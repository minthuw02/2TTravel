<?php 
    
    // Mở phiên session
    session_start();

    // Kiểm tra nếu session 'user' và 'level' tồn tại
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Lấy giá trị từ session
        $level = $_SESSION['level'];
        $users = $_SESSION['user'];

        // Bao gồm các file header, navbar và left-sidebar
        include('includes/header.php');
        include('includes/navbar.php');
        include('includes/left-sidebar.php');

        // Bao gồm file chứa các hàm
        require('modules/functions.php');

        // Lấy thông tin từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $name_user = $row_session['name'];
        $id_acc = $row_session['id_acc'];

        // Lấy bản ghi tour cuối cùng để thiết lập SKU
        $last = "SELECT sku_tour FROM tour ORDER BY sku_tour DESC";
        $rs_last = mysqli_query($conn, $last);
        $row_last = mysqli_fetch_array($rs_last);
        $sku_last = $row_last['sku_tour'];
        $cut_num = substr($sku_last, 1);
        $number_last = (int)$cut_num;
        $number_inc = $number_last + 1;

        if($number_inc < 10)
        {
            $sku_tour = "S0" . $number_inc;
        }
        else
        {
            $sku_tour = "S" . $number_inc;
        }

        // Upload tour
        if(isset($_POST['upload']))
        {
            // Lấy dữ liệu từ form
            $name_tour = $_POST['name_tour'];
            $slug = generateURL($name_tour);
            $summary = $_POST['summary'];
            $content = $_POST['content'];
            $date_upload = date("Y-m-d H:i:s");
            $author = $id_acc;
            $qty = $_POST['qty'];
            $price = $_POST['price'];
            $highlight = $_POST['highlight'];
            $view = 0;
            $id_type = $_POST['id_type'];
            $flag = 0;


            // Thiết lập ảnh thumbnail
            $target_dir = "public/images/tours/";
            $image = $_FILES['image']['name'];
            $name_code = name_code($image);
            $imageFileType = strtolower(pathinfo($name_code, PATHINFO_EXTENSION));
            $target_file = $target_dir.$name_code;

            if($image)
            {
                if($name_tour)
                {
                    if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif")
                    {
                        echo "<script>alert('Chỉ nhận file ảnh dạng: jpg, jpeg, png, gif.');</script>";
                    }
                    else
                    {
                        // Thêm vào lịch sử
                        $text = " Đã đăng tour <b>". $name_tour . "</b>";
                        $time = date('Y-m-d H:i:s');
                        $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                        mysqli_query($conn, $ins_his);

                        // Upload file lên server
                        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

                        // Thêm bản ghi mới
                        $ins = "INSERT INTO tour(sku_tour, image, name_tour, slug, summary, content, date_upload, author, qty, price, highlight, view, id_type, flag) VALUES('".$sku_tour."', '".$name_code."', '".$name_tour."', '".$slug."', '".$summary."', '".$content."', '".$date_upload."', '".$author."', '".$qty."', '".$price."', '".$highlight."', '".$view."', '".$id_type."', '".$flag."')";
                        mysqli_query($conn, $ins);
                        echo "<script>alert('Upload tour thành công');</script>";
                        echo "<script>location.href='tour.php';</script>";
                    }
                }
                else
                {
                    echo "<script>alert('Vui lòng nhập tên tour');</script>";
                }
            }
            else
            {
                if($name_tour)
                {
                    // Thêm vào lịch sử
                    $text = " Đã đăng tour <b>". $name_tour . "</b>";
                    $time = date('Y-m-d H:i:s');
                    $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                    mysqli_query($conn, $ins_his);
                            
                    $name_code = "no-image.png";

                    // Thêm bản ghi mới
                    $ins = "INSERT INTO tour(sku_tour, image, name_tour, slug, summary, content, date_upload, author, qty, price, highlight, view, id_type, flag) VALUES('".$sku_tour."', '".$name_code."', '".$name_tour."', '".$slug."', '".$summary."', '".$content."', '".$date_upload."', '".$author."', '".$qty."', '".$price."', '".$highlight."', '".$view."', '".$id_type."', '".$flag."')";
                    mysqli_query($conn, $ins);
                    echo "<script>alert('Upload tour thành công');</script>";
                    echo "<script>location.href='tour.php';</script>";
                }
                else
                {
                    echo "<script>alert('Vui lòng nhập tên tour');</script>";
                }
            }
        }
        // Kết thúc việc upload tour
?>
<body>
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content " >
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header" >
                                <h2 class="pageheader-title" style="font-family: 'Roboto Condensed', sans-serif;">Welcome to Admin</h2>
                                <!--
                                <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                                -->
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Trang chủ</a></li>
                                            <li class="breadcrumb-item"><a href="tour.php" class="breadcrumb-link">Tour</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Đăng tải tour</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <div class="ecommerce-widget">
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Đăng tour mới</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label>Mã tour (SKU):</label>
                                                    <input class="form-control" type="text" name="sku_tour" value="<?php echo $sku_tour; ?> (Mã tự tạo)" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tên tour*:</label>
                                                    <textarea class="form-control" name="name_tour"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Chọn ảnh tour:</label>
                                                    <input type="file" class="form-control" name="image">
                                                </div>
                                                <div class="form-group">
                                                    <label>Chọn loại tour:</label>
                                                    <select class="form-control" name="id_type">
                                <?php 

                                    // Hiển thị loại tour
                                    $type_op = "SELECT id_type, typename FROM type_tour";
                                    $rs_type_op = mysqli_query($conn, $type_op);
                                    while ($row_type_op = mysqli_fetch_array($rs_type_op))
                                    {
                                ?>
                                                        <option value="<?php echo $row_type_op['id_type']; ?>"><?php echo $row_type_op['typename']; ?></option>
                                <?php 
                                    }
                                    // Kết thúc vòng lặp
                                ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tour nổi bật:</label>
                                                    <select class="form-control" name="highlight">
                                                        <option value="1">Có</option>
                                                        <option value="0">Không</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Số lượng:</label>
                                                    <input class="form-control" type="text" name="qty"value="1">
                                                </div>
                                                <div class="form-group">
                                                    <label>Giá tour:</label>
                                                    <input class="form-control" type="text" name="price" value="1000">
                                                </div>
                                                <div class="form-group">
                                                    <label>Mô tả ngắn:</label>
                                                    <textarea class="form-control" name="summary"><?php if(isset($summary)){ echo $summary; } ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Bài viết về tour:</label>
                                                     <textarea class="form-control" id="ckeditor" name="content"><?php if(isset($content)){ echo $content; } ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày đăng:</label>
                                                    <input type="text" name="date_upload" class="form-control" value="<?php echo date('d-m-Y H:i:s'); ?>" disabled>
                                                </div>
                                                <button type='submit' class='btn btn-primary' name='upload'>Đăng lên</button>
                                            </form>
                                        </div>
                                        <!-- col-lg-12 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- col-lg-12 -->
                    </div>
                </div>
            </div>
<?php 
        // Footer
        include('includes/footer.php');
    }
    else
    {
        // Chuyển hướng đến trang đăng nhập nếu session không tồn tại
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>
