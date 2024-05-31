<?php 
	
	// Bao gồm phần header
	include('includes/header.php');
	// Bao gồm phần top
	include('includes/top.php');
	// Bao gồm phần nội dung top
	include('includes/top-content.php');
	
?>
		<div class="mid_content">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 padding-15">
						<!-- Tiêu đề danh mục sản phẩm nổi bật -->
						<div class="title_module_main">
							<h2>
								<a href="san-pham.php?id=7" title="Sản phẩm mới">Sản phẩm nổi bật</a>
							</h2>
						</div>
						<!-- Sản phẩm nổi bật -->
						<div class="product_main">
							<div class="owl-carousel owl-product">
								<?php 
                                    // Truy vấn lấy sản phẩm nổi bật
                                    $product = "SELECT sku_product, p.image as image, name_product, price, p.slug as slug, slug_type FROM product p, type_product tp, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND flag = 1 AND highlight = 1 ORDER BY date_upload DESC";
                                    $rs_product = mysqli_query($conn, $product);
                                    while ($row_product = mysqli_fetch_array($rs_product)) 
                                    {
                                ?>
                                    <div class="item">
                                        <!-- Box sản phẩm -->
                                        <div class="product_box">
                                            <!-- Hình ảnh sản phẩm -->
                                            <div class="product_image">
                                                <img src="admin/pages/public/images/products/<?php echo $row_product['image']; ?>" alt="<?php echo $row_product['image']; ?>" title="<?php echo $row_product['name_product']; ?>" width="100%">
                                            </div>
                                            <!-- Chi tiết sản phẩm -->
                                            <div class="detail_product">
                                                <!-- Tên sản phẩm -->
                                                <h2 class="product_name">
                                                    <a href="san-pham/<?php echo $row_product['slug_type']; ?>/<?php echo $row_product['slug']; ?>-<?php echo $row_product['sku_product']; ?>.html">
                                                        <?php 
                                                            echo mb_substr($row_product['name_product'], 0, 50, 'UTF-8')."..."; 
                                                        ?>
                                                    </a>
                                                </h2>
                                                <!-- Giá sản phẩm -->
                                                <span class="price_box">
                                                    <h4 class="price"><?php echo number_format($row_product['price']); ?>đ</h4>
                                                    <!-- Link chi tiết -->
                                                    <a href="san-pham/<?php echo $row_product['slug_type']; ?>/<?php echo $row_product['slug']; ?>-<?php echo $row_product['sku_product']; ?>.html">Chi tiết</a>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Kết thúc box sản phẩm -->
                                    </div>
                                    <!-- Kết thúc item -->
                                <?php 
                                    }
                                ?>
							</div>
						</div>
						<!-- Kết thúc sản phẩm nổi bật -->
					</div>
					<!-- Kết thúc col-lg-12 -->
					<!-- Hàng quảng cáo -->
					<!--
					<div class="col-lg-12">
						<div class="row">
							<div class="col-lg-6 col-sm-12">
								<div class="box">
									<div class="figure">
										<a href="#">
											<img src="public/images/sale1.jpg" alt="sale1.jpg">
										</a>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 sm_padding_15">
								<div class="box">
									<div class="figure">
										<a href="#">
											<img src="public/images/sale2.jpg" alt="sale2.jpg">
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					-->
					<!-- Kết thúc hàng quảng cáo -->
					<div class="col-lg-12 padding-30">
						<!-- Tiêu đề hàng mới về -->
						<div class="title_module_main">
							<h2>
								<a href="#" title="Sản phẩm mới">Hàng mới về</a>
							</h2>
						</div>
						<!-- Sản phẩm mới về -->
						<div class="product_main">
							<div class="row">
								<?php 
                                    // Truy vấn lấy sản phẩm mới về
                                    $product2 = "SELECT sku_product, p.image as image, name_product, price, p.slug as slug, slug_type FROM product p, type_product tp, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND flag = 1 ORDER BY date_upload DESC LIMIT 16";
                                    $rs_product2 = mysqli_query($conn, $product2);
                                    while ($row_product2 = mysqli_fetch_array($rs_product2)) 
                                    {
                                ?>
                                    <div class="col-lg-3 col-6 pt-2 pb-2">
                                        <!-- Box sản phẩm -->
                                        <div class="product_box">
                                            <!-- Hình ảnh sản phẩm -->
                                            <div class="product_image">
                                                <img src="admin/pages/public/images/products/<?php echo $row_product2['image']; ?>" alt="<?php echo $row_product2['image']; ?>" title="<?php echo $row_product2['name_product']; ?>" width="100%">
                                            </div>
                                            <!-- Chi tiết sản phẩm -->
                                            <div class="detail_product">
                                                <!-- Tên sản phẩm -->
                                                <h2 class="product_name">
                                                    <a href="san-pham/<?php echo $row_product2['slug_type']; ?>/<?php echo $row_product2['slug']; ?>-<?php echo $row_product2['sku_product']; ?>.html">
                                                        <?php 
                                                            echo mb_substr($row_product2['name_product'], 0, 50, 'UTF-8')."...";
                                                        ?>
                                                    </a>
                                                </h2>
                                                <!-- Giá sản phẩm -->
                                                <span class="price_box">
                                                    <h4 class="price"><?php echo number_format($row_product2['price']); ?>đ</h4>
                                                    <!-- Link chi tiết -->
                                                    <a href="san-pham/<?php echo $row_product2['slug_type']; ?>/<?php echo $row_product2['slug']; ?>-<?php echo $row_product2['sku_product']; ?>.html">Chi tiết</a>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Kết thúc box sản phẩm -->
                                    </div>
                                <?php 
                                    }
                                ?>
							</div>
						</div>
						<!-- Kết thúc sản phẩm mới về -->
					</div>
					<!-- Kết thúc col-lg-12 -->
					<!-- Xem thêm sản phẩm -->
					<div class="col-lg-12">
						<div class="viewmore">
							<a href="loai-san-pham/San-pham-cua-nu-7-1.html" title="Xem thêm sản phẩm">Xem thêm sản phẩm</a>
						</div>
					</div>
					<!-- Kết thúc col-lg-12 -->
				</div>
				<!-- Kết thúc row -->
			</div>
			<!-- Kết thúc container -->
		</div>
		<!-- Kết thúc nội dung chính -->
		<!-- Nội dung phía dưới -->
		<div class="bot_content">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="object_post">
							<!-- Tiêu đề -->
							<div class="title_text text-center">
								<h2>TIN TỨC VÀ KHUYẾN MÃI</h2>
							</div>
							<!-- Kết thúc tiêu đề -->
						</div>
						<!-- Kết thúc object_post -->
					</div>
					<!-- Kết thúc col-lg-12 -->
					<div class="col-lg-12" style="background: #fff;">
						<!-- Danh sách tin tức -->
						<div class="owl-carousel owl-blog">
							<?php 
                                // Truy vấn lấy danh sách tin tức
                                $blog = "SELECT id_blog, b.image as image, title, date_upload, b.slug as slug, slug_type, typename, author, summary, name FROM blog b, type_blog tb, account a WHERE b.id_type = tb.id_type AND a.id_acc = b.author AND flag = 1 and highlight = 1 ORDER BY date_upload DESC LIMIT 6";
                                $rs_blog = mysqli_query($conn, $blog);
                                while ($row_blog = mysqli_fetch_array($rs_blog)) 
                                {
                            ?>
								<!-- Mỗi tin tức -->
								<div class="item">
									<!-- Box tin tức -->
									<div class="blog_index">
										<!-- Hình ảnh -->
										<div class="image_blog">
											<a href="blog/<?php echo $row_blog['slug_type'] ?>/<?php echo $row_blog['slug']; ?>-<?php echo $row_blog['id_blog']; ?>.html">
												<img src="admin/pages/public/images/blogs/<?php echo $row_blog['image']; ?>" alt="<?php echo $row_blog['image']; ?>" title="<?php echo $row_blog['title']; ?>">
											</a>
										</div>
										<!-- Kết thúc hình ảnh -->
										<!-- Nội dung tin tức -->
										<div class="content_blog">
											<div class="content_left">
												<span class="top_content_blog">
													<?php 
														$day = date_create($row_blog['date_upload']);
														echo date_format($day, "d");
													?>
												</span>
												<span class="bot_content_blog">
													<?php 
														$month = date_create($row_blog['date_upload']);
														echo "Tháng " . date_format($month, "m");
													?>
												</span>
											</div>
											<!-- Kết thúc content_left -->
											<!-- Nội dung phải -->
											<div class="content_right">
												<span class="time_post">Đăng bởi:&nbsp; <?php echo $row_blog['name']; ?></span>
												<h3>
													<a href="blog/<?php echo $row_blog['slug_type'] ?>/<?php echo $row_blog['slug']; ?>-<?php echo $row_blog['id_blog']; ?>.html" title="<?php echo $row_blog['title']; ?>">
														<?php 
															echo mb_substr($row_blog['title'], 0, 50, 'UTF-8')."..."; 
														?>
													</a>
												</h3>
											</div>
											<!-- Kết thúc content_right -->
											<!-- Tóm tắt tin tức -->
											<div class="summary_item_blog">
												<p>
													<?php 
														echo mb_substr($row_blog['summary'], 0, 90, 'UTF-8')."..."; 
													?>
												</p>
											</div>
											<!-- Kết thúc tóm tắt -->
										</div>
										<!-- Kết thúc content blog -->
									</div>
									<!-- Kết thúc blog index -->
								</div>
								<!-- Kết thúc item -->
							<?php 
                                }
                            ?>
						</div>
						<!-- Kết thúc col-lg-12 -->
						<div class="col-lg-12">
							<!-- Xem thêm tin tức -->
							<div class="viewmore" style="margin-top: 20px;">
								<a href="loai-blog/Tin-tuc-moi-2-1.html" title="Xem thêm sản phẩm">Xem thêm tin tức</a>
							</div>
						</div>
						<!-- Kết thúc col-lg-12 -->
					</div>
					<!-- Kết thúc row -->
				</div>
				<!-- Kết thúc container -->
			</div>
			<!-- Kết thúc nội dung phía dưới -->
		</div>
		<!-- Kết thúc content -->
<?php 
	
	// Bao gồm footer
	include('includes/footer.php');
?>

