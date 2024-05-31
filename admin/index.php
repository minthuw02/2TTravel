<?php 
	
    // Mở phiên session
    session_start();

    // Kiểm tra nếu session 'user' và 'level' tồn tại
    if(isset($_SESSION['user']) && isset($_SESSION['level']))
    {
    	// Nếu có, chuyển hướng đến trang admin
    	header('Location: pages/index.php');
    }
    else
    {
    	// Nếu không, chuyển hướng về trang đăng nhập
    	header('Location: pages/dang-nhap.php');
    }
?>
