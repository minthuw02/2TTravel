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
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];

        // Xóa
        if(isset($_GET['id']))
        {
            $target_dir = "public/images/tours/";
            $id = $_GET['id'];
            $sql = "SELECT sku_tour, image, name_tour FROM tour WHERE sku_tour = '$id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            $delname = $row['name_tour'];
            $sku_tour = $row['sku_tour'];

            // Xóa ảnh cũ và ghi nhận xóa
            $attach = "SELECT name_image FROM image WHERE sku_tour = '$sku_tour'";
            $rs_attach = mysqli_query($conn, $attach);
            while ($row_attach = mysqli_fetch_array($rs_attach)) 
            {
                $image_attach = $row_attach['name_image'];
                $remove_attach = $target_dir.$image_attach;
                if($image_attach != 'no-image.png')
                {
                    unlink($remove_attach);
                }

                // Xóa bản ghi ảnh đính kèm
                $del_attach = "DELETE FROM image WHERE sku_tour = '$sku_tour'";
                mysqli_query($conn, $del_attach);
            }

            // Xóa ảnh của tour
            $old_image = $row['image'];
            $target_file = $target_dir . $old_image;
            if($old_image != "no-image.png")
            {
                unlink($target_file);
            }

            // Thêm vào lịch sử
            $text = " Đã xóa tour <b>". $delname . "</b>";
            $time = date('Y-m-d H:i:s');
            $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
            mysqli_query($conn, $ins_his);

            // Xóa bản ghi tour
            $del = "DELETE FROM tour WHERE sku_tour = '$id'";
            mysqli_query($conn, $del);
            echo "<script>alert('Xóa tour thành công');</script>";
            echo "<script>location.href='tour.php';</script>";
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
                                            <li class="breadcrumb-item active" aria-current="page">Danh sách tour</li>
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
                                <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Danh sách tour
                                    <div class="btn_function float-right">
                                        <a href='upload-tour.php' class='btn btn-outline-primary'><i class='fas fa-plus'></i> Tour </a>
                                    </div>
                                </h5>
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table class="table">
                                            <thead class="bg-light">
                                                <tr class="border-0">
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">STT</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Ảnh</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Tên tour</th>
                                                     <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Slug</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Loại</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Ngày đăng</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Duyệt</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Sửa</th>
                                                    <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Xóa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                        <?php 
                            
                            // Kiểm tra session 
                            if($level == 1)
                            {
                                $tour = "SELECT sku_tour, image, name_tour, date_upload, p.slug as slug, typename, flag FROM tour p, type_tour tt WHERE p.id_type = tp.id_type ORDER BY date_upload DESC";
                                $rs_tour = mysqli_query($conn, $tour);
                                $count = 0;
                                while ($row_tour = mysqli_fetch_array($rs_tour)) 
                                {
                                    $count++;
                        ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td>
                                                        <div class="m-r-10">
                                                            <a href="anh-tour.php?id=<?php echo $row_tour['sku_tour']; ?>" title="Click để thêm ảnh tour">
                                                                <img src="public/images/tours/<?php echo $row_tour['image']; ?>" alt="<?php echo $row_tour['image']; ?>" class="rounded" width="60">
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $row_tour['name_tour']; ?></td>
                                                    <td><?php echo $row_tour['slug']; ?></td>
                                                    <td width="10%"><?php echo $row_tour['typename']; ?></td>
                                                    <td width="10%">
                                                    <?php 
                                                        $date = date_create($row_tour['date_upload']);
                                                        echo date_format($date, "d-m-Y H:i:s");
                                                    ?>
                                                    </td>
                                                    <td>
                                                <?php 

                                                    if($row_tour['flag'] == 1)
                                                    {
                                                        echo "<a href='modules/flags.php?id=".$row_tour['sku_tour']."&tbl=tour' class='btn btn-light'>
                                                                <i class='fas fa-times'></i>
                                                            </a>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='modules/flags.php?id=".$row_tour['sku_tour']."&tbl=tour' class='btn btn-success'>
                                                                <i class='fas fa-check'></i>
                                                            </a>";
                                                        
                                                    }
                                                ?> 
                                                    </td>
                                                    <td><a href="e-upload-tour.php?id=<?php echo $row_tour['sku_tour']; ?>" class="btn btn-info"><i class="fas fa-pen-nib"></i></a></td>
                                                    <td><a href="tour.php?id=<?php echo $row_tour['sku_tour']; ?>" onclick="return confirm('Dữ liệu này sẽ được xóa vĩnh viễn. Đồng ý?');" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
                                                </tr>
                        <?php 
                                }
                                // Kết thúc while
                            }
                            else
                            {
                                $tour2 = "SELECT sku_tour, p.image as image, name_tour, date_upload, p.slug as slug, typename, flag FROM tour , type_tour tt, account a WHERE p.id_type = tp.id_type AND a.id_acc = p.author AND email = '$users' ORDER BY date_upload DESC";
                                $rs_tour2 = mysqli_query($conn, $tour2);
                                $count = 0;
                                while ($row_tour2 = mysqli_fetch_array($rs_tour2)) 
                                {
                                    $count++;

                        ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td>
                                                        <div class="m-r-10">
                                                            <?php
                                                                
                                                                if($row_tour2['flag'] == 1)
                                                                {
                                                                    echo "<img src='public/images/tours/".$row_tour2['image']."' alt='".$row_tour2['image']."' class='rounded' width='60'>";
                                                                }
                                                                else
                                                                {
                                                                    echo "<a href='anh-san-pham.php?id=".$row_tour2['sku_tour']."' title='Click để thêm ảnh tour'>
                                                                        <img src='public/images/tours/".$row_tour2['image']."' alt='".$row_tour2['image']."' class='rounded' width='60'>
                                                                        </a>";
                                                                }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $row_tour2['name_tour']; ?></td>
                                                    <td><?php echo $row_tour2['slug']; ?></td>
                                                    <td width="10%"><?php echo $row_tour2['typename']; ?></td>
                                                    <td width="10%">
                                                    <?php 
                                                        $date = date_create($row_tour2['date_upload']);
                                                        echo date_format($date, "d-m-Y H:i:s");
                                                    ?>
                                                    </td>
                                                    <td>
                                                <?php
                                                    if($row_tour2['flag'] == 1)
                                                    {
                                                        echo "<button type='button' class='btn btn-light' disabled><i class='fas fa-times'></i></button>";
                                                    }
                                                    else
                                                    {
                                                        echo "<button type='button' class='btn btn-success' disabled><i class='fas fa-check'></i></button>";
                                                    }
                                                ?> 
                                                    </td>
                                                <?php 

                                                    if($row_tour2['flag'] == 1)
                                                    {
                                                        // Nếu đã duyệt thì không cho phép sửa và xóa
                                                        echo "<td><button type='button' class='btn btn-info' disabled><i class='fas fa-pen-nib'></i></button></td>";
                                                        echo "<td><button type='button' class='btn btn-danger' disabled><i class='fas fa-trash-alt'></i></button></td>";
                                                    }
                                                    else
                                                    {
                                                        echo "<td><a href='e-upload-san-pham.php?id=".$row_tour2['sku_tour']."' class='btn btn-info'><i class='fas fa-pen-nib'></i></a></td>";
                                                        echo "<td><a href='san-pham.php?id=".$row_tour2['sku_tour']."' onclick='return confirm('Dữ liệu này sẽ được xóa vĩnh viễn. Đồng ý?');' class='btn btn-danger'><i class='fas fa-trash-alt'></i></a></td>";
                                                    }
                                                ?>
                                                </tr>
                        <?php
                                }
                                // Kết thúc while 2
                            }
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
        // Footer
        include('includes/footer.php');
    }
    else
    {
        // Chuyển hướng đến trang đăng nhập nếu session không tồn tại
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>
