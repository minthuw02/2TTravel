<?php

	// Bắt đầu một phiên làm việc
	session_start();

	// Include file cấu hình cơ sở dữ liệu
	require_once('config.php');

	// Kiểm tra xem người dùng đã đăng nhập và được cấp quyền hay không
	if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
    	// Lấy cấp độ người dùng và email từ session
    	$level = $_SESSION['level'];
        $users = $_SESSION['user'];

        // Lấy thông tin người dùng từ cơ sở dữ liệu dựa trên email
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];
   	} 

	// Kiểm tra và lấy giá trị từ tham số trong URL
	if(isset($_GET['id']) && isset($_GET['tbl']))
	{
		$id = $_GET['id'];
		$table = $_GET['tbl'];

		if($table == "blog")
		{
			// Lấy cờ và tiêu đề của bài viết từ cơ sở dữ liệu
			$sql = "SELECT flag, title FROM blog WHERE id_blog = $id";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($result);
			$flag = $row['flag'];
			$title = $row['title'];

			// Nếu cờ là 1, bài viết đã được duyệt trước đó
			if($flag == 1)
			{
				// Tạo chuỗi thông báo về việc tắt duyệt bài viết
                $text = "Đã tắt duyệt bài <b>". $title . "</b>";
                // Lấy thời gian hiện tại
                $time = date('Y-m-d H:i:s');
                // Thêm một bản ghi vào bảng lịch sử với thông tin vừa tạo
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Cập nhật cờ của bài viết thành 0 (chưa duyệt)
				$update = "UPDATE blog SET flag = 0 WHERE id_blog = $id";
				mysqli_query($conn, $update);
				// Chuyển hướng người dùng về trang quản lý blog
				echo "<script>location.href='../blog.php';</script>";
			}
			else
			{
                // Tạo chuỗi thông báo về việc duyệt bài viết
                $text = "Đã duyệt bài <b>". $title . "</b>";
                // Lấy thời gian hiện tại
                $time = date('Y-m-d H:i:s');
                // Thêm một bản ghi vào bảng lịch sử với thông tin vừa tạo
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Cập nhật cờ của bài viết thành 1 (đã duyệt)
				$update = "UPDATE blog SET flag = 1 WHERE id_blog = $id";
				mysqli_query($conn, $update);
				// Chuyển hướng người dùng về trang quản lý blog
				echo "<script>location.href='../blog.php';</script>";
			}
		}
		// Kết thúc xử lý bài viết

		if($table == "tour")
		{
			// Lấy cờ và tên tour từ cơ sở dữ liệu
			$sql = "SELECT flag, name_tour FROM tour WHERE sku_tour = '$id'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($result);
			$flag = $row['flag'];
			$name_tour = $row['name_tour'];

			// Nếu cờ là 1, tour đã được duyệt trước đó
			if($flag == 1)
			{
				// Tạo chuỗi thông báo về việc tắt duyệt tour
                $text = "Đã tắt duyệt tour <b>". $name_tour . "</b>";
                // Lấy thời gian hiện tại
                $time = date('Y-m-d H:i:s');
                // Thêm một bản ghi vào bảng lịch sử với thông tin vừa tạo
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Cập nhật cờ của tour thành 0 (chưa duyệt)
				$update = "UPDATE tour SET flag = 0 WHERE sku_tour = '$id'";
				mysqli_query($conn, $update);
				// Chuyển hướng người dùng về trang quản lý tour
				echo "<script>location.href='../tour.php';</script>";
			}
			else
			{
				// Tạo chuỗi thông báo về việc duyệt tour
                $text = " Đã duyệt tour <b>". $name_tour . "</b>";
                // Lấy thời gian hiện tại
                $time = date('Y-m-d H:i:s');
                // Thêm một bản ghi vào bảng lịch sử với thông tin vừa tạo
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Cập nhật cờ của tour thành 1 (đã duyệt)
				$update = "UPDATE tour SET flag = 1 WHERE sku_tour = '$id'";
				mysqli_query($conn, $update);
				// Chuyển hướng người dùng về trang quản lý tour
				echo "<script>location.href='../tour.php';</script>";
			}
		}
		 // Kết thúc xử lý tour

	}
	// Kết thúc kiểm tra và xử lý giá trị từ URL

?>
