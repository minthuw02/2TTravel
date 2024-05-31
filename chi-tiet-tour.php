<?php 
	
	// Bao gồm các tệp header và top
	include('includes/header.php');
	include('includes/top.php');

	// Hiển thị dữ liệu sản phẩm nếu có
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		// Truy vấn dữ liệu sản phẩm từ cơ sở dữ liệu
		$data_query = "SELECT sku_tour, tp.id_type as id_type, typename, p.image as image, name_tour, date_upload, p.slug as slug, slug_type, typename, author, summary, content, price, view, qty FROM tour t, type_tour tt, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND flag = 1 AND sku_tour = '$id'";
		$rs_data = mysqli_query($conn, $data_query);
		$row_data = mysqli_fetch_array($rs_data);

		// Tăng lượt xem cho sản phẩm
		$view_count = $row_data['view'] + 1;
		$update_view_query = "UPDATE tour SET view = $view_count WHERE sku_tour = '$id'";
		mysqli_query($conn, $update_view_query);
	}

?>
		<div id="content">
			<!-- Phần breadcrumb -->
			<div class="breadcrumb_me">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<ul class="breadcrumb_list">
								<li><a href="trang-chu.html"> Trang chủ </a></li>
								<li><i class="fas fa-caret-right"></i></li>
								<!-- Liên kết đến loại sản phẩm -->
								<li><a href="loai-san-pham/<?php echo $row_data['slug_type']; ?>-<?php echo $row_data['id_type']; ?>-1.html"><?php echo $row_data['typename']; ?></a></li>
								<li><i class="fas fa-caret-right"></i></li>
								<!-- Tên sản phẩm -->
								<li><?php echo $row_data['name_tour']; ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="mid_content">
				<div class="main_detail">
					<div class="container padding-heading">
						<div class="row">
							<!-- Phần quảng cáo (nếu có) -->
							<div class="col-lg-9">
								<div class="row">
									<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
										<div class="col_large_default large_image image_thumb">
											<!-- Hiển thị hình ảnh sản phẩm lớn -->
											<img src="admin/pages/public/images/tours/<?php echo $row_data['image']; ?>" id="img-container">
										</div>
										<!-- Thumbs hình ảnh sản phẩm -->
										<div class="tour_detail_thumb">
											<div class="owl-carousel owl-tour-thumb">
												<div class="item image_thumb">
													<!-- Thay đổi hình ảnh khi bấm vào thumbnail -->
													<img onclick="change_img(this)" src="admin/pages/public/images/tours/<?php echo $row_data['image']; ?>">
												</div>
					<?php

						$image_query = "SELECT name_image FROM image WHERE sku_tour = '$id'";
						$rs_image = mysqli_query($conn, $image_query);
						while ($row_image = mysqli_fetch_array($rs_image)) 
						{
					?>
												<div class="item image_thumb">
													<img  onclick="change_img(this)" src="admin/pages/public/images/tours/<?php echo $row_image['name_image']; ?>">
												</div>
					<?php 
						}
						// Kết thúc vòng lặp
					?>
											</div>
										</div>
									</div>
									<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
										<!-- Tên sản phẩm -->
										<h1 class="title_tour"><?php echo $row_data['name_tour']; ?></h1>
										<div class="group_status">
											<!-- Số lượt xem -->
											<span class="first_status"><i class="fas fa-eye"></i> Lượt xem:
												<span class="status_name">
													<?php echo $row_data['view']; ?>
												</span>
											</span>
											<!-- Số lượng sản phẩm còn lại -->
											<span class="first_status">
												<span class="space">&nbsp; | &nbsp;</span>
												<i class="fas fa-warehouse"></i> Kho:
												<span class="status_name availabel">
													<?php
														if($row_data['qty'] > 0)
														{
															echo $row_data['qty']; 
														}
														else
														{
															echo "Hết hàng";
														}
													?>
												</span>
											</span>
										</div>
										<!-- Thông tin đánh giá -->
										<div class="reviews_details_tour">
											<div class="tour_reviews_star">
												<a href="#"><i class="fas fa-star"></i></a>
												<a href="#"><i class="fas fa-star"></i></a>
												<a href="#"><i class="fas fa-star"></i></a>
												<a href="#"><i class="far fa-star"></i></a>
												<a href="#"><i class="far fa-star"></i></a>
											</div>
										</div>
										<!-- Thông tin giá -->
										<div class="price_box">
											<span class="price"> Giá: 
												<span class="special_price">
													<?php echo number_format($row_data['price']); ?>đ 
												</span> 
											</span>
										</div>
										<!-- Tóm tắt sản phẩm -->
										<div class="tour_summary tour_description">
											<div class="rte description">
												<p>
													<?php echo $row_data['summary']; ?>
												</p>
											</div>
											<a href="san-pham/<?php echo $row_data['slug_type']; ?>/<?php echo $row_data['slug']; ?>-<?php echo $row_data['sku_tour']; ?>.html#view-more" title="Xem tiếp" class="see_detail">[Xem tiếp]</a>
										</div>
										<!-- Nút mua hàng -->
										<div class="btn_buy_cart">
											<?php
												if($row_data['qty'] > 0)
												{
											?>
													<form action="add-cart.php" method="POST">
														<input type="hidden" name="sku_tour" value="<?php echo $row_data['sku_tour']; ?>">
														<button type="submit" class="btn btn-lg  btn-cart button_cart_buy_enable add_to_cart btn_buy" title="Thêm vào giỏ hàng" name="add_cart"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng</button>
													</form>
											<?php
												}
												else
												{
													echo "<button type='submit' class='btn btn-lg  btn-cart button_cart_buy_enable add_to_cart btn_buy' disabled><i class='fas fa-shopping-cart'></i> Thêm vào giỏ hàng</button>";
												}
											?>
											
										</div>
										<!-- Thông tin liên hệ -->
										<div class="call_now">
											<p style="color:#d7102c;font-weight:bold;">Gọi ngay : <a style="color:#d7102c" href="tel:(024)98567868">(039) 2738824</a> để có được giá tốt nhất!</p>
										</div>
									</div>
									<!-- col-lg-7 -->
									<div class="col-xs-12 col-lg-12 col-sm-12 col-md-12 no-padding">
										<!-- Tab sản phẩm -->
										<div class="tour-tab e-tabs" id="app-tabs">
											<ul class="tabs tabs-title clearfix">
												<!-- Tab mô tả -->
												<li class="tab-link selection" id="selecttab1">
													<h3 id="view-more">
														<span id="tab1btn" @click="tab1btn">Mô tả</span>
													</h3>
												</li>
												
											</ul>
											<!-- Nội dung các tab -->
											<div class="tab tab-1 tab_content" v-if="tab1">
												<?php echo $row_data['content']; ?>
											</div>
											<div class="tab tab-2 tab_content" v-if="tab2">
												<h6> Các nội dung hướng dẫn mua hàng viết ở đây </h6>
											</div>
											<div class="tab tab-3 tab_content" v-if="tab3">
												<div class="rte">
													<div id="huy_tour_reviews">
														<div class="title_bl">
															Đánh giá sản phẩm
														</div>
														<div class="tour-reviews-summary-actions">
															<input type="button" id="btnnewreview" value="Viết đánh giá">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- col-lg-12 -->
								</div>
								<!-- row -->
							</div>
							<!-- col-lg-9 -->
							<!-- Phần sản phẩm liên quan -->
							<div class="col-lg-3">
								<div class="wrap_module_service padding-15">
									<div class="item_service">
										<div class="wrap_item">
											<div class="image_service">
												<img src="public/images/tourdetail-icon5.png">
											</div>
											<div class="content_service">
												<p>Giao hàng nhanh chóng</p>
												<span>Chỉ trong vòng 24h đồng hồ</span>
											</div>
										</div>
									</div>
									<div class="item_service">
										<div class="wrap_item">
											<div class="image_service">
												<img src="public/images/tourdetail-icon4.png">
											</div>
											<div class="content_service">
												<p>Sản phẩm chính hãng</p>
												<span>Sản phẩm nhập khẩu 100%</span>
											</div>
										</div>
									</div>
									<div class="item_service">
										<div class="wrap_item">
											<div class="image_service">
												<img src="public/images/tourdetail-icon3.png">
											</div>
											<div class="content_service">
												<p>Đổi trả cực kì dễ dàng</p>
												<span>Đổi trả trong 2 ngày đầu tiên</span>
											</div>
										</div>
									</div>
									<div class="item_service">
										<div class="wrap_item">
											<div class="image_service">
												<img src="public/images/tourdetail-icon1.png">
											</div>
											<div class="content_service">
												<p>Mua hàng tiết kiệm</p>
												<span>Tiết kiệm từ 10% - 30%</span>
											</div>
										</div>
									</div>
								</div>
								<!-- wrap_module_service -->
								<!-- Sản phẩm có thể bạn thích -->
								<div class="blog_aside padding-15">
						    		<h2 class="title_head">
						    			<span> Có thể bạn thích </span>
						    		</h2>
	<?php 

		$id_type = $row_data['id_type'];

		$tour_rela_query = "SELECT sku_tour, typename, p.image as image, name_tour, date_upload, p.slug as slug, slug_type, typename, author, summary, content, price FROM tour t, type_tour tt, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND flag = 1  AND p.id_type = $id_type AND sku_tour <> '$id'";
		$rs_tour_rela = mysqli_query($conn, $tour_rela_query);
		while($row_tour_rela = mysqli_fetch_array($rs_tour_rela))	{
	?>
						    		<div class="tour_item_small">
						    			<div class="left_item">
						    				<a href="san-pham/<?php echo $row_tour_rela['slug_type']; ?>/<?php echo $row_tour_rela['slug']; ?>-<?php echo $row_tour_rela['sku_tour']; ?>.html">
						    					<img src="admin/pages/public/images/tours/<?php echo $row_tour_rela['image']; ?>" alt="<?php echo $row_tour_rela['image']; ?>" title="<?php echo $row_tour_rela['name_tour']; ?>">
						    				</a>
						    			</div>
						    			<div class="tour_info">
						    				<h3><a href="san-pham/<?php echo $row_tour_rela['slug_type']; ?>/<?php echo $row_tour_rela['slug']; ?>-<?php echo $row_tour_rela['sku_tour']; ?>.html" title="<?php echo $row_tour_rela['name_tour']; ?>"><?php echo mb_substr($row_tour_rela['name_tour'], 0, 35, 'UTF-8')."..."; ?></a></h3>
						    				<div class="price_box_mini">
						    					<span><?php echo number_format($row_tour_rela['price']); ?>đ</span>
						    				</div>
						    			</div>
						    		</div>
	<?php 
		}
		// Kết thúc vòng lặp
	?>
						    	</div>
						    	<!-- tour aside -->
							</div>
							<!-- col-lg-3 -->
						</div>
						<!-- row -->
					</div>
					<!-- container -->
				</div>
				<!-- main detail -->
				<!-- Sản phẩm cùng loại -->
				<div class="relative_box">
					<div class="container padding-heading">
						<div class="row">
							<div class="col-lg-12">
								<h2 class="title_rela">Sản phẩm cùng loại</h2>
								<div class="owl-carousel owl-tour-rela">
	<?php 

		$id_type = $row_data['id_type'];

		$tour_rela_query = "SELECT sku_tour, typename, p.image as image, name_tour, date_upload, p.slug as slug, slug_type, typename, author, summary, content, price FROM tour p, type_tour tp, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND flag = 1  AND p.id_type = $id_type AND sku_tour <> '$id'";
		$rs_tour_rela = mysqli_query($conn, $tour_rela_query);
		while($row_tour_rela = mysqli_fetch_array($rs_tour_rela))	
		{
	?>	
									<div class="item">
										<div class="tour_box">
								    		<div class="tour_image">
								    			<img src="admin/pages/public/images/tours/<?php echo $row_tour_rela['image']; ?>" alt="<?php echo $row_tour_rela['image']; ?>" title="<?php echo $row_tour_rela['name_tour']; ?>" width="100%">
								    		</div>
								    		<div class="detail_tour">
								    			<h2 class="tour_name">
								    				<a href="san-pham/<?php echo $row_tour_rela['slug_type']; ?>/<?php echo $row_tour_rela['slug']; ?>-<?php echo $row_tour_rela['sku_tour']; ?>.html">
								    					<?php echo mb_substr($row_tour_rela['name_tour'], 0, 32, 'UTF-8')."..."; ?>
								    				</a>
								    				<span class="price_box  mt-2 mb-2">
									    				<h4 class="price"><?php echo number_format($row_tour_rela['price']); ?>đ</h4>
									    			</span>
								    			</h2>
								    			
								    		</div>
								    	</div>
								    	<!-- tour box -->
									</div>
									<!-- item -->
	<?php 
		}
		// Kết thúc vòng lặp
	?>							
								</div>
							</div>
							<!-- col-lg-12 -->
						</div>
						<!-- row -->
					</div>
					<!-- container -->
				</div>
				<!-- relative box -->
			</div>
			<!-- middle content -->
		</div>
		<!-- content -->
<?php 
	
	// Footer
	include('includes/footer.php');
?>
