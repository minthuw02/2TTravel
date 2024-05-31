<?php 

	// Mở session
    session_start();

    // Kiểm tra nếu session 'user' và 'level' tồn tại
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

        // Lấy dữ liệu từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];

        // Kiểm tra xem có tham số code_invoice được truyền vào không
        if(isset($_GET['code_invoice']))
        {
            $code_invoice = $_GET['code_invoice'];

            // Thêm bản ghi vào lịch sử
            $text = " Xác nhận hoàn tất chuyến đi <b>". $code_invoice . "</b>";
            $time = date('Y-m-d H:i:s');
            $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
            mysqli_query($conn, $ins_his);

            // Tính lại số lượng 
            $detail_invoice = "SELECT sku_product, qty FROM detail_invoice WHERE code_invoice = '$code_invoice'";
            $rs_detail_invoice = mysqli_query($conn, $detail_invoice);
            while ($row_detail_invoice = mysqli_fetch_array($rs_detail_invoice)) 
            {
                $qty_invoice = $row_detail_invoice['qty'];
                $sku_product = $row_detail_invoice['sku_product'];
                
                // Lấy số lượng tour từ cơ sở dữ liệu
                $product = "SELECT qty FROM product WHERE sku_product = '$sku_product'";
                $rs_product = mysqli_query($conn, $product);
                $row_product = mysqli_fetch_array($rs_product);
                $qty_product = $row_product['qty'];

                // Tính toán lại số lượng tour
                $recalcu = $qty_product - $qty_invoice;

                // Cập nhật số lượng tour
                $update = "UPDATE product SET qty = '$recalcu' WHERE sku_product = '$sku_product'";
                mysqli_query($conn, $update);
            }

            // Cập nhật trạng thái
            $update = "UPDATE invoice SET flag = 2 WHERE code_invoice = '$code_invoice'";
            $rs_update = mysqli_query($conn, $update);
            
            // Thông báo hoàn tất đơn hàng
            echo "<script>alert('Hoàn tất đơn hàng');</script>";

            // Chuyển hướng đến trang van-chuyen.php nếu có tham số confirm_delivery
            if(isset($_GET['confirm_delivery']))
            {
            	echo "<script>location.href='van-chuyen.php';</script>";
            }

            // Chuyển hướng đến trang don-hang-thu-hoi.php nếu có tham số recalled
            if(isset($_GET['recalled']))
            {
            	echo "<script>location.href='don-hang-thu-hoi.php';</script>";
            }
        }
	}        
?>

