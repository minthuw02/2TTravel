<?php 
	
	session_start();

	// include db
	require_once('admin/pages/modules/config.php');

	// Kiểm tra nếu session 'customer' và 'level' tồn tại
	if(isset($_SESSION['customer']) && isset($_SESSION['level']))
	{
		$email = $_SESSION['customer'];
		// Lấy id của khách hàng
		$customer = "SELECT id_acc FROM account WHERE email = '$email'";
		$rs_customer = mysqli_query($conn, $customer);
		$row_customer = mysqli_fetch_array($rs_customer);

		// Thêm vào giỏ hàng
		if(isset($_POST['add_cart']))
		{
			$sku_product = $_POST['sku_product'];
			$id_acc = $row_customer['id_acc'];

			// Kiểm tra xem người dùng và sản phẩm đã tồn tại trong giỏ hàng chưa
			// Nếu tồn tại, tăng số lượng sản phẩm lên 1, ngược lại thêm bản ghi mới
			$exist = "SELECT id_customer, sku_product FROM cart WHERE id_customer = '$id_acc' AND sku_product = '$sku_product'";
			$rs_exist = mysqli_query($conn, $exist);
			$exist = mysqli_num_rows($rs_exist);
			
			if($exist > 0)
			{
				// Tăng số lượng sản phẩm

				// Lấy số lượng hiện tại của sản phẩm
				$product = "SELECT qty FROM product WHERE sku_product = '$sku_product'";
				$rs_product = mysqli_query($conn, $product);
				$row_product = mysqli_fetch_array($rs_product);
				$qty_product = $row_product['qty'];

				// Lấy số lượng hiện tại của giỏ hàng
				$qty = "SELECT id_cart, qty FROM cart WHERE id_customer = '$id_acc' AND sku_product = '$sku_product'";
				$rs_qty = mysqli_query($conn, $qty);
				$row_qty = mysqli_fetch_array($rs_qty);
				$qty_current = $row_qty['qty'];
				$inc_qty = $qty_current + 1;

				if($inc_qty >= $qty_product)
				{
					// Nếu số lượng tăng lớn hơn số lượng sản phẩm thì đặt số lượng sản phẩm tối đa
					$update = "UPDATE cart SET qty = $qty_product WHERE id_customer = '$id_acc' AND sku_product = '$sku_product'";
					mysqli_query($conn, $update);
					echo "<script>alert('Thêm vào giỏ hàng thành công');</script>";
					echo "<script>location.href='chi-tiet-san-pham.php?id=".$sku_product."';</script>";
				}
				else
				{
					// Nếu số lượng tăng nhỏ hơn số lượng sản phẩm thì đặt số lượng tăng
					$update = "UPDATE cart SET qty = $inc_qty WHERE id_customer = '$id_acc' AND sku_product = '$sku_product'";
					mysqli_query($conn, $update);
					echo "<script>alert('Thêm vào giỏ hàng thành công');</script>";
					echo "<script>location.href='chi-tiet-san-pham.php?id=".$sku_product."';</script>";
				}

			}
			else
			{
				// Thêm bản ghi mới
				// Thêm sản phẩm vào giỏ hàng
				$ins = "INSERT INTO cart(id_customer, sku_product, qty) VALUES('$id_acc', '$sku_product', 1)";
				mysqli_query($conn, $ins);
				echo "<script>alert('Thêm vào giỏ hàng thành công');</script>";
				echo "<script>location.href='chi-tiet-san-pham.php?id=".$sku_product."';</script>";
			}
			// Kết thúc kiểm tra tồn tại
		}
	}
	else
	{
		// Nếu không, chuyển hướng đến trang đăng nhập
		echo "<script>location.href='dang-nhap.html'</script>";
	}
?>
