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

        // Kiểm tra xem có tham số 'id' được truyền qua URL không
        if(isset($_GET['id']))
        {
            // Lấy giá trị 'id' từ URL
            $id = $_GET['id'];
            
            // Truy vấn để lấy thông tin liên hệ dựa trên 'id' nhận được
            $contact = "SELECT * FROM contact WHERE id_contact = '$id'";
            $rs_contact = mysqli_query($conn, $contact);
            $row_contact = mysqli_fetch_array($rs_contact);
        }

        // Xử lý khi người dùng nhấn nút lưu lại
        if(isset($_POST['save']))
        {
            // Lấy nội dung nhập từ form
            $content = $_POST['content'];
            $date_create = date("Y-m-d H:i:s");

            // Kiểm tra xem nội dung có được nhập không
            if($content)
            {
                // Thêm thông tin chỉnh sửa vào lịch sử
                $text = " đã chỉnh sửa thông tin liên hệ";
                $time = date('Y-m-d H:i:s');
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Cập nhật nội dung liên hệ trong cơ sở dữ liệu
                $ins = "UPDATE contact SET content = \"$content\" WHERE id_contact = $id";
                mysqli_query($conn, $ins);
                
                // Hiển thị thông báo và chuyển hướng về trang footer.php
                echo "<script>alert('Lưu lại thành công');</script>";
                echo "<script>location.href='footer.php';</script>";
            }
            else
            {
                // Hiển thị thông báo nếu nội dung không được nhập
                echo "<script>alert('Vui lòng nhập nội dung!.');</script>";
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
                            <h2 class="pageheader-title" style="font-family: 'Roboto Condensed', sans-serif;">Welcome to Admin</h2>
                            <!--
                            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                            -->
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Trang chủ</a></li>
                                        <li class="breadcrumb-item"><a href="footer.php" class="breadcrumb-link">Chân trang</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Cấu hình chân trang</li>
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
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Thêm thông tin liên hệ</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <form method="POST">
                                                <div class="form-group">
                                                    <label>Nội dung*:</label>
                                                    <!-- Hiển thị nội dung liên hệ hiện tại -->
                                                    <input type="text" name="content" class="form-control" value="<?php echo $row_contact['content']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày tạo:</label>
                                                    <!-- Hiển thị ngày tạo liên hệ -->
                                                    <input type="text" name="date_create" class="form-control" value="<?php echo date('d-m-Y H:i:s'); ?>" disabled>
                                                </div>
                                                <?php 

                                                    // Kiểm tra quyền của người dùng để hiển thị hoặc ẩn nút lưu lại
                                                    if($level == 1)
                                                    {
                                                        echo "<button type='submit' class='btn btn-warning' name='save'>Lưu lại</button>";
                                                    }
                                                    else
                                                    {
                                                        echo "<button type='submit' class='btn btn-warning' disabled>Lưu lại</button>";
                                                    }
                                                ?>
                                            </form>
                                        </div>
                                        <!-- col-lg-12 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- col-lg-12 -->
                    </div>
                    <!-- row -->
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
