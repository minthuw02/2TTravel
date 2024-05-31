<?php 
	
	// Bao gồm các tệp cần thiết
	include('includes/header.php');
	include('includes/top.php');

	// Kiểm tra xem phiên làm việc và cấp độ người dùng đã được xác định chưa
	if(isset($customer) && isset($level))
	{
		// Lấy dữ liệu của khách hàng
		$customer_query = "SELECT id_acc FROM account WHERE email ='$customer'";
		$rs_customer = mysqli_query($conn, $customer_query);
		$row_customer = mysqli_fetch_array($rs_customer);
		$id_acc = $row_customer['id_acc'];
	}
?>
		<div id="content">
			<!-- breadcrumb -->
			<div class="breadcrumb_me">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<ul class="breadcrumb_list">
								<li><a href="trang-chu.html">Trang chủ</a></li>
								<li><i class="fas fa-caret-right"></i></li>
								<li>Giỏ hàng của bạn</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- middle content -->
			<div class="mid_content">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-pd-0">
	<?php 
		// Kiểm tra xem người dùng đã đăng nhập chưa
		if(!isset($customer) && !isset($level))
		{
	?>
							<!-- Thông báo khi chưa đăng nhập -->
							<div class="cart_login">
								<div class="title_cart">
									<h3>CHƯA ĐĂNG NHẬP TÀI KHOẢN</h3>
									<span>Vui lòng <a href="dang-nhap.html"> đăng nhập </a> để mua hàng</span>
								</div>
							</div>
	<?php 
		}
		else
		{
			// Kiểm tra giỏ hàng có sản phẩm không
			$cart_query = "SELECT id_customer FROM cart WHERE id_customer = '$id_acc'";
			$rs_cart = mysqli_query($conn, $cart_query);

			if(mysqli_num_rows($rs_cart) == 0)
			{
	?>
							<!-- Thông báo khi giỏ hàng trống -->
							<div class="cart_login">
								<div class="title_cart">
									<h3>GIỎ HÀNG CỦA BẠN</h3>
									<span>(Chưa có sản phẩm nào) nhấn vào <a href="trang-chu.html"> Cửa hàng </a> để mua hàng</span>
								</div>
							</div>
		<?php 
			}
			else
			{
		?>
							<!-- Hiển thị giỏ hàng -->
							<div class="cart_panel sm_hidden">
								<div class="title_cart">
									<h3>GIỎ HÀNG CỦA BẠN 
										<span> 
										<?php 
											// Đếm số lượng sản phẩm trong giỏ hàng
											$qty_query = "SELECT sum(qty) as tong FROM cart WHERE id_customer = '$id_acc'";
											$rs_qty = mysqli_query($conn, $qty_query);
											$row_qty = mysqli_fetch_array($rs_qty);
											echo "( ".$row_qty['tong']." sản phẩm )";
										?>
										</span>
									</h3>
								</div>
								<!-- Bảng giỏ hàng -->
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

			// Hiển thị dữ liệu trong giỏ hàng
			$datacart_query = "SELECT id_cart, image, name_product, price, c.qty as qty_cart FROM cart c, product p WHERE c.sku_product = p.sku_product AND id_customer = '$id_acc'";
			$rs_datacart = mysqli_query($conn, $datacart_query);
			$total_money = 0;
			while ($row_datacart = mysqli_fetch_array($rs_datacart)) 
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
													<a href="delete-cart.php?id_cart=<?php echo $row_datacart['id_cart']; ?>" class="delete_cart">
														<i class="far fa-trash-alt"></i> 
														Xóa sản phẩm
													</a>
												</td>
												<td class="text-center">
													<?php echo number_format($row_datacart['price']); ?>đ
												</td>
												<td class="text-center">
													<form action="update-qty.php" method="POST">
														<div class="form-group">
															<input type="hidden" name="id_cart" value="<?php echo $row_datacart['id_cart']; ?>">
		                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="qty" value="<?php echo $row_datacart['qty_cart']; ?>" style="float: left; width: 50%; margin: 0 auto; vertical-align: middle;">
		                                                    <button type="submit" class="btn btn-outline-info" name="update_qty">
		                                                    	<i class="far fa-save"></i>
		                                                	</button>
		                                                </div>
	                                                </form>
												</td>
												<td class="text-center">
													<?php echo number_format($money); ?>đ
												</td>
											<tr>
		<?php 
			}
			// Kết thúc hiển thị dữ liệu giỏ hàng
		?>									
										</tbody>
									</table>
									<!-- Tổng tiền -->
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
									<!-- Nút thanh toán -->
									<ul class="checkout">
										<li>
											<a href="trang-chu.html">
												<button class="btn btn-white f-left" title="Tiếp tục mua hàng" type="submit"><span>Tiếp tục mua hàng</span></button>
											</a>
											<a href="thanh-toan.html">
												<button class="btn btn-primary button btn-proceed-checkout" title="Xác nhận đơn hàng"><span>Xác nhận đơn hàng</span></button>
											</a>
										</li>
									</ul>
									<!-- Kết thúc nút thanh toán -->
								</div>
								<!-- Kết thúc bảng giỏ hàng -->
							</div>
							<!-- Kết thúc panel giỏ hàng -->

	<?php 
			}
			// Kết thúc kiểm tra giỏ hàng có sản phẩm không
		}
		// Kết thúc kiểm tra phiên làm việc
	?>
						</div>
						<!-- col-lg-12 -->
					</div>
					<!-- Kết thúc row -->
				</div>
				<!-- Kết thúc container -->
			</div>	
			
	<?php 

		// Kiểm tra phiên làm việc
		if(isset($customer) && isset($level))
		{
			// Kiểm tra giỏ hàng có sản phẩm không
			$cart_query = "SELECT id_customer FROM cart WHERE id_customer = '$id_acc'";
			$rs_cart = mysqli_query($conn, $cart_query);

			if(mysqli_num_rows($rs_cart) != 0)
			{

	?>
			<!-- Hiển thị giỏ hàng trên di động -->
			<div class="cart_mobile_page lg_hidden">
		<?php 

			// Hiển thị dữ liệu giỏ hàng trên di động
			$datacart_query_mb = "SELECT id_cart, image, name_product, price, c.qty as qty_cart FROM cart c, product p WHERE c.sku_product = p.sku_product AND id_customer = '$id_acc'";
			$rs_datacart_mb = mysqli_query($conn, $datacart_query_mb);
			$total_money_mb = 0;
			while ($row_datacart_mb = mysqli_fetch_array($rs_datacart_mb)) 
			{
				$money_mb = $row_datacart_mb['qty_cart'] * $row_datacart_mb['price'];
				$total_money_mb += $money_mb;
		?>
				<div class="cart_mobile_item">
					<div class="delete_cart_icon">
						<a href="delete-cart.php?id_cart=<?php echo $row_datacart_mb['id_cart']; ?>"><i class="far fa-trash-alt"></i></a>
					</div>
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
				<!-- Kết thúc mục giỏ hàng di động -->
		<?php 
			}
			// Kết thúc vòng lặp
		?>
				<!-- Hiển thị tổng tiền -->
				<div class="cost_total_mb">
					TỔNG TIỀN <span class="cost_right_mb">
						<?php echo number_format($total_money_mb); ?>đ
					</span>
				</div>
				<!-- Nút thanh toán -->
				<div class="btn_cart_mobile">
					<a href="thanh-toan.html">
						<button class="btn btn-primary button btn-proceed-checkout" title="Xác nhận đơn hàng"><span>Xác nhận đơn hàng</span></button>
					</a>
					<a href="trang-chu.html">
						<button class="btn btn-white f-left" title="Tiếp tục mua hàng" type="submit"><span>Tiếp tục mua hàng</span></button>
					</a>
				</div>
			</div>
			<!-- Kết thúc trang giỏ hàng di động -->
	<?php
			}
			// Kết thúc kiểm tra giỏ hàng có sản phẩm không
		}
		// Kết thúc kiểm tra phiên làm việc
	?>
		</div>
		<!-- Kết thúc nội dung -->
<?php 
	
	// Bao gồm phần footer
	include('includes/footer.php');
?>
