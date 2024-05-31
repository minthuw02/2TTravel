<?php 
	
	// Bắt đầu phiên làm việc
	session_start();

	// Bao gồm cấu hình cơ sở dữ liệu
	require_once('admin/pages/modules/config.php');


	if(isset($_SESSION['customer']) && isset($_SESSION['level']))
	{
		$email = $_SESSION['customer'];
		// Lấy thông tin thanh toán của người dùng
		$user = "SELECT id_acc FROM account WHERE email = '$email'";
		$rs_user = mysqli_query($conn, $user);
		$row_user = mysqli_fetch_array($rs_user);
		$user_payment = $row_user['id_acc'];

		// Tạo mã hóa đơn
        $codeinvoice = "SELECT code_invoice FROM invoice ORDER BY code_invoice DESC";
        $rs_codeinvoice = mysqli_query($conn, $codeinvoice);
        $row_codeinvoice = mysqli_fetch_array($rs_codeinvoice);
        $code_invoice = $row_codeinvoice['code_invoice'];
        // Tăng mã
        $cut_num = substr($code_invoice, 3);
        $change_int = (int)$cut_num;
        $code_invoice_new = $change_int + 1;

        if($code_invoice_new < 10)
        {
            $invoice_code = "INV0" . $code_invoice_new;
        }
        else
        {
            $invoice_code = "INV" . $code_invoice_new;
        }

		if(isset($_POST['payment_cart']))
		{
			$info_receive = $_POST['info_receive'];
			$info_product = $_POST['info_product'];
			$order_date = date("Y-m-d H:i:s");

			// Thêm hóa đơn vào cơ sở dữ liệu
			$ins_inv = "INSERT INTO invoice(code_invoice, id_customer, info_receive, info_product, order_date, flag) VALUES('".$invoice_code."', '".$user_payment."', '".$info_receive."', '".$info_product."', '".$order_date."', 0)";
			mysqli_query($conn, $ins_inv);

			// Thêm giỏ hàng vào chi tiết hóa đơn
			$datacart = "SELECT sku_product, qty FROM cart WHERE id_customer = '$user_payment'";
			$rs_datacart = mysqli_query($conn, $datacart);
			while ($row_datacart = mysqli_fetch_array($rs_datacart)) 
			{
				$sku_product = $row_datacart['sku_product'];
				$qty = $row_datacart['qty'];

				// Thêm chi tiết hóa đơn
				$ins_detail_invoice = "INSERT INTO detail_invoice(code_invoice, sku_product, qty) VALUES('".$invoice_code."', '".$sku_product."', '".$qty."')";
				mysqli_query($conn, $ins_detail_invoice);
			}

			// Xóa giỏ hàng
			$delcart = "DELETE FROM cart WHERE id_customer = '$user_payment'";
			mysqli_query($conn, $delcart);
			echo "<script>alert('Thanh toán thành công');</script>";
			echo "<script>location.href='trang-chu.html';</script>";
		}
		// Kết thúc kiểm tra phương thức thanh toán
	}
	// Kết thúc kiểm tra phiên làm việc
?>

