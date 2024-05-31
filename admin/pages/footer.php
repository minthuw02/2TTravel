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

        // Lấy dữ liệu từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];

        // Tạo thông tin liên hệ
        if(isset($_POST['save']))
        {
            // Lấy nội dung từ form
            $content = $_POST['content'];
            $date_create = date("Y-m-d H:i:s");

            // Kiểm tra nội dung có tồn tại không
            if($content)
            {
                // Thêm thông tin vào lịch sử
                $text = " đã tạo thông tin liên hệ";
                $time = date('Y-m-d H:i:s');
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Thêm dữ liệu vào cơ sở dữ liệu
                $ins = "INSERT INTO contact(content, date_create) VALUES('$content','$date_create')";
                mysqli_query($conn, $ins);
                echo "<script>alert('Tạo thông tin liên hệ thành công');</script>";
                echo "<script>location.href='footer.php';</script>";
            }
            else
            {
                echo "<script>alert('Vui lòng nhập nội dung!.');</script>";
            }
        }

        // Xóa thông tin liên hệ
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];

            // Thêm thông tin vào lịch sử
            $text = " đã xóa loại thông tin liên hệ";
            $time = date('Y-m-d H:i:s');
            $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
            mysqli_query($conn, $ins_his);

            // Xóa bản ghi từ cơ sở dữ liệu
            $del = "DELETE FROM contact WHERE id_contact = $id";
            mysqli_query($conn, $del);
            echo "<script>alert('Xóa thông tin liên hệ thành công');</script>";
            echo "<script>location.href='footer.php';</script>";
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
                                            <li class="breadcrumb-item"><a href="footer.php" class="breadcrumb-link">Chân trang</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Cấu hình chân trang</li>
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
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Thêm thông tin liên hệ</h5>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                                <form method="POST">
                                                    <div class="form-group">
                                                        <label>Nội dung*:</label>
                                                        <input type="text" name="content" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Ngày tạo:</label>
                                                        <input type="text" name="date_create" class="form-control" value="<?php echo date('d-m-Y H:i:s'); ?>" disabled>
                                                    </div>
                                                    <?php 

                                                        // Kiểm tra quyền của người dùng để hiển thị nút thêm
                                                        if($level == 1)
                                                        {
                                                            echo "<button type='submit' class='btn btn-primary' name='save'>Thêm thông tin</button>";
                                                        }
                                                        else
                                                        {
                                                            echo "<button type='submit' class='btn btn-primary' disabled>Thêm thông tin</button>";
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

                            <!-- Bảng thông tin liên hệ -->
                            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Danh sách các mục thông tin
                                    </h5>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="bg-light">
                                                    <tr class="border-0">
                                                        <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">STT</th>
                                                        <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Tên loại</th>
                                                        <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Ngày tạo</th>
                                                        <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Sửa</th>
                                                        <th class="border-0" style="font-family: 'Roboto Condensed', sans-serif;">Xóa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                <?php 
                                    
                                    // Lấy danh sách thông tin liên hệ từ cơ sở dữ liệu
                                    $contact = "SELECT * FROM contact ORDER BY date_create DESC";
                                    $rs_contact = mysqli_query($conn, $contact);
                                    $count = 0;
                                    while ($row_contact = mysqli_fetch_array($rs_contact)) 
                                    {
                                        $count++;
                                ?>
                                                    <tr>
                                                        <td><?php echo $count; ?></td>
                                                        <td><?php echo $row_contact['content']; ?></td>
                                                        <td>
                                                        <?php 
                                                            // Format ngày tháng
                                                            $date = date_create($row_contact['date_create']);
                                                            echo date_format($date, "d-m-Y H:i:s");
                                                        ?>
                                                        </td>
                                                <?php 
                                                    // Kiểm tra quyền của người dùng để hiển thị nút sửa và xóa
                                                    if($level == 1)
                                                    {
                                                ?>
                                                        <td><a href="e-contact.php?id=<?php echo $row_contact['id_contact']; ?>" class="btn btn-info"><i class="fas fa-pen-nib"></i></a></td>
                                                        <td><a href="footer.php?id=<?php echo $row_contact['id_contact']; ?>" onclick="return confirm('Dữ liệu này sẽ được xóa vĩnh viễn. Đồng ý?');" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
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
                        <!-- row -->
                    </div>
                </div>
            </div>
<?php 
        // Footer
        include('includes/footer.php');
    }
    else
    {
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>
