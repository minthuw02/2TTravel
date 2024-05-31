<?php 

	// Thêm cơ sở dữ liệu
    require_once('modules/config.php');
	
	// Kiểm tra xem có tham số id và id_product được truyền vào không
	if(isset($_GET['id']) && isset($_GET['id_product']))
	{
		// Lấy giá trị id và id_product từ tham số truyền vào
		$id = $_GET['id'];
		$id_product = $_GET['id_product'];
		
		// Đường dẫn lưu trữ ảnh sản phẩm
		$target_dir = "public/images/products/";

		// Xóa tệp ảnh cũ
		$image = "SELECT name_image FROM image WHERE id_image = $id";
		$rs_image = mysqli_query($conn, $image);
		$row_image = mysqli_fetch_array($rs_image);
		$old_image = $row_image['name_image'];
		$target_file = $target_dir . $old_image;

		// Kiểm tra và xóa tệp ảnh cũ nếu tồn tại và không phải là no-image.png
		if($old_image != "no-image.png")
		{
			unlink($target_file);
		}

		// Xóa bản ghi trong cơ sở dữ liệu
		$del = "DELETE FROM image WHERE id_image = $id";
		mysqli_query($conn, $del);
		
		// Thông báo xóa thành công
		echo "<script>alert('Xóa thành công');</script>";
		
		// Chuyển hướng đến trang ảnh sản phẩm với id_product tương ứng
		echo "<script>location.href='anh-san-pham.php?id=".$id_product."';</script>";
	
	}
?>

