<?php
header('Cache-Control: no-cache, must-revalidate');

//added 10/26/2022 to fix image rotation
function fix_jpeg_rotation($file) {
	
	if (function_exists('exif_read_data')) {
		$buffer = imagecreatefromjpeg($file);
		$exif = @exif_read_data($file);

		if ($exif && !empty($exif['Orientation'])) {
			
			if($exif['Orientation'] == 1) return;
			
			switch($exif['Orientation']) {
				case 8:
					$buffer = imagerotate($buffer, 90, 0);
				break;
				case 3:
					$buffer = imagerotate($buffer, 180, 0);
				break;
				case 6:
					$buffer = imagerotate($buffer, -90, 0);
				break;
				case 5: // vertical flip + 90 rotate right
					$buffer = imagerotate($buffer, -90, 0);
				break;
				case 7: // horizontal flip + 90 rotate right
					$buffer = imagerotate($buffer, -90, 0);
				break;
			}
		}
		imagejpeg($buffer, $file, 90);
	}
}
//------------------------------------------

//Specify url path
$path = 'uploads/'; // Physical path, relative to this file (savecover.php)
$urlpath = $path; // Use this in case URL path is different than physical path. For example: $urlpath = '/admin/contentbox/uploads/';


//Get customvalue  
$customvalue = $_REQUEST['hidcustomval']; 


//Check folder. Create if not exist
$pagefolder = $path;
if (!file_exists($pagefolder)) {
	mkdir($pagefolder, 0777);
} 


//Optional: If using customvalue to specify upload folder
if ($customvalue!='') {
  $pagefolder = $path . $customvalue. '/';
  if (!file_exists($pagefolder)) {
	  mkdir($pagefolder, 0777);
  } 
  $urlpath = $urlpath . $customvalue. '/';
}


$filename = basename($_FILES["fileCover"]["name"]);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["fileCover"]["tmp_name"]);
if($check !== false) {
	//echo "File is an image - " . $check["mime"];
	$uploadOk = 1;
} else {
	echo "<html><body onload=\"parent.applyBoxImage('','File is not an image.');\"></body></html>";
	exit();
	$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp" ) {
	echo "<html><body onload=\"parent.applyBoxImage('','Sorry, only JPG, JPEG, PNG & GIF files are allowed.');\"></body></html>";
	exit();
    $uploadOk = 0;
}

if ($uploadOk == 0) {
	echo "<html><body onload=\"parent.applyBoxImage('','Sorry, your file was not uploaded.');\"></body></html>";
	exit();
} else {

    $random = base_convert(rand(),10,36) . date("his");
	$pic_type = strtolower(strrchr($_FILES["fileCover"]['name'],"."));
	$pic_name = "original$random$pic_type";

    if($pic_type == ".webp") {

        //Save as is
        move_uploaded_file($_FILES["fileCover"]['tmp_name'], $pagefolder . "$random$pic_type");
        
    } else {

        move_uploaded_file($_FILES["fileCover"]['tmp_name'], $pagefolder . $pic_name);
		
		//added 10/26/2022 to fix image rotation
		if($imageFileType == "jpeg" || $imageFileType=="jpg") {
			fix_jpeg_rotation($pagefolder . $pic_name);
		}
		//---------------------------------------

        //Save image
        if (true !== ($pic_error = @image_resize($pagefolder . $pic_name, $pagefolder . "$random$pic_type", 1600, 1600))) { //Resize image to max 1600x1600 to safe size
            echo "<html><body onload=\"parent.applyBoxImage('','".$pic_error."');\"></body></html>";		
            exit();
        }

        unlink($pagefolder . $pic_name); //delete original
    }
  
  
    //Replace image src with the new saved file
    echo "<html><body onload=\"parent.applyBoxImage('" . $urlpath . "$random$pic_type')\"></body></html>";
}


function image_resize($src, $dst, $width, $height, $crop=0){

  //if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
  list($w, $h) = getimagesize($src);

  $type = strtolower(substr(strrchr($src,"."),1));
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($src); break;
    case 'gif': $img = imagecreatefromgif($src); break;
    case 'jpg': $img = imagecreatefromjpeg($src); break;
    case 'png': $img = imagecreatefrompng($src); break;
    default : return "Unsupported picture type!";
  }
  if($w < $width or $h < $height) {
	$width = 1629;
	$height = 850;
  }
  if($w < $width or $h < $height) {
	$width = 1533;
	$height = 800;
  }
  if($w < $width or $h < $height) {
	$width = 1438;
	$height = 750;
  }
  if($w < $width or $h < $height) {
	$width = 1380;
	$height = 720;
  }
  if($w < $width or $h < $height) {
	$width = 1342;
	$height = 700;
  }
  if($w < $width or $h < $height) {
	$width = 1246;
	$height = 650;
  }
  if($w < $width or $h < $height) {
	$width = 1150;
	$height = 600;
  }
  if($w < $width or $h < $height) {
	$width = 1054;
	$height = 550;
  }
  if($w < $width or $h < $height) {
	$width = 958;
	$height = 500;
  }
  if($w < $width or $h < $height) {
	$width = 863;
	$height = 450;
  }
  if($w < $width or $h < $height) {
	$width = 767;
	$height = 400;
  }
  if($w < $width or $h < $height) {
	$width = 671;
	$height = 350;
  }
  if($w < $width or $h < $height) {
	$width = 575;
	$height = 300;
  }
  if($w < $width or $h < $height) {
	return "Picture is too small. Minimum dimension: 575 x 350 pixels.";
  }
  
  // resize
  if($crop){
    $ratio = max($width/$w, $height/$h);
    $h = $height / $ratio;
    $x = ($w - $width / $ratio) / 2;
    $w = $width / $ratio;
  }
  else{
    $ratio = min($width/$w, $height/$h);
    $width = $w * $ratio;
    $height = $h * $ratio;
    $x = 0;
  }

  $new = imagecreatetruecolor($width, $height);

  // preserve transparency
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

  switch($type){
    case 'bmp': imagewbmp($new, $dst); break;
    case 'gif': imagegif($new, $dst); break;
    case 'jpg': imagejpeg($new, $dst); break;
    case 'png': imagepng($new, $dst); break;
  }
  return true;
}
?>
