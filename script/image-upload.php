<?php
/*
 * TODO: GET IMG UPLOAD
 * CONVERT IMG TO JPG ONLY
 * FIXED SIZE ONLY 285x200
 * QUALITY 0.4
 */
if (!file_exists('../media/placeholder/tmp/')) {
    mkdir('../media/placeholder/tmp/', 0777, true);
}

$path = "../media/placeholder/";
$path_file = $path . "tmp/" . basename($_FILES['image-upload-input']['name']);
$img_filetype = pathinfo($path_file,PATHINFO_EXTENSION);
$upload_ok = 0;
$err_msg = "";
require("sqlConnect.php");
// FOR NOW GET COUNT OF TABLE THEN ADD 1 TO GENERATE NAME. NO WAY TO DELETE ENTRY. OTHERWISE WE USE FOR LOOP.

print_r($path_file);
print_r($_FILES['image-upload-input']);

// GET IMAGE
$check = getimagesize($_FILES['image-upload-input']['tmp_name']);
if($check!==false) {
    $upload_ok = 1;
} else {
    $upload_ok = 0;
    $err_msg = "Unsupported file. Please upload only image.";
}
if($_FILES['image-upload-input']['size'] > 1000000) {
    $upload_ok = 0;
    $err_msg = "Image size to big! Please upload image with size lower than 1MB.";
} else {
    $upload_ok = 1;
}
if($img_filetype != "jpg" && $img_filetype != "jpeg" && $img_filetype != "png" && $img_filetype != "gif" && $img_filetype != "bmp") {
    $upload_ok = 0;
    $err_msg = "Unsupported file type. Supported images: jpg, png, gif or bmp.";
} else {
    $upload_ok = 1;
}

if($upload_ok == 0) {
    // Validate image before proceeding
    $response_arr['status'] = "error";
    $response_arr['msg'] = "One or more errors found: " . $err_msg;
    header('Content-type: application/json');
    echo json_encode($response_arr);
    // Exit since error has occurred.
} else {
    // Save tmp image
    move_uploaded_file($_FILES["image-upload-input"]["tmp_name"], $path_file);
    
    // Convert image before updating DB
    $err_msg = "";
    $count = 0;
    $img_new_name;
    $sql = "SELECT COUNT(*) AS image_count FROM placeholders;";
    $rslt = $mConn->query($sql);
    while($row=$rslt->fetch_assoc()) {
        $count = $row['image_count'];
    }
    $count = $count + 1;
    $img_new_name = "hotel-" . $count . "-placeholder";
    if($img_filetype == 'jpg' || $img_filetype == 'jpeg') {
        $imageTmp=imagecreatefromjpeg($path_file);
    }
    if($img_filetype == 'png') {
        $imageTmp=imagecreatefrompng($path_file);
    }
    if($img_filetype == 'bmp') {
        $imageTmp=imagecreatefromwbmp($path_file);
    }
    if($img_filetype == 'gif') {
        $imageTmp=imagecreatefromgif($path_file);
    }
    $scaledImg = imagescale($imageTmp, 285, 200, IMG_BICUBIC);
    $x = $path . $img_new_name . ".jpg";
    imagejpeg($scaledImg, $x, 80);
    imagedestroy($imageTmp);
    unlink($path_file);
    
    $sql = "INSERT INTO placeholders (placeholder_name) VALUES ('$img_new_name');";
    $rslt = $mConn->query($sql);
    if($rslt===FALSE) {
        $err_msg = "Error in updating database: " . htmlspecialchars($mConn->error);
    }
    
    // Reply to JSON
    if(!empty($err_msg)) {
        $response_arr['status'] = "error";
        $response_arr['msg'] = "One or more errors found: " . $err_msg;
        header('Content-type: application/json');
        echo json_encode($response_arr);
    } else {
        $response_arr['status'] = "success";
        $response_arr['msg'] = "Successfully uploaded image";
        header('Content-type: application/json');
        echo json_encode($response_arr);
    }
}



?>