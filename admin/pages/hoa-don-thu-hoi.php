<?php 
    
    // Mở session
    session_start();

    // Kiểm tra xem session 'user' và 'level' có tồn tại không
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Lấy giá trị của session 'level' và 'user'
        $level = $_SESSION['level'];
        $users = $_SESSION['user'];
        
        // Bao gồm các file header, navbar, left-sidebar
        include('includes/header.php');
        include('includes/navbar.php');
        include('includes/left-sidebar.php');

        // Bao gồm các hàm trong file functions.php
        require('modules/functions.php');
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
                            <h2 class="pageheader-title" style="font-family: 'Roboto Condensed', sans-serif;">Welcom to Admin</h2>
                            <!--
                            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                            -->
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Trang chủ</a></li>
                                        <li class="breadcrumb-item"><a href="don-hang.php" class="breadcrumb-link">Hóa đơn</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Quản lý hóa đơn</li>
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
<?php 
        
        // Truy vấn các đơn hàng có trạng thái "đã giao thành công" và hiển thị thông tin
        $invoice = "SELECT code_invoice, order_date, info_receive, info_product, name, address, phone  FROM invoice inv, account a WHERE inv.id_customer = a.id_acc AND flag = 3 ORDER BY order_date DESC";
        $rs_invoice = mysqli_query($conn, $invoice);
        while ($row_invoice = mysqli_fetch_array($rs_invoice)) 
        {
?>
                    <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                               <i class="fas fa-file-invoice"></i> Mã hóa đơn: 
                                    <?php echo $row_invoice['code_invoice']; ?>
                                    <?php 
                                        $date = date_create($row_invoice['order_date']);
                                        echo "(" . date_format($date, "d/m/Y H:i:s") . ")";
                                    ?>
                                    <a href="chi-tiet-don-hang.php?code_invoice=<?php echo $row_invoice['code_invoice']; ?>&delivery" class="btn btn-outline-info float-right" title="Xem chi tiết hóa đơn">
                                        Chi tiết hóa đơn
                                    </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 text-center">
                                        <img src="public/images/invoice.png" width="75%">
                                    </div>
                                    <!-- col-lg-2 -->
                                    <div class="col-lg-10" style="text-align:  justify;">
                                        <p style="font-family: 'Roboto Condensed', sans-serif;">Họ tên khách hàng: <b><?php echo $row_invoice['name']; ?></b></p>
                                        <p style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Địa chỉ: <b> <?php echo $row_invoice['address']; ?> (Mặc định)</b><p>
                                        <p style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Số điện thoại: <b> <?php echo $row_invoice['phone']; ?></b><p>
                                        <!--<p style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Thông tin nhận hàng mới: <b> <?php echo $row_invoice['info_receive']; ?></b><p>-->
                                        <p style="font-family: 'Roboto Condensed', sans-serif; margin-top: -15px;">Ghi chú: <b> <?php echo $row_invoice['info_product']; ?></b><p>
                                    </div>
                                    <!-- col-lg-10 -->
                                </div>
                                <!-- row -->
                            </div>
                            <div class="card-footer">
                                <a href="delivery.php?code_invoice=<?php echo $row_invoice['code_invoice']; ?>&recalled" class="btn btn-success" title="Vận chuyển thành công">
                                    <i class="fas fa-check"></i> thành công
                                </a>
                                <a href="cancel-invoice.php?cancel=<?php echo $row_invoice['code_invoice']; ?>&delivery" class="btn btn-danger" title="Hủy bỏ hóa đơn" onclick="return confirm('Hóa đơn này sẽ được hủy vĩnh viễn. Đồng ý?')">
                                    <i class="fas fa-times"></i> hóa đơn
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- col-lg-12 -->
<?php 
        }
        // end while
?>                
                </div>
            </div>
        </div>
<?php 
        // footer
        include('includes/footer.php');
    }
    else
    {
        // Chuyển hướng đến trang dang-nhap.php nếu không có session
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>

