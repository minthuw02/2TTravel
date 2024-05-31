<?php 

	// Điều chỉnh file header và top
	include('includes/header.php'); // Bao gồm file header
	include('includes/top.php'); // Bao gồm file top

	// Lấy thông tin blog theo ID
	if(isset($_GET['id'])) { // Kiểm tra xem có tham số 'id' được thiết lập trong URL không
		$id = $_GET['id']; // Lấy giá trị 'id' từ URL

		// Chọn thông tin blog dựa trên ID
		$sql = "SELECT id_blog, b.image as image, title, date_upload, b.slug as slug, slug_type, typename, author, summary, name, view, content, tb.id_type as id_type FROM blog b, type_blog tb, account a WHERE b.id_type = tb.id_type AND a.id_acc = b.author AND flag = 1 AND id_blog = $id";
		$result = mysqli_query($conn, $sql); // Thực thi truy vấn SQL
		$row = mysqli_fetch_array($result); // Lấy dòng kết quả

		// Tăng lượt xem
		$view_current = $row['view']; // Lấy số lượt xem hiện tại
		$inc_view = $view_current + 1; // Tăng số lượt xem
		$update_v = "UPDATE blog SET view = $inc_view WHERE id_blog = $id"; // Cập nhật số lượt xem trong cơ sở dữ liệu
		mysqli_query($conn, $update_v);  // Thực thi truy vấn cập nhật

		
	}
	
?>

		<div id="content">
			<!-- Slider trên cùng (không có) -->
			<div class="breadcrumb_me">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<ul class="breadcrumb_list">
								<li><a href="trang-chu.html">Trang chủ</a></li> <!-- Liên kết về trang chủ -->
								<li><i class="fas fa-caret-right"></i></li>
								<li><a href="loai-blog/<?php echo $row['slug_type'] ?>-<?php echo $row['id_type'] ?>-1.html"><?php echo $row['typename']; ?></a></li> <!-- Liên kết đến danh mục blog -->
								<li><i class="fas fa-caret-right"></i></li>
								<li><?php echo $row['title']; ?></li> <!-- Tiêu đề blog -->
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="mid_content" style="background: #fff; margin-top: 0;">
				<div class="container">
					<div class="row">
						<!-- Quảng cáo (không có) -->
						<div class="col-lg-12 padding-30">
							<div class="tour_main">
								<div class="row">
								    <div class="col-lg-9 col-xs-12">
								    	<div class="article_detail">
								    		<h1 class="article_title">
								    			<a href="blog.php?id= ?>"> <?php echo $row['title']; ?> </a> <!-- Tiêu đề blog với liên kết -->
								    		</h1>
								    		<div class="content_day_blog padding-15">
							    				<span class="fix_left_blog pr-3">
							    					<i class="far fa-calendar-alt"></i>
							    					<span>
							    					<?php

							    					 	// Lấy ngày
							    					 	$day = date('D', $time = strtotime($row['date_upload']) );

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
							    					?>
							    					, 
							    					</span>
								    				<span class="news_home_content_short_time">
								    					<?php 
								    						$date = date_create($row['date_upload']);
								    						echo date_format($date, "d/m/Y");
								    					?>
								    				</span>
							    				</span>
							    				<span class="fix_left_blog">
							    					<i class="fas fa-user"></i>
							    					<span>Đăng bởi: </span>
								    				<span class="news_home_content_short_time">
								    					<strong> <?php echo $row['name']; ?> </strong> <!-- Tác giả blog -->
								    				</span>
							    				</span>
							    				<span class="fix_left_blog ml-3">
							    					<i class="fas fa-eye"></i>
							    					<span>Lượt xem: </span>
								    				<span class="news_home_content_short_time">
								    					<strong> <?php echo number_format($row['view']); ?> </strong> <!-- Số lượt xem -->
								    				</span>
							    				</span>
							    			</div>
							    			<!-- content_day_blog -->
							    			<div class="article_content">
							    				<div class="rte">
							    					<p><b><?php echo $row['summary']; ?></b></p> <!-- Tóm tắt của blog -->
							    					<p><?php echo $row['content']; ?></p> <!-- Nội dung của blog -->
							    				</div>
							    				<!-- rte -->
							    			</div>
							    			<!-- article content -->
								    	</div>
								    	<!-- article detail -->
								    	
								    </div>
								    <!-- col-lg-9 col-xs-12 -->
								    <div class="col-lg-3 col-xs-12">
								    	<?php include('includes/blog-relative.php'); ?> <!-- Bao gồm file blog-relative.php -->
								    	<div class="blog_aside">
								    		<h2 class="title_head">
								    			<span> Sản phẩm nổi bật </span> <!-- Tiêu đề sản phẩm nổi bật -->
								    		</h2>
	<?php

		// Sản phẩm nổi bật
		$tour_hl = "SELECT sku_tour, p.image as image, name_tour, price, p.slug as slug, slug_type FROM tour p, type_tour tp, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND flag = 1 AND highlight = 1 ORDER BY date_upload DESC LIMIT 10";
        $rs_tour_hl = mysqli_query($conn, $tour_hl); // Thực thi truy vấn SQL
        while ($row_tour_hl = mysqli_fetch_array($rs_tour_hl)) { // Lặp qua kết quả trả về
	?>
								    		<div class="tour_item_small">
								    			<div class="left_item">
								    				<a href="san-pham/<?php echo $row_tour_hl['slug_type']; ?>/<?php echo $row_tour_hl['slug']; ?>-<?php echo $row_tour_hl['sku_tour']; ?>.html"> <!-- Liên kết sản phẩm -->
								    					<img src="admin/pages/public/images/tours/<?php echo $row_tour_hl['image']; ?>" alt="<?php echo $row_tour_hl['image']; ?>" title="<?php echo $row_tour_hl['name_tour']; ?>"> <!-- Hình ảnh sản phẩm -->
								    				</a>
								    			</div>
								    			<div class="tour_info">
								    				<h3>
								    					<a href="san-pham/<?php echo $row_tour_hl['slug_type']; ?>/<?php echo $row_tour_hl['slug']; ?>-<?php echo $row_tour_hl['sku_tour']; ?>.html" title="<?php echo $row_tour_hl['name_tour']; ?>">
								    					<?php echo mb_substr($row_tour_hl['name_tour'], 0, 35, 'UTF-8')."..."; ?> <!-- Tên sản phẩm -->
								    					</a>
								    				</h3>
								    				<div class="price_box_mini">
								    					<span><?php echo number_format($row_tour_hl['price']); ?>đ</span> <!-- Giá sản phẩm -->
								    				</div>
								    			</div>
								    		</div>
								    		<!-- tour_item_small -->
	<?php 
		}
	?>
								    	</div>
								    	<!-- tour aside -->
								    </div>
								    <!-- col-lg-3 col xs-12 -->
								</div>
							</div>
						</div>
						<!-- col-lg-12 -->
					</div>
					<!-- row -->
				</div>
				<!-- cotainer -->
			</div>
			<!-- middle content -->
			<!-- bottom content -->
		</div>
		<!-- content -->
<?php 
	
	// Chân trang
	include('includes/footer.php'); // Bao gồm file footer.php
?>

