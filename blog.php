<?php 

	// include các file header và top
	include('includes/header.php');
	include('includes/top.php');

	// Kiểm tra xem có tham số id được truyền qua URL không
	if(isset($_GET['id']))
	{
		// Lấy giá trị của tham số id từ URL
		$id = $_GET['id'];

		// Truy vấn để lấy thông tin về loại blog dựa trên id
		$type = "SELECT id_type, typename, slug_type FROM type_blog WHERE id_type = $id";
		$rs_type = mysqli_query($conn, $type);
		$row_type = mysqli_fetch_array($rs_type);
		$slug_type = $row_type['slug_type'];

		// Xử lý phân trang
		// BƯỚC 2: TÌM TỔNG SỐ BÀI VIẾT
		$sql = "SELECT count(id_blog) as total FROM blog WHERE id_type = $id";
	    $result = mysqli_query($conn, $sql);
	    $row = mysqli_fetch_assoc($result);
	    $total_records = $row['total'];

	    // BƯỚC 3: TÌM LIMIT VÀ CURRENT_PAGE
	    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
	    $limit = 16;

	    // BƯỚC 4: TÍNH TOÁN TOTAL_PAGE VÀ START
	    // Tổng số trang
	    $total_page = ceil($total_records / $limit);

	    // Giới hạn current_page trong khoảng từ 1 đến total_page
	    if ($current_page > $total_page){
	        $current_page = $total_page;
	    }
	    else if ($current_page < 1){
	        $current_page = 1;
	    }

	    // Tính toán vị trí bắt đầu
	    $start = ($current_page - 1) * $limit;
	}
