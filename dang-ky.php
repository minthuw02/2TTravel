<?php 
	// Bao gồm các tệp
	include('includes/header.php'); // Header của trang
	include('includes/top.php'); // Phần top của trang

	// Xử lý đăng ký tài khoản
	if(isset($_POST['register']))
	{
		// Thu thập dữ liệu từ form
		$name = $_POST['name']; // Tên người dùng
		$phone = $_POST['phone']; // Số điện thoại
		$address = $_POST['address']; // Địa chỉ
		$email = $_POST['email']; // Email
		$password = $_POST['password']; // Mật khẩu
		$repassword = $_POST['repassword']; // Nhập lại mật khẩu
		$date_create = date("Y-m-d H:i:s"); // Ngày tạo
		$image = "no-image.jpg"; // Ảnh đại diện mặc định
		$level = 2; // Cấp độ tài khoản mặc định

		// Kiểm tra dữ liệu được nhập từ form
		if($name && $phone && $address && $email && $password && $repassword)
		{
			$pass_md5 = md5($password); // Mã hóa mật khẩu
			$repass_md5 = md5($repassword); // Mã hóa lại mật khẩu nhập lại

			// Kiểm tra xem mật khẩu và mật khẩu nhập lại có trùng khớp không
			if($pass_md5 != $repass_md5)
			{
				echo "<script>alert('Mật khẩu không trùng khớp!.');</script>";
			}
			else
			{
				// Kiểm tra xem tài khoản đã tồn tại chưa
				$ac = "SELECT email FROM account WHERE email = '$email'";
				$rs_ac = mysqli_query($conn, $ac);
				if(mysqli_num_rows($rs_ac) > 0)
				{
					echo "<script>alert('Email này đã được sử dụng!. Vui lòng chọn email khác.');</script>";
				}
				else
				{
					// Thêm tài khoản vào cơ sở dữ liệu
					$ins = "INSERT INTO account(name, email, password, phone, address, image, date_create, level) VALUES('".$name."', '".$email."', '".$pass_md5."', '".$phone."', '".$address."', '".$image."', '".$date_create."', '".$level."')";
					mysqli_query($conn, $ins);

					// Lưu thông tin tài khoản vào phiên làm việc
					$_SESSION['customer'] = $email; // Email của người dùng
					$_SESSION['level'] = $level; // Cấp độ tài khoản
					echo "<script>alert('Đăng ký tài khoản thành công');</script>";
					echo "<script>location.href='trang-chu.html';</script>"; // Chuyển hướng về trang chủ
				}
			}
		}
		else
		{
			echo "<script>alert('Vui lòng điền đầy đủ thông tin!.');</script>";
		}
	}
?>

<!-- Phần header -->
<div id="content">
	<!-- Phần top slider (không có nội dung) -->
	<div class="breadcrumb_me">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<ul class="breadcrumb_list">
						<li><a href="trang-chu.html">Trang chủ</a></li>
						<li><i class="fas fa-caret-right"></i></li>
						<li>Đăng ký tài khoản</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<!-- Phần nội dung đăng ký tài khoản -->
	<div class="login_content">
		<div class="container">
			<h1 class="title_head"><span> ĐĂNG KÝ TÀI KHOẢN </span></h1>
			<span>Nếu bạn chưa có tài khoản, vui lòng đăng ký tại đây</span>
			<form method="POST" action="">
				<div class="row">
					<div class="col-lg-6 pad-top-5">
						<div class="login_form" style="padding-bottom: 0;">
						  	<div class="form-group">
						    	<label for="exampleInputEmail1">Họ tên* </label>
						    	<input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="name" value="<?php if(isset($name)){ echo $name; } ?>">
						  	</div>
						  	<div class="form-group">
						    	<label for="exampleInputPassword1">Số điện thoại*:</label>
						    	<input type="text" class="form-control" id="exampleInputPassword1" name="phone" value="<?php if(isset($phone)){ echo $phone; } ?>">
						  	</div>
						  	<div class="form-group">
						    	<label for="exampleInputPassword1">Địa chỉ (Nhận hàng)*:</label>
						    	<textarea class="form-control" name="address"><?php if(isset($address)){ echo $address; } ?></textarea>
						  	</div>
						</div>
					</div>
					<div class="col-lg-6 pad-top-5">
						<div class="login_form" style="padding-bottom: 0;">
						  	<div class="form-group">
						    	<label for="exampleInputEmail1">Email*: </label>
						    	<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" value="<?php if(isset($email)){ echo $email; } ?>">
						  	</div>
						  	<div class="form-group">
						    	<label for="exampleInputPassword1">Mật khẩu:* </label>
						    	<input type="password" class="form-control" id="exampleInputPassword1" name="password">
						  	</div>
						  	<div class="form-group">
						    	<label for="exampleInputPassword1">Nhập lại mật khẩu:* </label>
						    	<input type="password" class="form-control" id="exampleInputPassword1" name="repassword">
						  	</div>
						</div>
					</div>
					<div class="col-lg-12">
						<button type="submit" class="btn btn-primary" name="register">Đăng ký</button>
			  			<a href="dang-nhap.html" class="btn-link-style btn-register" style="margin-left: 20px;text-decoration: underline; ">Đăng nhập</a>
			  		</div>
				</div>
				<!-- kết thúc dòng -->
			</form>
		</div>
		<!-- container -->
	</div>
	<!-- nội dung giữa -->
	<!-- nội dung dưới cùng -->
</div>
<!-- nội dung -->
<?php 
	// Phần footer
	include('includes/footer.php');
?>
