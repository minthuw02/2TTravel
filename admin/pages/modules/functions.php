<?php 
  
  // Hàm đổi tên tệp thành tên mới để tránh trùng lặp
  function name_code($str)
  {
      // Lấy phần mở rộng của tệp
      $ext = strtolower(pathinfo($str, PATHINFO_EXTENSION));
      // Tạo tên mới bằng cách kết hợp thời gian hiện tại và phần mở rộng
      $new_name = time() .".". $ext;
      // Trả về tên mới
      return $new_name;
  }

  // Hàm sinh URL thân thiện từ chuỗi
  function generateURL($str) {
       // Bảng ánh xạ ký tự đặc biệt
       $charMap = array(
         // In thường
         "a" => "á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ",
         "d" => "đ",
         "e" => "é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ",
         "i" => "í|ì|ỉ|ĩ|ị",
         "o" => "ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ",
         "u" => "ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự",
         "y" => "ý|ỳ|ỷ|ỹ|ỵ",
         // In hoa
         "A" => "Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ",
         "D" => "Đ",
         "E" => "É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ",
         "I" => "Í|Ì|Ỉ|Ĩ|Ị",
         "O" => "Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ",
         "U" => "Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự",
         "Y" => "Ý|Ỳ|Ỷ|Ỹ|Ỵ",
         "" => ",", // Ký tự đặc biệt trong URL sẽ được thay thế bằng dấu gạch ngang
      );

      // Thực hiện thay thế ký tự đặc biệt trong chuỗi bằng các ký tự tương ứng trong bảng ánh xạ
      foreach($charMap as $replace => $search){
        $str = preg_replace("/($search)/i", $replace, $str);
      }
      // Thay thế khoảng trắng bằng dấu gạch ngang
      return str_replace(" ", "-", $str);
  }

?>
