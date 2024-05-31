

<?php 
	
	// Kiểm tra xem nút tìm kiếm đã được nhấn chưa
	if(isset($_POST['tim_kiem']))
	{
		// Lấy từ khóa tìm kiếm từ biểu mẫu
		$key_word = $_POST['key_word'];
		
		// Tạo liên kết tới trang tìm kiếm với tham số từ khóa
		$link = "tim-kiem.php?key=".$key_word;
		
		// Chuyển hướng trình duyệt tới trang tìm kiếm với từ khóa
		echo "<script>location.href='".$link."';</script>";
	}
?>

