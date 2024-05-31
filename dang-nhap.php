<?php 
	
	// Bao gồm các tệp header và top
	include('includes/header.php'); // Header của trang
	include('includes/top.php'); // Phần top của trang

	 // Xử lý đăng nhập
	 if(isset($_POST['login']))
	 {
		 $email = isset($_POST['email']) ? $_POST['email'] : ''; // Email được nhập từ form
		 $password = isset($_POST['password']) ? $_POST['password'] : ''; // Mật khẩu được nhập từ form
 
		 // Kiểm tra xem email và mật khẩu có được nhập không
		 if($email && $password)
		 {
			 $pass_md5 = md5($password); // Mã hóa mật khẩu
 
			 // Kiểm tra tài khoản tồn tại và cấp độ
			 $sql = "SELECT email, level FROM account WHERE email = '$email' AND password = '$pass_md5'";
			 $result = mysqli_query($conn, $sql);
 
			 // Kiểm tra kết quả truy vấn
			 if(mysqli_num_rows($result) > 0)
			 {
				 $row = mysqli_fetch_array($result);
				 $level = isset($row['level']) ? $row['level'] : ''; // Lấy cấp độ tài khoản
 
				 // Kiểm tra cấp độ tài khoản
				 if($level == 1)
				 {
					echo "<script>location.href='http://localhost/Shop/admin/pages/dang-nhap.php';</script>"; // Chuyển hướng đến trang đăng nhập của admin
					 exit; // Ngăn chặn mã PHP tiếp tục chạy
				 }
				 else if($level == 2)
				 {
					 $_SESSION['customer'] = $email; // Lưu email vào phiên làm việc
					 $_SESSION['level'] = $level; // Lưu cấp độ tài khoản vào phiên làm việc
					 echo "<script>location.href='trang-chu.html';</script>"; // Chuyển hướng về trang chủ
					 exit; // Ngăn chặn mã PHP tiếp tục chạy
				 }
			 }
			 else
			 {
				 echo "<script>alert('Tài khoản không tồn tại!.');</script>"; // Thông báo nếu tài khoản không tồn tại
			 }
		 }
		 else
		 {
			 echo "<script>alert('Vui lòng nhập đủ thông tin tài khoản!.');</script>"; // Thông báo nếu không nhập đủ thông tin tài khoản
		 }
	 }

?>
		<div id="content">
			<!-- top slider không có -->
			<div class="breadcrumb_me">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<ul class="breadcrumb_list">
								<li><a href="trang-chu.html">Trang chủ</a></li>
								<li><i class="fas fa-caret-right"></i></li>
								<li>Đăng nhập tài khoản</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="login_content">
				<div class="container">
					<h1 class="title_head" style="color: #339AF0;"><span> ĐĂNG NHẬP TÀI KHOẢN </span></h1>
					<div class="row">
						<div class="col-lg-6">
							<div class="login_form">
								<span>Nếu đã có tài khoản, đăng nhập tại đây.</span>
								<form class="pt-5" action="" method="POST">
								  	<div class="form-group">
								    	<label for="exampleInputEmail1">Email* </label>
								    	<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Nhập email của bạn" name="email" value="<?php if(isset($email)){ echo $email; } ?>">
								  	</div>
								  	<div class="form-group">
								    	<label for="exampleInputPassword1">Mật khẩu* </label>
								    	<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Nhập mật khẩu" name="password">
								  	</div>
								  	<button type="submit" class="btn btn-primary" name="login">Đăng nhập</button>
								  	<a href="dang-ky.html" class="btn-link-style btn-register" style="margin-left: 20px;text-decoration: underline; ">Đăng ký</a>
								</form>
							</div>
						</div>
						<!--
						<div class="col-lg-6">
							<div class="login_form">
								<span>Bạn quên mật khẩu? Nhập địa chỉ email để lấy lại mật khẩu qua email.</span>
								<form class="pt-5">
								  	<div class="form-group">
								    	<label for="exampleInputEmail1">Email* </label>
								    	<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Nhập email của bạn">
								  	</div>
								  	<button type="submit" class="btn btn-primary">Lấy lại mật khẩu</button>
								</form>
							</div>
						</div>
						-->
					</div>
					<!-- kết thúc dòng -->
				</div>
				<!-- container -->
			</div>
			<!-- nội dung giữa -->
			<!-- nội dung dưới cùng -->
		</div>
		<!-- nội dung -->
<?php 

	// Bao gồm tệp footer
	include('includes/footer.php');
?>