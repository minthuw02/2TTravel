<?php 
    // Mở phiên làm việc
    session_start();
    // Kiểm tra xem người dùng đã đăng nhập và có quyền không
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Lấy cấp độ người dùng và email từ session
        $level = $_SESSION['level'];
        $users = $_SESSION['user'];

        // Bao gồm các tệp header, navbar và left-sidebar và file chứa các hàm
        include('includes/header.php');
        include('includes/navbar.php');
        include('includes/left-sidebar.php');
        require('modules/functions.php');

        // Lấy thông tin từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $name_user = $row_session['name'];
        $id_acc = $row_session['id_acc'];

        // Lấy dữ liệu sản phẩm từ URL nếu có
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
            $data = "SELECT image, name_product FROM product WHERE sku_product = '$id'";
            $rs_data = mysqli_query($conn, $data);
            $row_data = mysqli_fetch_array($rs_data);
        }

        // Xử lý khi người dùng thêm ảnh mới
        if(isset($_POST['add-image']))
        {
            // Thiết lập đường dẫn và tên file cho ảnh
            $target_dir = "public/images/products/";
            $image = $_FILES['image']['name'];
            $name_code = "m_".name_code($image);
            $imageFileType = strtolower(pathinfo($name_code, PATHINFO_EXTENSION));
            $target_file = $target_dir.$name_code;

            // Kiểm tra xem người dùng đã chọn ảnh chưa
            if($image)
            {
                // Di chuyển ảnh vào thư mục lưu trữ
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                // Thêm bản ghi vào cơ sở dữ liệu
                $ins = "INSERT INTO image(sku_product, name_image) VALUES('$id', '$name_code')";
                mysqli_query($conn, $ins);
                echo "<script>alert('Thêm ảnh thành công');</script>";
                echo "<script>location.href='anh-san-pham.php?id=".$id."';</script>";
            }
            else
            {
                echo "<script>alert('Vui lòng chọn ảnh!.');</script>";
            }
        }
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
                                <h2 class="pageheader-title" style="font-family: 'Roboto Condensed', sans-serif;">Quản trị website bán hàng</h2>
                                <!--
                                <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                                -->
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Trang chính</a></li>
                                            <li class="breadcrumb-item"><a href="san-pham.php" class="breadcrumb-link">Sản phẩm</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Tạo ảnh sản phẩm</li>
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
                            <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Tạo ảnh sản phẩm</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                                            <div class="card">
                                                <img class="card-img-top" src="public/images/products/<?php echo $row_data['image']; ?>" alt="<?php echo $row_data['image']; ?>">
                                            </div>
                                    </div>
                                    <!-- col-lg-3 -->
                                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                    <form method="POST" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <label>Chọn ảnh:</label>
                                                            <input type="file" name="image" class="form-control">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary" name="add-image">Thêm ảnh mới</button>
                                                    </form>
                                            </div>
                                            <!-- col-lg-12 -->
                                            <div class="col-lg-12 mt-3 mb-3">
                                                <div class="row">
                                                    <?php
                                                        // Lấy danh sách ảnh đã được thêm vào sản phẩm
                                                        $image = "SELECT id_image, name_image FROM image WHERE sku_product = '$id'";
                                                        $rs_image = mysqli_query($conn, $image);
                                                        while ($row_image = mysqli_fetch_array($rs_image)) 
                                                        {
                                                    ?>
                                                    <div class="col-lg-2 col-md-4 col-sm-6 col-4 mt-2">
                                                        <!-- Hiển thị ảnh và tạo liên kết để xóa ảnh -->
                                                        <a href="delete-image.php?id=<?php echo $row_image['id_image']; ?>&id_product=<?php echo $id ?>" title="Click để xóa ảnh" onclick="return confirm('Dữ liệu này sẽ được xóa vĩnh viễn. Đồng ý?')">
                                                            <img src="public/images/products/<?php echo $row_image['name_image']; ?>" alt="<?php echo $row_image['name_image']; ?>" class="rounded" width="100%">
                                                        </a>
                                                    </div>
                                                    <?php 
                                                            }
                                                            // Kết thúc vòng lặp while
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- row -->
                                    </div>
                                    <!-- col-lg-9 -->
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- col-lg-12 -->
                </div>
            </div>
        </div>
    </div>
<?php 
        // Footer
        include('includes/footer.php');
    }
    else
    {
        // Chuyển hướng người dùng đến trang đăng nhập nếu chưa đăng nhập
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>

