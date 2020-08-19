<?php
session_start();
$user_id = $_SESSION['user_id'];

if($_GET['what'] === 'profile_image'){
$params = ["%{$user_id}%"];
// we want to know if user had profile image or not
try{
	$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt2 = $connect2->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
	$stmt2->bindParam(':user_id', $user_id);
	$stmt2->bindParam(':image_name1', $params[0]);
	$stmt2->execute();
	$data2 = $stmt2->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error: </p>" . $e->getMessage();
}
$image_name = $data2[0]['image_name'];
$file = $_FILES['file'];
$file_type = $file['type'];
$file_name = $file['name'];
$file_tmp_name = $file['tmp_name'];
$file_error = $file['error'];
$file_size = $file['type'];
$is_image = substr($file_type, 0, 5);
$array = explode('/', $file_type);
$image_type = strtolower(end($array));
if($is_image === 'image'){
	if ($file_error === 0) {
		if($fileSize < 1000000){
			$file_new_name = 'profile_'. $user_id . '.' . $image_type;
			$destination = 'images/' . $file_new_name;
			move_uploaded_file($file_tmp_name, $destination);
			$profile = 'profile';
			try{
				$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $connect->prepare("UPDATE images SET user_id = :user_id, image_name = :image_name WHERE image_name=:image_name1 AND user_id = :user_id1;");
				$stmt->bindParam(':user_id', $user_id);
				$stmt->bindParam(':user_id1', $user_id);
				$stmt->bindParam(':image_name', $file_new_name);
				$stmt->bindParam(':image_name1', $profile);
				$stmt->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error0: </p>" . $e->getMessage();
			}
			$one = '1';
			try{
				$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt1 = $connect1->prepare("UPDATE user_information SET image_status = :image_status WHERE user_id=:user_id;");
				$stmt1->bindParam(':user_id', $user_id);
				$stmt1->bindParam(':image_status', $one);
				$stmt1->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error1: </p>" . $e->getMessage();
			}
			$_SESSION['message'] = '<strong>Uploading success</strong><br>New profile image successfully uploaded!';
			$_SESSION['where'] = 'upload';
		}else{
			$_SESSION['message'] = '<strong>Uploading error</strong><br>Size of an image is too big.';
			$_SESSION['where'] = 'upload';
		}
	}else{
		$_SESSION['message'] = '<strong>Uploading error</strong><br>Error uploading the image.';
		$_SESSION['where'] = 'upload';
	}
}else{
	$_SESSION['message'] = '<strong>Uploading error</strong><br>You can upload only image.';
	$_SESSION['where'] = 'upload';
}
echo "<script> window.location.replace('my_profile.php')</script>";
}elseif($_GET['what'] === 'image'){
$image_name = $data2[0]['image_name'];
$file = $_FILES['file'];
$file_type = $file['type'];
$file_name = $file['name'];
$file_tmp_name = $file['tmp_name'];
$file_error = $file['error'];
$file_size = $file['type'];
$is_image = substr($file_type, 0, 5);
$array = explode('/', $file_type);
$image_type = strtolower(end($array));
$album_name = $_SESSION['album'];
unset($_SESSION['album']);
$no_image = 'no image';
if($is_image === 'image'){
	if ($file_error === 0) {
		if($fileSize < 1000000){
			if(trim($_POST['tag_person'])!== ''){
			$tag_person = $_POST['tag_person'];}
			$file_new_name = uniqid('img_', true) . '.' . $image_type;
			$destination = 'images/' . $file_new_name;
			move_uploaded_file($file_tmp_name, $destination);
			try{
				$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $connect->prepare("INSERT INTO images(user_id, image_name, album, tag_person) 
										VALUES (:user_id, :image_name, :album, :tag_person) ;");
				$stmt->bindParam(':user_id', $user_id);
				$stmt->bindParam(':image_name', $file_new_name);
				$stmt->bindParam(':album', $album_name);
				$stmt->bindParam(':tag_person', $tag_person);
				$stmt->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			try{
				$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt1 = $connect1->prepare("DELETE FROM images WHERE user_id = :user_id AND album = :album AND image_name = :image_name;");
				$stmt1->bindParam(':user_id', $user_id);
				$stmt1->bindParam(':album', $album_name);
				$stmt1->bindParam(':image_name', $no_image);
				$stmt1->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error1: </p>" . $e->getMessage();
			}
		}else{
			$_SESSION['message'] = '<br><strong>Uploading error</strong><br><br><Size of an image is too big.';
			$_SESSION['where'] = 'upload';
		}
	}else{
		$_SESSION['message'] = '<br><strong>Uploading error</strong><br><br>Error uploading the image.';
		$_SESSION['where'] = 'upload';
	}
}else{
	$_SESSION['message'] = '<br><strong>Uploading error</strong><br><br>You can upload only images.';
	$_SESSION['where'] = 'upload';
}
$href = 'Location: images.php?album=' . $album_name;
header($href);
exit;
}elseif($_GET['what'] === 'video'){
$album_name = $_SESSION['album'];
unset($_SESSION['album']);
$file = $_FILES['file'];
$file_type = $file['type'];
$file_name = $file['name'];
$file_tmp_name = $file['tmp_name'];
$file_error = $file['error'];
$file_size = $file['size'];
$is_video = substr($file_type, 0, 5);
$array = explode('/', $file_type);
$video_type = strtolower(end($array));
$no_video = 'no video';
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);
if(!empty($file)){
if($is_video === 'video'){
	if ($file_error === 0) {
		if($fileSize < 100000000){ // 100 millions bytes = 100 MB
			if(trim($_POST['tag_person'])!== ''){
			$tag_person = $_POST['tag_person'];}
			$file_new_name = uniqid('vid_', true) . '.' . $video_type;
			$destination = 'video/' . $file_new_name;
			move_uploaded_file($file_tmp_name, $destination);
			try{
				$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $connect->prepare("INSERT INTO video(user_id, video, album, tag_person) 
										VALUES (:user_id, :video, :album, :tag_person) ;");
				$stmt->bindParam(':user_id', $user_id);
				$stmt->bindParam(':video', $file_new_name);
				$stmt->bindParam(':album', $album_name);
				$stmt->bindParam(':tag_person', $tag_person);
				$stmt->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			try{
				$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt1 = $connect1->prepare("DELETE FROM video WHERE user_id = :user_id AND album = :album AND video = :video;");
				$stmt1->bindParam(':user_id', $user_id);
				$stmt1->bindParam(':album', $album_name);
				$stmt1->bindParam(':video', $no_video);
				$stmt1->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error1: </p>" . $e->getMessage();
			}
		}else{
			$_SESSION['message'] = '<br><strong>Uploading error</strong><br><br>Size of a video is too big.<br>Max size = 100 MB.';
			$_SESSION['where'] = 'upload';
		}
	}else{
		$_SESSION['message'] = '<br><strong>Uploading error</strong><br><br>Error uploading the video.';
		$_SESSION['where'] = 'upload';
	}
}else{
	$_SESSION['message'] = '<br><strong>Uploading error</strong><br><br>You can upload only video.';
	$_SESSION['where'] = 'upload';
}
}else{
	$_SESSION['message'] = '<br><strong>Uploading error</strong><br><br>Size of a video is too big.<br>Max size = 100 MB.';
	$_SESSION['where'] = 'upload';
}
$href = 'Location: videos.php?album=' . $album_name;
header($href);
exit;
}