<?php 
    
    // Mở phiên làm việc
    session_start();

    // Kiểm tra xem người dùng đã đăng nhập và có quyền không
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Lấy cấp độ người dùng và email từ session
        $level = $_SESSION['level'];
        $users = $_SESSION['user'];

        // Bao gồm các file header, navbar và left-sidebar
        include('includes/header.php');
        include('includes/navbar.php');
        include('includes/left-sidebar.php');

        // Bao gồm tệp chứa các hàm
        require('modules/functions.php');

        // Lấy thông tin từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];

        // Kiểm tra xem có tham số code_invoice trong URL không
        if(isset($_GET['code_invoice']))
        {
            $code_invoice = $_GET['code_invoice'];
            // Truy vấn thông tin đơn hàng dựa trên mã đơn hàng
            $invoice = "SELECT code_invoice, info_receive, info_product,address, name, phone FROM invoice inv, account a WHERE inv.id_customer = a.id_acc AND code_invoice = '$code_invoice'";
            $rs_invoice = mysqli_query($conn, $invoice);
            $row_invoice = mysqli_fetch_array($rs_invoice);
        }

        // Xác nhận đơn hàng
        if(isset($_GET['confirm']))
        {
            $code_invoice = $_GET['confirm'];
            
            // Thêm hành động xác nhận vào lịch sử
            $text = " đã xác nhận đơn hàng <b>". $code_invoice . "</b>";
            $time = date('Y-m-d H:i:s');
            $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
            mysqli_query($conn, $ins_his);

            // Thay đổi cờ của hóa đơn thành trạng thái "đang vận chuyển"
            $reup_invoice = "UPDATE invoice SET flag = 1 WHERE code_invoice = '$code_invoice'";
            mysqli_query($conn, $reup_invoice);

            echo "<script>alert('Xác nhận đơn hàng thành công');</script>";
            echo "<script>location.href='don-hang.php';</script>";
        }

?>
<body>
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content " >
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header" >
                                <h2 class="pageheader-title" style="font-family: 'Roboto Condensed', sans-serif;">Quản trị website bán hàng</h2>
                                <!--
                                <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                                -->
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Trang chính</a></li>
                                            <li class="breadcrumb-item"><a href="don-hang.php" class="breadcrumb-link">Đơn hàng</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <div class="ecommerce-widget">
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">
                                    Thông tin khách hàng
                                </h5>
                                <div class="card-body" style="text-align: justify;">
                                    <h4 style="font-family: 'Roboto Condensed', sans-serif;">Tên khách hàng: <b> <?php echo $row_invoice['name']; ?> </b></h4>
                                    <h5 style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Địa chỉ: <b><?php echo $row_invoice['address']; ?>(Mặc định)</b></h5>
                                    <h5 style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Số điện thoại: <b><?php echo $row_invoice['phone']; ?>(Mặc định)</b></h5>
                                    <h5 style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Thông tin nhận hàng mới: <b><?php echo $row_invoice['info_receive']; ?></b></h5>
                                    <h5 style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Thông tin sản phẩm: <b><?php echo $row_invoice['info_product']; ?></b></h5>
                                </div>
            <?php 
                if(!isset($_GET['delivery']))
                {
            ?>
                                <div class="card-footer">
                                    <a href="chi-tiet-don-hang.php?confirm=<?php echo $row_invoice['code_invoice']; ?>" class="btn btn-primary btn-block">
                                        Xác nhận đơn hàng
                                    </a>
                                    <a href="cancel-invoice.php?cancel=<?php echo $row_invoice['code_invoice']; ?>&invoice" class="btn btn-danger btn-block" onclick="return confirm('Đơn hàng này sẽ được hủy vĩnh viễn. Đồng ý?')">
                                        Hủy đơn hàng
                                    </a>
                                </div>
            <?php 
                }
                // end check
            ?>
                            </div>
                            <!-- card -->
                        </div>
                        <!-- col-lg-12 -->
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Chi tiết đơn hàng
                                </h5>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="bg-light">
                                                <tr class="border-0">
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Mã sản phẩm</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Ảnh</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Tên sản phẩm</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Đơn giá</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Số lượng</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
        <?php 
            
            // Truy vấn chi tiết đơn hàng dựa trên mã đơn hàng
            $detail_invoice = "SELECT dinv.sku_product as sku_product, image, name_product, p.price as price, dinv.qty as qty FROM detail_invoice dinv, product p WHERE dinv.sku_product = p.sku_product AND code_invoice = '$code_invoice'";
            $rs_detail_invoice = mysqli_query($conn, $detail_invoice);
            $total_money = 0;
            while ($row_detail_invoice = mysqli_fetch_array($rs_detail_invoice)) 
            {
                $money = $row_detail_invoice['qty'] * $row_detail_invoice['price'];
                $total_money += $money;

        ?>
                                                <tr>
                                                    <td><?php echo $row_detail_invoice['sku_product']; ?></td>
                                                    <td><img src="public/images/products/<?php echo $row_detail_invoice['image']; ?>" width="80"></td>
                                                    <td><?php echo $row_detail_invoice['name_product']; ?></td>
                                                    <td width="15%"><?php echo number_format($row_detail_invoice['price']); ?>đ</td>
                                                    <td><?php echo number_format($row_detail_invoice['qty']); ?></td>
                                                    <td><?php echo number_format($money); ?>đ</td>
                                                </tr>
        <?php 
            }

            // end while
        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- card-body -->
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-3 float-right text-cente">
                                            <h4 style="font-family: 'Roboto Condensed', sans-serif;">Tổng giá trị đơn hàng: <h2 style="font-family: 'Roboto Condensed', sans-serif;"><b><?php echo number_format($total_money); ?>vnđ</b></h2></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- card -->
                        </div>
                        <!-- col-lg-12 -->
                    </div>
                </div>
            </div>
<?php 
        // footer
        include('includes/footer.php');
    }
    else
    {
        // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>