?>
<!-- Phần nội dung chính -->
<div id="content">
    <!-- Breadcrumb -->
    <div class="breadcrumb_me">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb_list">
                        <li><a href="trang-chu.html">Trang chủ</a></li>
                        <li><i class="fas fa-caret-right"></i></li>
                        <li><?php echo $row_type['typename']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Nội dung chính -->
    <div class="mid_content" style="background: #fff; margin-top: 0;">
        <div class="container">
            <div class="row">
                <!-- Quảng cáo -->
                <div class="col-lg-12 padding-30">
                    <div class="title_module_main">
                        <h2>
                            <a href="loai-blog/<?php echo $row_type['slug_type']; ?>-<?php echo $row_type['id_type']; ?>-<?php echo $current_page; ?>.html" title="<?php echo $row_type['typename']; ?>"><?php echo $row_type['typename']; ?></a>
                        </h2>
                    </div>
                    <div class="product_main">
                        <div class="row">
                            <div class="col-lg-9 col-xs-12">
                                <!-- Hiển thị danh sách bài viết -->
    <?php 
        // Truy vấn để lấy danh sách bài viết của loại blog
        $blog = "SELECT id_blog, b.image as image, title, date_upload, b.slug as slug, slug_type, typename, author, summary, name FROM blog b, type_blog tb, account a WHERE b.id_type = tb.id_type AND a.id_acc = b.author AND b.id_type = $id AND flag = 1 ORDER BY date_upload DESC LIMIT $start, $limit";
        $rs_blog = mysqli_query($conn, $blog);
        while ($row_blog = mysqli_fetch_array($rs_blog))
        {
    ?>
                                <!-- Mỗi bài viết -->
                                <div class="blog_item">
                                    <div class="image_blog_left">
                                        <a href="blog/<?php echo $row_blog['slug_type']; ?>/<?php echo $row_blog['slug']; ?>-<?php echo $row_blog['id_blog']; ?>.html">
                                            <img src="admin/pages/public/images/blogs/<?php echo $row_blog['image']; ?>" alt="<?php echo $row_blog['image']; ?>" title="<?php echo $row_blog['title']; ?>">
                                        </a>
                                    </div>
                                    <div class="content_right_blog">
                                        <div class="title_blog_home">
                                            <h3>
                                                <a href="blog/<?php echo $row_blog['slug_type']; ?>/<?php echo $row_blog['slug']; ?>-<?php echo $row_blog['id_blog']; ?>.html" title="<?php echo $row_blog['title']; ?>">
                                                    <?php echo $row_blog['title']; ?>
                                                </a>
                                            </h3>
                                        </div>
                                        <div class="content_day_blog">
                                            <span class="fix_left_blog">
                                                <i class="far fa-calendar-alt"></i>
                                                <span>
                                                    <?php
                                                        // Lấy ngày
                                                        $day = date('D', $time = strtotime($row_blog['date_upload']) );

                                                        if($day == "Mon")
                                                        {
                                                            echo "Thứ hai";
                                                        }
                                                        else if($day == "Tue")
                                                        {
                                                            echo "Thứ ba";
                                                        }
                                                        else if($day == "Wed")
                                                        {
                                                            echo "Thứ tư";
                                                        }
                                                        else if($day == "Thu")
                                                        {
                                                            echo "Thứ năm";
                                                        }
                                                        else if($day == "Fri")
                                                        {
                                                            echo "Thứ sáu";
                                                        }
                                                        else if($day == "Sat")
                                                        {
                                                            echo "Thứ bảy";
                                                        }
                                                        else
                                                        {
                                                            echo "Chủ nhật";
                                                        }
                                                    ?>, 
                                                </span>
                                                <span class="news_home_content_short_time">
                                                    <?php 
                                                        $date = date_create($row_blog['date_upload']);
                                                        echo date_format($date, "d/m/Y");
                                                    ?>
                                                </span>
                                            </span>
                                        </div>
                                        <p class="blog_item_summary">
                                            <?php 
                                                echo mb_substr($row_blog['summary'], 0, 100, 'UTF-8')."..."; 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Kết thúc mỗi bài viết -->
    <?php 
        }
        // Kết thúc vòng lặp while
    ?>   
                                <!-- Phân trang -->
                                <ul class="pagination_list">
                                    <?php 
                                        // Hiển thị nút phân trang
                                        if($current_page > 1)
                                        {   
                                            echo "<li><a href='loai-blog/".$slug_type."-".$id."-1.html'><i class='fas fa-angle-double-left'></i></a></li>";
                                            echo "<li><a href='loai-blog/".$slug_type."-".$id."-".($current_page - 1).".html'><i class='fas fa-angle-left'></i></a></li>";
                                        }

                                        for($i = 1; $i <= $total_page; $i++)
                                        {
                                            if($current_page == $i)
                                            {
                                                echo "<li><a href='loai-blog/".$slug_type."-".$id."-".$i.".html' class='active'>".$i."</a></li>";
                                            }
                                            else
                                            {
                                                echo "<li><a href='loai-blog/".$slug_type."-".$id."-".$i.".html'>".$i."</a></li>";
                                            }
                                        }

                                        if($current_page < $total_page)
                                        {
                                            echo "<li><a href='loai-blog/".$slug_type."-".$id."-".($current_page + 1).".html'><i class='fas fa-angle-right'></i></a></li>";
                                            echo "<li><a href='loai-blog/".$slug_type."-".$id."-".($total_page).".html'><i class='fas fa-angle-double-right'></i></a></li>";
                                        }
                                    ?>
                                </ul>
                                <!-- Kết thúc phân trang -->
                            </div>
                            <!-- Kết thúc col-lg-9 -->
                            <div class="col-lg-3 col-xs-12">
                                <!-- Sidebar -->
                                <div class="sidebar_blog">
                                    <h2 class="title_head">
                                        <span> Danh mục tin tức </span>
                                    </h2>
                                    <ul class="aside_category">
    <?php 
        // Truy vấn để lấy danh sách các loại blog
        $typeb = "SELECT * FROM type_blog WHERE id_type <> 25 AND id_type <> 26 ORDER BY typename DESC";
        $rs_typeb = mysqli_query($conn, $typeb);
        while ($row_typeb = mysqli_fetch_array($rs_typeb)) 
        {
    ?>
                                        <li><a href="loai-blog/<?php echo $row_typeb['slug_type']; ?>-<?php echo $row_typeb['id_type']; ?>-1.html"><?php echo $row_typeb['typename']; ?></a></li>
    <?php 
        }
        // Kết thúc vòng lặp while
    ?>
                                    </ul>
                                </div>
                                <!-- Kết thúc sidebar -->
                                <div class="blog_aside">
                                    <!-- Danh sách bài viết nổi bật -->
                                    <h2 class="title_head">
                                        <span> Bài viết nổi bật</span>
                                    </h2>
    <?php
        // Truy vấn để lấy danh sách các bài viết nổi bật
        $blog_hl = "SELECT id_blog, b.image as image, title, date_upload, b.slug as slug, slug_type, typename, author, summary, name, view, content FROM blog b, type_blog tb, account a WHERE b.id_type = tb.id_type AND a.id_acc = b.author AND flag = 1 AND highlight = 1 ORDER BY date_upload DESC LIMIT 10";
        $rs_blog_hl = mysqli_query($conn, $blog_hl);
        while ($row_blog_hl = mysqli_fetch_array($rs_blog_hl))
        {
    ?>
                                    <div class="blog_list">
                                        <div class="loop_blog">
                                            <div class="thumb_left">
                                                <a href="blog/<?php echo $row_blog_hl['slug_type']; ?>/<?php echo $row_blog_hl['slug']; ?>-<?php echo $row_blog_hl['id_blog']; ?>.html">
                                                    <img src="admin/pages/public/images/blogs/<?php echo $row_blog_hl['image']; ?>" alt="<?php echo $row_blog_hl['image']; ?>" title="<?php echo $row_blog_hl['title']; ?>">
                                                </a>
                                            </div>
                                            <div class="name_right">
                                                <h3 class="text2line">
                                                    <a href="blog/<?php echo $row_blog_hl['slug_type']; ?>/<?php echo $row_blog_hl['slug']; ?>-<?php echo $row_blog_hl['id_blog']; ?>.html" title="<?php echo $row_blog_hl['title']; ?>">
                                                        <?php 
                                                            echo mb_substr($row_blog_hl['title'], 0,40, 'UTF-8')."..."; 
                                                        ?>
                                                    </a>
                                                </h3>
                                                <div class="date">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <span>
                                                        <?php
                                                            // Lấy ngày
                                                            $day = date('D', $time = strtotime($row_blog_hl['date_upload']) );

                                                            if($day == "Mon")
                                                            {
                                                                echo "Thứ hai";
                                                            }
                                                            else if($day == "Tue")
                                                            {
                                                                echo "Thứ ba";
                                                            }
                                                            else if($day == "Wed")
                                                            {
                                                                echo "Thứ tư";
                                                            }
                                                            else if($day == "Thu")
                                                            {
                                                                echo "Thứ năm";
                                                            }
                                                            else if($day == "Fri")
                                                            {
                                                                echo "Thứ sáu";
                                                            }
                                                            else if($day == "Sat")
                                                            {
                                                                echo "Thứ bảy";
                                                            }
                                                            else
                                                            {
                                                                echo "Chủ nhật";
                                                            }
                                                        ?>, 
                                                    </span>
                                                    <span>
                                                        <?php 
                                                            $date = date_create($row_blog_hl['date_upload']);
                                                            echo date_format($date, "d/m/Y");
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Kết thúc mỗi bài viết -->
    <?php 
        }
        // Kết thúc vòng lặp while
    ?> 
                                </div>
                                <!-- Kết thúc danh sách bài viết nổi bật -->
                                <!-- Sản phẩm nổi bật -->
                                <div class="blog_aside">
                                    <h2 class="title_head">
                                        <span> Sản phẩm nổi bật </span>
                                    </h2>
    <?php
        // Truy vấn để lấy danh sách các sản phẩm nổi bật
        $product_hl = "SELECT sku_product, p.image as image, name_product, price FROM product p, type_product tp, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND flag = 1 AND highlight = 1 ORDER BY date_upload DESC LIMIT 10";
        $rs_product_hl = mysqli_query($conn, $product_hl);
        while ($row_product_hl = mysqli_fetch_array($rs_product_hl)) 
        {
    ?>
                                    <div class="product_item_small">
                                        <div class="left_item">
                                            <a href="chi-tiet-san-pham.php?id=<?php echo $row_product_hl['sku_product']; ?>">
                                                <img src="admin/pages/public/images/products/<?php echo $row_product_hl['image']; ?>" alt="<?php echo $row_product_hl['image']; ?>" title="<?php echo $row_product_hl['name_product']; ?>">
                                            </a>
                                        </div>
                                        <div class="product_info">
                                            <h3><a href="chi-tiet-san-pham.php?id=<?php echo $row_product_hl['sku_product']; ?>" title="<?php echo $row_product_hl['name_product']; ?>">
                                                <?php 
                                                    echo mb_substr($row_product_hl['name_product'], 0,40, 'UTF-8')."..."; 
                                                ?>
                                            </a></h3>
                                            <div class="price_box_mini">
                                                <span><?php echo number_format($row_product_hl['price']); ?>đ</span>
                                            </div>
                                        </div>
                                    </div>
    <?php 
        }
        // Kết thúc vòng lặp while
    ?>
                                </div>
                                <!-- Kết thúc sản phẩm nổi bật -->
                            </div>
                            <!-- Kết thúc col-lg-3 -->
                        </div>
                    </div>
                </div>
                <!-- Kết thúc col-lg-12 -->
            </div>
            <!-- Kết thúc row -->
        </div>
        <!-- Kết thúc container -->
    </div>
    <!-- Kết thúc nội dung trung tâm -->
</div>
<!-- Kết thúc nội dung -->
<?php 

	// Footer
	include('includes/footer.php');
?>
