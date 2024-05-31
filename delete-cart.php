<?php 
	
	session_start(); // Bắt đầu phiên làm việc

	// Bao gồm tệp cấu hình cơ sở dữ liệu
	require_once('admin/pages/modules/config.php');

	// Kiểm tra xem phiên làm việc có tồn tại và cấp độ người dùng đã được xác định chưa
	if(isset($_SESSION['customer']) && isset($_SESSION['level']))
	{
		$email = $_SESSION['customer']; // Lấy email của người dùng từ phiên làm việc

		// Lấy ID của người dùng dựa trên email
		$customer = "SELECT id_acc FROM account WHERE email = '$email'";
		$rs_customer = mysqli_query($conn, $customer);
		$row_customer = mysqli_fetch_array($rs_customer);

		// Xử lý xóa sản phẩm khỏi giỏ hàng
		if(isset($_GET['id_cart']))
		{
			$id_cart = $_GET['id_cart']; // Lấy ID của sản phẩm trong giỏ hàng cần xóa
			$del = "DELETE FROM cart WHERE id_cart = $id_cart"; // Xây dựng truy vấn xóa sản phẩm
			mysqli_query($conn, $del); // Thực hiện truy vấn xóa
			echo "<script>location.href='gio-hang.php';</script>"; // Chuyển hướng người dùng đến trang giỏ hàng sau khi xóa
		}
	}
	else
	{
		echo "<script>location.href='index.php'</script>"; // Nếu phiên làm việc không tồn tại hoặc người dùng chưa đăng nhập, chuyển hướng về trang chính
	}
?>

