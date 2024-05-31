<?php 
    
    // Mở phiên làm việc
    session_start();

    // Kiểm tra xem người dùng đã đăng nhập và có quyền không
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Nếu đã đăng nhập và có quyền, chuyển hướng đến trang index.php
        echo "<script>location.href='index.php';</script>";
    }
    else
    {
        // Bao gồm tệp config.php chứa cấu hình cơ sở dữ liệu
        include('modules/config.php');

        // Xử lý đăng nhập khi người dùng gửi biểu mẫu
        if(isset($_POST['dangnhap']))
        {
            // Lấy dữ liệu từ biểu mẫu
            $email = $_POST['email'];
            $password = md5($_POST['password']); // Mã hóa mật khẩu với MD5

            if($email)
            {
                // Kiểm tra xem mật khẩu có trống không
                if($password != "d41d8cd98f00b204e9800998ecf8427e") // kiểm tra mật khẩu có rỗng không
                {
                
                    // Truy vấn cơ sở dữ liệu để kiểm tra đăng nhập
                    $sql = "SELECT email, level FROM account WHERE email = '$email' AND password = '$password'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);
                    $level = $row['level'];
                    if(mysqli_num_rows($result) == 1)
                    {
                        // Tạo session và chuyển hướng đến trang index.php
                        $_SESSION['user'] = $email;
                        $_SESSION['level'] = $level;
                        echo "<script>location.href='index.php';</script>";
                    }
                    else
                    {
                        // Thông báo lỗi khi tài khoản không tồn tại
                        echo "<script>alert('Tài khoản không tồn tại!.');</script>";
                    }
                }
                else
                {
                   // Thông báo lỗi khi mật khẩu trống
                   echo "<script>alert('Vui lòng nhập mật khẩu!.');</script>";    
                }
            }
            else
            {
                // Thông báo lỗi khi email trống
                echo "<script>alert('Vui lòng nhập email!.');</script>";   
            }
        }
        // end funtion

?>
<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản trị - Website bán hàng</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/libs/css/style.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">

    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        font-family: 'Roboto Condensed', sans-serif;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"></a><span class="splash-description">Đăng nhập tài khoản</span></div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="username" type="email" placeholder="Nhập email" name="email" value="<?php if(isset($email)){ echo $email; } ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="password" type="password" placeholder="Nhập mật khẩu" name="password">
                    </div>
                    <!-- 
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"><span class="custom-control-label">Remember Me</span>
                        </label>
                    </div>
                    -->
                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="dangnhap">Đăng nhập</button>
                </form>
            </div>
            <!-- 
            <div class="card-footer bg-white p-0  ">
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Create An Account</a></div>
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Forgot Password</a>
                </div>
            </div>
            -->
        </div>
    </div>
  
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>
 
</html>
<?php 
    }
    // end check session
?>

