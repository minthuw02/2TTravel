<?php 
    
    // Mở session
    session_start();

    // Kiểm tra xem session 'user' và 'level' có tồn tại không
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Lấy giá trị của session 'level' và 'user'
        $level = $_SESSION['level'];
        $users = $_SESSION['user'];

        // Bao gồm các file header, navbar, left-sidebar
        include('includes/header.php');
        include('includes/navbar.php');
        include('includes/left-sidebar.php');

        // Bao gồm các hàm trong file functions.php
        require('modules/functions.php');

        // Truy vấn để lấy thông tin từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $name_user = $row_session['name'];
        $id_acc = $row_session['id_acc'];

        // Lấy dữ liệu
        if(isset($_GET['id']))
        {
            // Lấy id tour từ URL
            $id = $_GET['id'];
            
            // Truy vấn để lấy thông tin về tour dựa trên 'sku_tour'
            $data = "SELECT image, sku_tour, name_tour, highlight, qty, price, summary, content FROM tour WHERE sku_tour = '$id'";
            $rs_data = mysqli_query($conn, $data);
            $row_data = mysqli_fetch_array($rs_data);
            $old_image = $row_data['image'];
        }

        // Xử lý khi người dùng nhấn nút upload
        if(isset($_POST['upload']))
        {
            // Lấy dữ liệu từ form
            $name_tour = $_POST['name_tour'];
            $slug = generateURL($name_tour);
            $summary = $_POST['summary'];
            $content = $_POST['content'];
            $qty = $_POST['qty'];
            $price = $_POST['price'];
            $highlight = $_POST['highlight'];
            $id_type = $_POST['id_type'];


            // Thiết lập đường dẫn cho ảnh thumbnail
            $target_dir = "public/images/tours/";
            $image = $_FILES['image']['name'];
            $name_code = name_code($image);
            $imageFileType = strtolower(pathinfo($name_code, PATHINFO_EXTENSION));
            $target_file = $target_dir.$name_code;

            if($image)
            {
                if($name_tour)
                {
                    // Kiểm tra định dạng ảnh
                    if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif")
                    {
                        echo "<script>alert('Chỉ nhận file ảnh dạng: jpg, jpeg, png, gif.');</script>";
                    }
                    else
                    {
                        // Thêm vào lịch sử
                        $text = " đã chỉnh sửa tour <b>". $name_tour . "</b>";
                        $time = date('Y-m-d H:i:s');
                        $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                        mysqli_query($conn, $ins_his);

                        // Xóa file cũ (nếu có)
                        $remove_image = $target_dir . $old_image;
                        if($old_image != "no-image.png")
                        {
                            unlink($remove_image);
                        }

                        // Upload file mới
                        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

                        // Cập nhật thông tin tour
                        $update = "UPDATE tour SET
                                image = '".$name_code."',
                                name_tour = '".$name_tour."',
                                slug = '".$slug."',
                                summary = '".$summary."',
                                content = '".$content."',
                                qty = '".$qty."',
                                price = '".$price."',
                                highlight = '".$highlight."',
                                id_type = '".$id_type."'
                                WHERE sku_tour = '$id'";
                        mysqli_query($conn, $update);
                        echo "<script>alert('Lưu lại thành công');</script>";
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
                    $text = " đã chỉnh sửa tour <b>". $name_tour . "</b>";
                    $time = date('Y-m-d H:i:s');
                    $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                    mysqli_query($conn, $ins_his);

                    // Cập nhật thông tin tour
                    $update = "UPDATE tour SET
                            name_tour = '".$name_tour."',
                            slug = '".$slug."',
                            summary = '".$summary."',
                            content = '".$content."',
                            qty = '".$qty."',
                            price = '".$price."',
                            highlight = '".$highlight."',
                            id_type = '".$id_type."'
                            WHERE sku_tour = '$id'";
                    mysqli_query($conn, $update);
                    echo "<script>alert('Lưu lại thành công');</script>";
                    echo "<script>location.href='tour.php';</script>";
                }
                else
                {
                    echo "<script>alert('Vui lòng nhập tên tour');</script>";
                }
            }
        }
        // Kết thúc xử lý khi người dùng nhấn nút upload
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
                                                    <input class="form-control" type="text" name="sku_tour" value="<?php echo $row_data['sku_tour']; ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tên tour*:</label>
                                                    <textarea class="form-control" name="name_tour"><?php echo $row_data['name_tour']; ?></textarea>
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
                                                        $type_ac = "SELECT tp.id_type as id_type, typename FROM type_tour tt, tour t WHERE p.id_type = tp.id_type AND sku_tour = '$id'";
                                                        $rs_type_ac = mysqli_query($conn, $type_ac);
                                                        $row_type_ac = mysqli_fetch_array($rs_type_ac);
                                                        $id_type_ac = $row_type_ac['id_type'];

                                                    ?>
                                                        <option value="<?php echo $row_type_ac['id_type']; ?>"><?php echo $row_type_ac['typename']; ?></option>

                                                    <?php 

                                                        // Hiển thị loại tour
                                                        $type_op = "SELECT id_type, typename FROM type_tour WHERE id_type <> $id_type_ac";
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
                                                    <?php 

                                                        if($row_data['highlight'] == 1)
                                                        {
                                                    ?>
                                                        <option value="1">Có</option>
                                                        <option value="0">Không</option>
                                                    <?php
                                                        }
                                                        else
                                                        {
                                                    ?>
                                                        <option value="0">Không</option>
                                                        <option value="1">Có</option>
                                                    <?php
                                                        }
                                                    ?>    
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Số lượng nhập hàng:</label>
                                                    <input class="form-control" type="text" name="qty"value="<?php echo $row_data['qty']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Giá tour:</label>
                                                    <input class="form-control" type="text" name="price" value="<?php echo $row_data['price']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Mô tả ngắn:</label>
                                                    <textarea class="form-control" name="summary"><?php echo $row_data['summary']; ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Bài viết về tour:</label>
                                                     <textarea class="form-control" id="ckeditor" name="content"><?php echo $row_data['content']; ?></textarea>
                                                </div>
                                                <button type='submit' class='btn btn-warning' name='upload'>Lưu lại</button>
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
        // footer
        include('includes/footer.php');
    }
    else
    {
        // Chuyển hướng đến trang dang-nhap.php nếu không có session
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>
