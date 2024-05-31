<?php 
	
	session_start();

	// Include file cấu hình cơ sở dữ liệu
	require_once('admin/pages/modules/config.php');


	if(isset($_SESSION['customer']) && isset($_SESSION['level']))
	{
		$email = $_SESSION['customer'];
		// Lấy id của khách hàng
		$customer_query = "SELECT id_acc FROM account WHERE email = '$email'";
		$customer_result = mysqli_query($conn, $customer_query);
		$customer_row = mysqli_fetch_array($customer_result);


		// Cập nhật giỏ hàng
		if(isset($_POST['update_qty']))
		{
			$id_cart = $_POST['id_cart'];
			$qty = $_POST['qty'];

			// Lấy sku_product
			$p_query = "SELECT sku_product FROM cart WHERE id_cart = $id_cart";
			$p_result = mysqli_query($conn, $p_query);
			$p_row = mysqli_fetch_array($p_result);
			$sku_product = $p_row['sku_product'];

			// Thiết lập số lượng tối đa nếu số lượng yêu cầu lớn hơn
			$qty_product_query = "SELECT qty FROM product WHERE sku_product = '$sku_product'";
			$qty_product_result = mysqli_query($conn, $qty_product_query);
			$qty_product_row = mysqli_fetch_array($qty_product_result);
			$qty_current = $qty_product_row['qty'];

			if($qty >= $qty_current)
			{
				// Cập nhật số lượng trong giỏ hàng
				$update_query = "UPDATE cart SET qty = $qty_current WHERE id_cart = $id_cart";
				mysqli_query($conn, $update_query);
				echo "<script>location.href='gio-hang.php';</script>";
			}
			else
			{
				// Cập nhật số lượng trong giỏ hàng
				$update_query = "UPDATE cart SET qty = $qty WHERE id_cart = $id_cart";
				mysqli_query($conn, $update_query);
				echo "<script>location.href='gio-hang.html';</script>";
			}
		}
	}
	else
	{
		echo "<script>location.href='trang-chu.html'</script>";
	}
?>

