<?php 
    
    // Mở phiên session
    session_start();

    // Kiểm tra nếu session 'user' và 'level' tồn tại
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
        // Lấy giá trị của session 'level' và 'user'
        $level = $_SESSION['level'];
        $users = $_SESSION['user'];

        // Bao gồm các file header, navbar và left-sidebar
        include('includes/header.php');
        include('includes/navbar.php');
        include('includes/left-sidebar.php');

        // Bao gồm file chứa các hàm
        require('modules/functions.php');

        // Lấy thông tin từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $name_user = $row_session['name'];
        $id_acc = $row_session['id_acc'];

        // Tạo loại  tour
        if(isset($_POST['save']))
        {
            $name_type = $_POST['name_type'];
            $slug = generateURL($name_type); // Hàm generateURL() được giả định là đã được định nghĩa trong file functions.php
            $date_create = date("Y-m-d H:i:s");

            if($name_type)
            {
                // Thêm dữ liệu
                $ins = "INSERT INTO type_tour(typename, slug_type, date_create) VALUES('$name_type', '$slug', '$date_create')";
                mysqli_query($conn, $ins);
                echo "<script>alert('Tạo loại tour thành công');</script>";
                echo "<script>location.href='loai-tour.php';</script>";

                // Thêm vào lịch sử
                $text = " đã tạo loại tour <b>". $name_type . "</b>";
                $time = date('Y-m-d H:i:s');
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);
            }
            else
            {
                echo "<script>alert('Vui lòng nhập tên loại!.');</script>";
            }
        }

        // Xóa loại tour
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
            $target_dir = "public/images/tours/";

            // Lấy dữ liệu loại tour
            $data = "SELECT id_type, typename FROM type_tour WHERE id_type = $id";
            $rs_data = mysqli_query($conn, $data);
            $row_data = mysqli_fetch_array($rs_data);
            $delname = $row_data['typename'];
            $id_type = $row_data['id_type'];

            // Xóa hình ảnh tour và tour thuộc loại
            $p = "SELECT sku_tour, image FROM tour WHERE id_type = $id";
            $rs_p = mysqli_query($conn, $p);
            while($row_p = mysqli_fetch_array($rs_p))
            {
                $sku_tour = $row_p['sku_tour'];
                
                // Xóa hình ảnh đính kèm tour và bản ghi đính kèm tour
                $attach = "SELECT name_image FROM image WHERE sku_tour = '$sku_tour'";
                $rs_attach = mysqli_query($conn, $attach);
                while ($row_image_attach = mysqli_fetch_array($rs_attach)) 
                {
                    // Xóa hình ảnh đính kèm
                    $image_attach = $row_image_attach['name_image'];
                    $remove_attach = $target_dir . $image_attach;
                    if($image_attach != "no-image.png")
                    {
                        unlink($remove_attach);
                    }

                    // Xóa bản ghi hình ảnh đính kèm
                    $del_attach = "DELETE FROM image WHERE sku_tour = '$sku_tour'";
                    mysqli_query($conn, $del_attach);
                }
                

                // Xóa hình ảnh tour
                $image_p = $row_p['image'];
                $remove_image_p = $target_dir . $image_p;
                if($image_p != "no-image.png")
                {
                    unlink($remove_image_p);
                }

                // Xóa bản ghi tour
                $del_p = "DELETE FROM tour WHERE id_type = $id";
                mysqli_query($conn, $del_p);
            }
            

            // Thêm vào lịch sử
            $text = " đã xóa loại tour <b>". $delname . "</b>";
            $time = date('Y-m-d H:i:s');
            $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
            mysqli_query($conn, $ins_his);

            // Xóa bản ghi loại tour
            $del = "DELETE FROM type_tour WHERE id_type = $id";
            mysqli_query($conn, $del);
            echo "<script>alert('Xóa thành công');</script>";
            echo "<script>location.href='loai-tour.php';</script>";
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
                                <h2 class="pageheader-title" style="font-family: 'Roboto Condensed', sans-serif;">Welcome to Admin</h2>
                                <!--
                                <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                                -->
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Trang chủ</a></li>
                                            <li class="breadcrumb-item"><a href="tour.php" class="breadcrumb-link">Tour</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Tạo loại tour</li>
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
                                <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Tạo loại tour</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <form method="POST">
                                                <div class="form-group">
                                                    <label>Tên loại*:</label>
                                                    <input type="text" name="name_type" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày tạo:</label>
                                                    <input type="text" name="date_create" class="form-control" value="<?php echo date('d-m-Y H:i:s'); ?>" disabled>
                                                </div>
                                                <?php 

                                                    if($level == 1)
                                                    {
                                                        echo "<button type='submit' class='btn btn-primary' name='save'>Tạo loại mới</button>";
                                                    }
                                                    else
                                                    {
                                                        echo "<button type='submit' class='btn btn-primary' disabled>Tạo loại mới</button>";
                                                    }
                                                ?>
                                            </form>
                                        </div>
                                        <!-- col-lg-12 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- col-lg-12 -->

                        <!-- Bảng danh sách loại tour -->
                        <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Danh sách loại tour
                                </h5>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="bg-light">
                                                <tr class="border-0">
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">STT</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Tên loại</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Slug</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Ngày tạo</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Sửa</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Xóa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                            <?php 
                                
                                $typep = "SELECT * FROM type_tour ORDER BY typename DESC";
                                $rs_typep = mysqli_query($conn, $typep);
                                $count = 0;
                                while ($row_typep = mysqli_fetch_array($rs_typep)) 
                                {
                                    $count++;
                            ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo $row_typep['typename']; ?></td>
                                                    <td><?php echo $row_typep['slug_type']; ?></td>
                                                    <td>
                                                    <?php 
                                                        $date = date_create($row_typep['date_create']);
                                                        echo date_format($date, "d-m-Y H:i:s");
                                                    ?>
                                                    </td>



                                                <?php 
                                                    if($level == 1)
                                                    {
                                                ?>
                                                        <td><a href="e-loai-tour.php?id=<?php echo $row_typep['id_type']; ?>" class="btn btn-info"><i class="fas fa-pen-nib"></i></a></td>
                                                        <td><a href="loai-tour.php?id=<?php echo $row_typep['id_type']; ?>" onclick="return confirm('Dữ liệu này sẽ được xóa vĩnh viễn. Đồng ý?');" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                        <td><button type="button" class="btn btn-info" disabled><i class="fas fa-pen-nib"></i></button></td>
                                                        <td><button type="button" class="btn btn-danger" disabled><i class="fas fa-trash-alt"></i></button></td>
                                                <?php
                                                    }
                                                ?>

                                                    
                                                </tr>
                            <?php 
                                }

                                // Kết thúc vòng lặp while
                            ?>
                                            </tbody>
                                        </table>
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
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>
