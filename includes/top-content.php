<?php
// count qty tour by type
// count northland
$north = "SELECT count(sku_tour) as total FROM tour WHERE id_type = 7 AND flag = 1";
$rs_north = mysqli_query($conn, $north);
$row_north = mysqli_fetch_array($rs_north);
$total_north = $row_north['total'];

// count central
$central = "SELECT count(sku_tour) as total FROM tour WHERE id_type = 1 AND flag = 1";
$rs_central = mysqli_query($conn, $central);
$row_central = mysqli_fetch_array($rs_central);
$total_central = $row_central['total'];

// count southern
$new_southern = "SELECT count(sku_tour) as total FROM tour WHERE id_type = 10 AND flag = 1";
$rs_southern = mysqli_query($conn, $southern);
$row_southern = mysqli_fetch_array($rs_southern);
$total_southern = $row_southern['total'];
?>

<!-- navbar mobile -->
<div class="slider">
	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<?php
			// get slider active
			$slider_ac = "SELECT id_slider, image, link FROM slider ORDER BY id_slider DESC LIMIT 1";
			$rs_slider_ac = mysqli_query($conn, $slider_ac);
			$row_slider_ac = mysqli_fetch_array($rs_slider_ac);
			$id_slider_ac = $row_slider_ac['id_slider'];
			?>
			<div class="carousel-item active">
				<a href="<?php echo $row_slider_ac['link']; ?>">
					<img class="d-block w-100" src="admin/pages/public/images/sliders/<?php echo $row_slider_ac['image']; ?>" alt="<?php echo $row_slider_ac['image']; ?>">
				</a>
			</div>
			<?php

			// get slider active
			$slider = "SELECT image, link FROM slider WHERE id_slider <> $id_slider_ac ORDER BY id_slider DESC";
			$rs_slider = mysqli_query($conn, $slider);
			while ($row_slider = mysqli_fetch_array($rs_slider)) {
			?>
				<div class="carousel-item">
					<a href="<?php echo $row_slider['link']; ?>">
						<img class="d-block w-100" src="admin/pages/public/images/sliders/<?php echo $row_slider['image']; ?>" alt="<?php echo $row_slider['image']; ?>">
					</a>
				</div>
			<?php
			}
			// end slider
			?>
		</div>
		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>
<!-- slider -->
</div>
<!-- bot header -->
</div>
<!-- header -->
<div id="content">
	<div class="top_content">
		<div class="container">
			<div class="row">
				<div class="col-4 sm_hidden">
					<div class="box">
						<a href="loai-san-pham/Do-JumpSuit-1-1.html">
							<div class="figure">
								<img src="public/images/1.jpg">
								<div class="caption">
									<div class="about">
										<h2>TOUR MIỀN BẮC</h2>
										<p>Cập nhật các tour Miền Bắc mới nhất</p>
										<p><?php echo number_format($total_north); ?> TOUR </p>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!-- box banner -->
				</div>
				<!-- col-lg-4 -->
				<div class="col-4 sm_hidden">
					<div class="box">
						<a href="loai-san-pham/Ao-So-Mi-7-1.html">
							<div class="figure">
								<img src="public/images/2.jpg" alt="2.jpg">
								<div class="caption">
									<div class="about">
										<h2>TOUR MIỀN TRUNG</h2>
										<p>Cập nhật các tour Miền Trung mới nhất</p>
										<p><?php echo number_format($total_central); ?> TOUR</p>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!-- box banner -->
				</div>
				<!-- col-lg-4 -->
				<div class="col-4 sm_hidden">
					<div class="box">
						<a href="loai-san-pham/Do-phu-kien-10-1.html">
							<div class="figure">
								<img src="public/images/3.jpg" alt="3.jpg">
								<div class="caption">
									<div class="about">
										<h2>TOUR MIỀN NAM</h2>
										<p>Cập nhật các tour Miền Nam mới nhất</p>
										<p><?php echo number_format($total_southern); ?> TOUR</p>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!-- box banner -->
				</div>
				<!-- col-lg-4 -->
			</div>
			<!-- row -->
		</div>
		<!-- container -->
	</div>
	<!-- top content -->