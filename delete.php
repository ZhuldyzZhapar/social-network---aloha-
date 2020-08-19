<?php
session_start();
$user_id = $_SESSION['user_id'];
if(isset($_GET['delete'])){
	$image_name = $_GET['delete'];
	$src = 'images/' . $image_name;
	$album = $_SESSION['album'];
	unset($_SESSION['album']);
	unlink($src);
	try{
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("SELECT * FROM images WHERE user_id = :user_id AND album = :album;");
		$stmt1->bindParam(':user_id', $user_id);
		$stmt1->bindParam(':album', $album);
		$stmt1->execute();
		$album_images = $stmt1->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error1: </p>" . $e->getMessage();
	}
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("DELETE FROM images WHERE user_id = :user_id AND image_name = :image_name AND album = :album;");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':image_name', $image_name);
		$stmt->bindParam(':album', $album);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error1: </p>" . $e->getMessage();
	}
	if(count($album_images)===1){ // if album becomes empty, it is necessary for album to stay still with "no images"
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("INSERT INTO images(user_id, album) VALUES (:user_id, :album);");
		$stmt1->bindParam(':user_id', $user_id);
		$stmt1->bindParam(':album', $album);
		$stmt1->execute();
	}
	$href = 'images.php?album=' . $album;
	?>
	<script>window.location.replace("<?php echo $href;?>")</script>
	<?php
}elseif(isset($_GET['deleteAlbum'])){
	$album = $_GET['deleteAlbum'];
	$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$stmt1 = $connect1->prepare("SELECT * FROM images WHERE user_id = :user_id AND album = :album;");
	$stmt1->bindParam(':user_id', $user_id);
	$stmt1->bindParam(':album', $album);
	$stmt1->execute();
	$album_images = $stmt1->fetchAll();
	foreach ($album_images as $value) {
		$src = 'images/' . $value['image_name'];
		unlink($src);
	}
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("DELETE FROM images WHERE user_id = :user_id AND album = :album;");
	$stmt->bindParam(':user_id', $user_id);
	$stmt->bindParam(':album', $album);
	$stmt->execute();
		echo "<script>window.location.replace('albums.php?what=images')</script>";
}elseif(isset($_GET['deleteAlbumVid'])){
	$album = $_GET['deleteAlbumVid'];
	try{
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("SELECT * FROM video WHERE user_id = :user_id AND album = :album;");
		$stmt1->bindParam(':user_id', $user_id);
		$stmt1->bindParam(':album', $album);
		$stmt1->execute();
		$album_videos = $stmt1->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error1: </p>" . $e->getMessage();
	}
	foreach ($album_videos as $value) {
		$src = 'video/' . $value['video'];
		unlink($src);
	}
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("DELETE FROM video WHERE user_id = :user_id AND album = :album;");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':album', $album);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error1: </p>" . $e->getMessage();
	}
	echo "<script>window.location.replace('albums.php?what=videos')</script>";
}elseif(isset($_GET['deleteVid'])){
	$video = $_GET['deleteVid'];
	$src = 'video/' . $video;
	$album = $_SESSION['album'];
	unset($_SESSION['album']);
	unlink($src);
	try{
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("SELECT * FROM video WHERE user_id = :user_id AND album = :album;");
		$stmt1->bindParam(':user_id', $user_id);
		$stmt1->bindParam(':album', $album);
		$stmt1->execute();
		$album_videos = $stmt1->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error1: </p>" . $e->getMessage();
	}
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("DELETE FROM video WHERE user_id = :user_id AND video = :video AND album = :album;");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':video', $video);
		$stmt->bindParam(':album', $album);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error1: </p>" . $e->getMessage();
	}
	if(count($album_videos)===1){
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("INSERT INTO video(user_id, album) VALUES (:user_id, :album);");
		$stmt1->bindParam(':user_id', $user_id);
		$stmt1->bindParam(':album', $album);
		$stmt1->execute();
	}
	$href = 'videos.php?album=' . $album;
	?>
	<script>window.location.replace("<?php echo $href;?>")</script>
	<?php
}elseif(isset($_GET['profile'])){
	$name = 'Profile images';
	$profile = 'profile';
	$zero = 0;
	try{
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("SELECT * FROM images WHERE user_id = :user_id AND album = :album;");
		$stmt1->bindParam(':user_id', $user_id);
		$stmt1->bindParam(':album', $name);
		$stmt1->execute();
		$profile_img = $stmt1->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error1: </p>" . $e->getMessage();
	}
	$src = 'images/' . $profile_img[0]['image_name'];
	unlink($src);
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("UPDATE images 
								SET image_name = :image_name
								WHERE user_id = :user_id AND album = :album;");
		$stmt->bindParam(':image_name', $profile);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':album', $name);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error0: </p>" . $e->getMessage();
	}
	try{
		$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt2 = $connect2->prepare("UPDATE user_information 
									SET image_status = :image_status
									WHERE user_id = :user_id;");
		$stmt2->bindParam(':image_status', $zero);
		$stmt2->bindParam(':user_id', $user_id);
		$stmt2->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error2: </p>" . $e->getMessage();
	}
	echo "<script>window.location.replace('my_profile.php')</script>";
}elseif(isset($_POST['new_album_name'])){
	$new_album_name = $_POST['new_album_name'];
	$old_album_name = $_SESSION['old_album_name'];
	unset($_SESSION['old_album_name']);
	try{
		$connect0 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt0 = $connect0->prepare("UPDATE images 
									SET album = :album
									WHERE album = :album_1 AND user_id=:user_id;");
		$stmt0->bindParam(':user_id', $user_id);
		$stmt0->bindParam(':album', $new_album_name);
		$stmt0->bindParam(':album_1', $old_album_name);
		$stmt0->execute();
	}
	catch(PDOException $e){
		echo "<p>Error0: </p>" . $e->getMessage();
	}
	$link = 'images.php?album=' . $new_album_name;?>
	<script>window.location.replace('<?php echo $link;?>')</script><?php
}elseif(isset($_POST['new_album_name_vid'])){
	$new_album_name = $_POST['new_album_name_vid'];
	$old_album_name = $_SESSION['old_album_name'];
	unset($_SESSION['old_album_name']);
	try{
		$connect0 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt0 = $connect0->prepare("UPDATE video 
									SET album = :album
									WHERE album = :album_1 AND user_id=:user_id;");
		$stmt0->bindParam(':user_id', $user_id);
		$stmt0->bindParam(':album', $new_album_name);
		$stmt0->bindParam(':album_1', $old_album_name);
		$stmt0->execute();
	}
	catch(PDOException $e){
		echo "<p>Error0: </p>" . $e->getMessage();
	}
	$link = 'videos.php?album=' . $new_album_name;
	?>
	<script>window.location.replace('<?php echo $link;?>')</script>
	<?php 
}