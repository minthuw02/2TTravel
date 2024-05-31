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

        // Truy vấn để lấy thông tin từ session
        $session = "SELECT * FROM account WHERE email = '".$users."'";
        $rs_session = mysqli_query($conn, $session);
        $row_session = mysqli_fetch_array($rs_session);
        $id_acc = $row_session['id_acc'];
        $name_user = $row_session['name'];

        // Lấy dữ liệu dựa trên id bài viết nếu có tham số 'id' được truyền qua URL
        if(isset($_GET['id']))
        {
            // Lấy giá trị 'id' từ URL
            $id = $_GET['id'];
            
            // Truy vấn để lấy thông tin về bài viết dựa trên 'id'
            $sql = "SELECT * FROM blog WHERE id_blog = $id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            $change_name = $row['title'];
        }

        // Xử lý khi người dùng nhấn nút upload
        if(isset($_POST['upload']))
        {
            // Lấy dữ liệu từ form
            $title = $_POST['title'];
            $slug = generateURL($title);
            $id_type = $_POST['id_type'];
            $highlight = $_POST['highlight'];
            $summary = $_POST['summary'];
            $content = $_POST['content'];

            // Thiết lập đường dẫn cho ảnh thumbnail
            $target_dir = "public/images/blogs/";
            $image = $_FILES['image']['name'];
            $name_code = name_code($image);
            $imageFileType = strtolower(pathinfo($name_code, PATHINFO_EXTENSION));
            $target_file = $target_dir.$name_code;

            if($image)
            {
                if($title)
                {
                    // Kiểm tra định dạng ảnh
                    if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif")
                    {
                        echo "<script>alert('Chỉ nhận file ảnh dạng: jpg, jpeg, png, gif.');</script>";
                    }
                    else
                    {
                        // Thêm vào lịch sử
                        $text = " đã chỉnh sửa bài viết <b>". $title . "</b>";
                        $time = date('Y-m-d H:i:s');
                        $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                        mysqli_query($conn, $ins_his);

                        // Xóa file cũ (nếu có)
                        $old_image = $row['image'];
                        $remove_image = $target_dir . $old_image;
                        if($old_image != "no-image.jpg")
                        {
                            unlink($remove_image);
                        }

                        // Upload file mới
                        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

                        // Cập nhật thông tin với ảnh
                        $update = " UPDATE blog SET 
                                    image = '".$name_code."',
                                    title = '".$title."',
                                    slug = '".$slug."',
                                    summary = '".$summary."',
                                    content = '".$content."',
                                    highlight = ".$highlight.",
                                    id_type = ".$id_type."
                                    WHERE id_blog = $id";

                        mysqli_query($conn, $update);
                        echo "<script>alert('Lưu lại thành công');</script>";
                        echo "<script>location.href='blog.php';</script>";
                    }
                }
                else
                {
                    echo "<script>alert('Vui lòng nhập tiêu đề bài viết');</script>";
                }
            }
            else
            {
                // Thêm vào lịch sử
                $text = " đã chỉnh sửa bài viết <b>". $title . "</b>";
                $time = date('Y-m-d H:i:s');
                $ins_his = "INSERT INTO history(text, time, id_acc, flag) VALUES('$text','$time', '$id_acc', 0)";
                mysqli_query($conn, $ins_his);

                // Cập nhật thông tin không có ảnh
                $update = " UPDATE blog SET 
                            title = '".$title."',
                            slug = '".$slug."',
                            summary = '".$summary."',
                            content = '".$content."',
                            highlight = ".$highlight.",
                            id_type = ".$id_type."
                            WHERE id_blog = $id";
                            
                mysqli_query($conn, $update);
                echo "<script>alert('Lưu lại thành công');</script>";
                echo "<script>location.href='blog.php';</script>";
            }
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
                                        <li class="breadcrumb-item"><a href="blog.php" class="breadcrumb-link">Bài viết</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa bài viết</li>
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
                            <h5 class="card-header" style="font-family: 'Roboto Condensed', sans-serif;">Chỉnh sửa bài viết</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-xs-12">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Tiêu đề bài viết*:</label>
                                                <textarea class="form-control" name="title"><?php echo $row['title']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Chọn ảnh thumbnail:</label>
                                                <input type="file" class="form-control" name="image">
                                            </div>
                                            <div class="form-group">
                                                <label>Chọn loại bài viết:</label>
                                                <select class="form-control" name="id_type">

                                                    <?php 

                                                        // Hiển thị loại bài viết
                                                        $active = "SELECT tb.id_type as id_type, typename FROM blog b, type_blog tb WHERE b.id_type = tb.id_type AND b.id_blog = $id";
                                                        $rs_active = mysqli_query($conn, $active);
                                                        $row_active = mysqli_fetch_array($rs_active);
                                                        $id_active = $row_active['id_type'];
                                                    ?>
                                                    <option value="<?php echo $row_active['id_type']; ?>"><?php echo $row_active['typename']; ?></option>
                                                    <?php 
                                                        // Hiển thị loại bài viết
                                                        $type_op = "SELECT id_type, typename FROM type_blog WHERE id_type <> $id_active";
                                                        $rs_type_op = mysqli_query($conn, $type_op);
                                                        while ($row_type_op = mysqli_fetch_array($rs_type_op))
                                                        {
                                                    ?>
                                                        <option value="<?php echo $row_type_op['id_type']; ?>"><?php echo $row_type_op['typename']; ?></option>
                                                    <?php 
                                                        }
                                                        // Kết thúc vòng lặp
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Bài viết nổi bật:</label>
                                                <select class="form-control" name="highlight">
                                                    <?php 

                                                        if($row['highlight'] == 1)
                                                        {
                                                    ?>
                                                        <option value="1">Có</option>
                                                        <option value="0">Không</option>
                                                    <?php
                                                        }
                                                        else
                                                        {
                                                    ?>
                                                        <option value="0">Không</option>
                                                        <option value="1">Có</option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Tóm tắt:</label>
                                                <textarea class="form-control" name="summary"><?php echo $row['summary']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Nội dung bài viết:</label>
                                                <textarea class="form-control" id="ckeditor" name="content"><?php echo $row['content']; ?></textarea>
                                            </div>
                                            <button type='submit' class='btn btn-warning' name='upload'>Lưu lại</button>
                                        </form>
                                    </div>
                                    <!-- col-lg-12 -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col-lg-12 -->
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Footer -->
        <!-- ============================================================== -->
<?php 
        // Footer
        include('includes/footer.php');
    }
    else
    {
        // Chuyển hướng đến trang dang-nhap.php nếu không có session
        echo "<script> location.href='dang-nhap.php'; </script>";
    }
?>
