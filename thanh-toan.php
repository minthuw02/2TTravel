<?php 
	
	// Include các file header và top
	include('includes/header.php');
	include('includes/top.php');

	// Kiểm tra session tồn tại
	if(isset($customer) && isset($level))
	{
		// Lấy thông tin của khách hàng
		$customer_query = "SELECT id_acc, name, phone, address FROM account WHERE email ='$customer'";
		$customer_result = mysqli_query($conn, $customer_query);
		$customer_row = mysqli_fetch_array($customer_result);
		$id_acc = $customer_row['id_acc'];
		$name_customer = $customer_row['name'];
		$phone_customer = $customer_row['phone'];
		$address_customer = $customer_row['address'];
	}
?>
		<div id="content">
			<!-- Breadcrumb -->
			<div class="breadcrumb_me">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<ul class="breadcrumb_list">
								<li><a href="trang-chu.html">Trang chủ</a></li>
								<li><i class="fas fa-caret-right"></i></li>
								<li><a href="gio-hang.html">Giỏ hàng của bạn</a></li>
								<li><i class="fas fa-caret-right"></i></li>
								<li>Xác nhận đơn hàng</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="mid_content">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-pd-0">
	<?php 
		// Kiểm tra session tồn tại
		if(!isset($customer) && !isset($level))
		{
	?>
							<div class="cart_login">
								<div class="title_cart">
									<h3>CHƯA ĐĂNG NHẬP TÀI KHOẢN</h3>
									<span>Vui lòng <a href="dang-nhap.php"> đăng nhập </a> để mua hàng</span>
								</div>
							</div>
							<!-- Thông báo đăng nhập -->
	<?php 
		}
		else
		{
			// Kiểm tra giỏ hàng có sản phẩm không
			$cart_query = "SELECT id_customer FROM cart WHERE id_customer = '$id_acc'";
			$cart_result = mysqli_query($conn, $cart_query);

			if(mysqli_num_rows($cart_result) == 0)
			{
	?>
							<div class="cart_login">
								<div class="title_cart">
									<h3>GIỎ HÀNG CỦA BẠN</h3>
									<span>(Chưa có sản phẩm nào) nhấn vào <a href="index.php"> Cửa hàng </a> để mua hàng</span>
								</div>
							</div>
		<?php 
			}
			else
			{
		?>
							<div class="cart_login">
								<div class="title_cart">
									<h3>THÔNG TIN ĐẶT HÀNG</h3>
									<div class="form-group">
										<span><i class="fas fa-user-alt"></i> <?php echo $name_customer; ?></span>
									</div>
									<div class="form-group">
										<span for="exampleInputEmail1"><i class="fas fa-phone"></i> <?php echo $phone_customer; ?></span>
									</div>
									<div class="form-group">
										<span for="exampleInputEmail1"><i class="fas fa-map-marked-alt"></i> <?php echo $address_customer; ?> (Mặc định)</span>
									</div>
									<form action="payment.php" method="POST">
										<div class="form-group">
										    <label for="exampleInputEmail1">Thay đổi thông tin nhận hàng tại đây (Họ tên, địa chỉ, số điện thoại,...):</label>
										    <textarea class="form-control" name="info_receive"><?php if(isset($info_receive)){ echo $info_receive; } ?></textarea>
										</div>
										<div class="form-group">
										    <label for="exampleInputPassword1">Thông tin sản phẩm (Màu, size, cân nặng,...):</label>
										    <textarea class="form-control" name="info_product"><?php if(isset($info_product)){ echo $info_product; } ?></textarea>
										</div>
										<button type="submit" class="btn btn-primary" name="payment_cart">Đặt hàng</button>
									</form>
								</div>
							</div>
							<!-- Thông tin đặt hàng -->
							<div class="cart_panel sm_hidden">
								<div class="title_cart">
									<h3>GIỎ HÀNG CỦA BẠN 
										<span> 
										<?php 
											$qty_query = "SELECT sum(qty) as tong FROM cart WHERE id_customer = '$id_acc'";
											$qty_result = mysqli_query($conn, $qty_query);
											$qty_row = mysqli_fetch_array($qty_result);
											echo "( ".$qty_row['tong']." sản phẩm )";
										?>
										</span>
									</h3>
								</div>
								<div class="cart_desktop_page">
									<table class="table_cart">
										<thead>
											<tr>
												<th width="15%">Ảnh sản phẩm</th>
												<th width="40%">Tên sản phẩm</th>
												<th width="15%">Đơn giá</th>
												<th width="15%">Số lượng</th>
												<th width="15%">Thành tiền</th>
											</tr>
										</thead>
										<tbody>
		<?php 

			// Hiển thị thông tin giỏ hàng
			$datacart_query = "SELECT id_cart, image, name_product, price, c.qty as qty_cart FROM cart c, product p WHERE c.sku_product = p.sku_product AND id_customer = '$id_acc'";
			$datacart_result = mysqli_query($conn, $datacart_query);
			$total_money = 0;
			while ($row_datacart = mysqli_fetch_array($datacart_result)) 
			{
				$money = $row_datacart['qty_cart'] * $row_datacart['price'];
				$total_money += $money;
		?>
											<tr>
												<td class="image_product_table">
													<img src="admin/pages/public/images/products/<?php echo $row_datacart['image']; ?>" alt="<?php echo $row_datacart['image']; ?>" title="<?php echo $row_datacart['name_product']; ?>">
												</td>
												<td>
													<?php echo $row_datacart['name_product']; ?>
												</td>
												<td class="text-center">
													<?php echo number_format($row_datacart['price']); ?>đ
												</td>
												<td class="text-center">
													<?php echo $row_datacart['qty_cart']; ?>
												</td>
												<td class="text-center">
													<?php echo number_format($money); ?>đ
												</td>
											<tr>
		<?php 
			}
			// Kết thúc hiển thị thông tin giỏ hàng
		?>									
										</tbody>
									</table>
									<!-- Bảng giỏ hàng trên desktop -->
									<table class="table_cart table_total">
										<thead>
											<tr>
												<td>Tạm tính:</td>
												<td class="total_price">
													<?php echo number_format($total_money); ?>vnđ
												</td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-right">Tổng tiền:</td>
												<td class="text-right total_price_second"><?php echo number_format($total_money); ?>vnđ</td>
											</tr>
										</tbody>
									</table>
									<div class="clearfix"></div>
								</div>
								<!-- Bảng giỏ hàng trên desktop -->
							</div>
							<!-- Bảng giỏ hàng -->

	<?php 
			}
			// Kết thúc kiểm tra sản phẩm trong giỏ hàng
		}
		// Kết thúc kiểm tra session
	?>
						</div>
						<!-- col-lg-12 -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<div class="cart_mobile_page lg_hidden">
	<?php 

		// Hiển thị thông tin giỏ hàng trên điện thoại di động
		$datacart_mb_query = "SELECT id_cart, image, name_product, price, c.qty as qty_cart FROM cart c, product p WHERE c.sku_product = p.sku_product AND id_customer = '$id_acc'";
		$datacart_mb_result = mysqli_query($conn, $datacart_mb_query);
		$total_money_mb = 0;
		while ($row_datacart_mb = mysqli_fetch_array($datacart_mb_result)) 
		{
			$money_mb = $row_datacart_mb['qty_cart'] * $row_datacart_mb['price'];
			$total_money_mb += $money_mb;
	?>
				<div class="cart_mobile_item">
					<div class="image_cart_mb">
						<img src="admin/pages/public/images/products/<?php echo $row_datacart_mb['image']; ?>">
					</div>
					<div class="title_cart_mb">
						<a href="#"><?php echo $row_datacart_mb['name_product']; ?></a>
					</div>
					<div class="form_cart_mb">
					 	<div class="cost_cart_mb">
							Số lượng: <?php echo number_format($row_datacart_mb['qty_cart']); ?>
						</div>
				   		<div class="cost_cart_mb">
							Giá: 
							<span> 
								<?php echo number_format($row_datacart_mb['price']); ?>đ 
							</span>
						</div>
					</div>
				</div>
				<!-- cart_mobile_item -->
	<?php 
		}
		// Kết thúc hiển thị thông tin giỏ hàng trên điện thoại di động
	?>
				<div class="cost_total_mb">
					TỔNG TIỀN <span class="cost_right_mb">
						<?php echo number_format($total_money_mb); ?>đ
					</span>
				</div>
			</div>
			<!-- cart_mobile_page -->
		</div>
		<!-- content -->
<?php 
	
	// Footer
	include('includes/footer.php');
?>

