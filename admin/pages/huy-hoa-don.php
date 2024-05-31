<?php 
    
    // Mở phiên làm việc
    session_start();

    // Kiểm tra xem người dùng đã đăng nhập và có quyền không
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Lấy cấp độ người dùng và email từ session
        $level = $_SESSION['level'];
        $users = $_SESSION['user'];

        // Thêm tệp cấu hình cơ sở dữ liệu vào header để sử dụng trong tất cả các tệp
        require_once('modules/config.php');

        // Bao gồm tệp chứa các hàm
        require('modules/functions.php');

        // Lấy thông tin từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];
        
        // Hủy đơn hàng
        if(isset($_GET['cancel']))
        {
            $code_invoice = $_GET['cancel'];
            // Thêm hành động hủy vào lịch sử
            $text = " đã hủy đơn hàng <b>". $code_invoice . "</b>";
            $time = date('Y-m-d H:i:s');
            $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
            mysqli_query($conn, $ins_his);

            // Xóa chi tiết hóa đơn
            $del_detailinvoice = "DELETE FROM detail_invoice WHERE code_invoice = '$code_invoice'";
            mysqli_query($conn, $del_detailinvoice);

            // Xóa hóa đơn
            $del_invoice = "DELETE FROM invoice WHERE code_invoice = '$code_invoice'";
            mysqli_query($conn, $del_invoice);

            echo "<script>alert('Hủy đơn hàng thành công');</script>";

            // Chuyển đến trang van-chuyen.php nếu có tham số 'delivery' trong URL
            if(isset($_GET['delivery']))
            {
                echo "<script>location.href='van-chuyen.php';</script>";
            }  

            // Chuyển đến trang don-hang.php nếu có tham số 'invoice' trong URL
            if(isset($_GET['invoice']))
            {
                echo "<script>location.href='don-hang.php';</script>";
            }
        }
    }
?>
