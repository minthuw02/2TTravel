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
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];

        // Lấy dữ liệu từ cơ sở dữ liệu nếu có tham số 'id' được truyền qua URL
        if(isset($_GET['id']))
        {
            // Lấy giá trị 'id' từ URL
            $id = $_GET['id'];
            
            // Truy vấn để lấy thông tin về loại bài viết dựa trên 'id' nhận được
            $sql = "SELECT typename FROM type_blog WHERE id_type = $id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            $delname = $row['typename'];
        }

        // Xử lý khi người dùng nhấn nút lưu lại
        if(isset($_POST['save']))
        {
            // Lấy tên loại bài viết từ form
            $name_type = $_POST['name_type'];
            $slug = generateURL($name_type);

            // Kiểm tra xem tên loại bài viết có được nhập không
            if($name_type)
            {
                // Thêm thông tin chỉnh sửa vào lịch sử
                $text = " đã chỉnh sửa loại bài viết <b>". $delname . "</b> thành <b>". $name_type . "</b>";
                $time = date('Y-m-d H:i:s');
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Cập nhật tên loại bài viết trong cơ sở dữ liệu
                $update = "UPDATE type_blog SET typename = '$name_type', slug_type = '$slug' WHERE id_type = $id";
                mysqli_query($conn, $update);
                echo "<script>alert('Sửa loại thành công');</script>";
                echo "<script>location.href='loai-blog.php';</script>";
            }
            else
            {
                // Hiển thị thông báo nếu tên loại bài viết không được nhập
                echo "<script>alert('Vui lòng nhập tên loại!.');</script>";
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
                                        <li class="breadcrumb-item"><a href="blog.php" class="breadcrumb-link">Bài viết</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa loại bài viết</li>
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
                            <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Chỉnh sửa loại bài viết</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-xs-12">
                                        <form method="POST">
                                            <div class="form-group">
                                                <label>Tên loại*:</label>
                                                <!-- Hiển thị tên loại bài viết hiện tại -->
                                                <input type="text" name="name_type" class="form-control" value="<?php echo $row['typename']; ?>">
                                            </div>
                                            <button type="submit" class="btn btn-warning" name="save">Sửa</button>
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
