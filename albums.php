<?php
session_start();
$user_id = $_SESSION['user_id'];
$what = $_GET['what'];
require_once 'user_search.php';
require_once 'weather.php';
if($what==='images'){
	if(isset($_POST['image_album_name'])){
		$new_album_name = $_POST['image_album_name'];
		try{
			$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt1 = $connect1->prepare("INSERT INTO images(user_id, album) VALUES (:user_id, :album);");
			$stmt1->bindParam(':user_id', $user_id);
			$stmt1->bindParam(':album', $new_album_name);
			$stmt1->execute();
		}
		catch(PDOException $e) {
			echo "<p>ErrorYO: </p>" . $e->getMessage();
		}
		unset($_POST['image_album_name']);
	}
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("SELECT DISTINCT album FROM images WHERE user_id=:user_id ORDER BY album ASC");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		$albums = $stmt->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	$h2 = 'Image albums';
}elseif($what === 'videos'){
	if(isset($_POST['video_album_name'])){
		$new_album_name = $_POST['video_album_name'];
		try{
			$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt1 = $connect1->prepare("INSERT INTO video(user_id, album) VALUES (:user_id, :album);");
			$stmt1->bindParam(':user_id', $user_id);
			$stmt1->bindParam(':album', $new_album_name);
			$stmt1->execute();
		}
		catch(PDOException $e) {
			echo "<p>ErrorYO: </p>" . $e->getMessage();
		}
		unset($_POST['video_album_name']);
	}
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("SELECT DISTINCT album FROM video WHERE user_id=:user_id ORDER BY album ASC");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		$albums = $stmt->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	$h2 = 'Video albums';
}
?>
<html>
<head><?php if($what==='images'){?>
	<title>Image albums | <?php echo $_SESSION['username'];?></title><?php }elseif($what==='videos'){?>
	<title>Video albums | <?php echo $_SESSION['username'];?></title><?php } ?>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type = "text/javascript" src = "/javascript/prototype.js"></script>
    <script type = "text/javascript" src = "/javascript/scriptaculous.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Baloo+2" rel="stylesheet" type="text/css">
</head>
<body>
	<header>
	<nav class="navbar">
		<ul>
		<li id='logo'>Aloha</li>
		<li style='margin-top: -10px;'><p class='search' id='search' onclick="show_search('block')">Find User</p></li>
		</ul>
	</nav>
	</header>
	<div class='navigation'>
		<p><a href="my_profile.php">Profile</a></p>
		<p><a href="main_page.php">Main page</a></p>
		<p><a href="friends.php">Friends</a></p>
		<p><a href="chats.php">Chats</a></p>
		<p><a href="albums.php?what=images">Images</a></p>
		<p><a href="albums.php?what=videos">Videos</a></p>
		<p><a href="email_us.php" style='color: navy !important;'>Email us</a></p>
		<p><a href="index.php?logout=yes" style='color: navy !important;'>Log out</a></p>
		<a target="_blank" href="https://nochi.com/weather/almaty-10297"><img src="https://w.bookcdn.com/weather/picture/11_10297_1_20_ffffff_118_2373ca_333333_ffffff_1_ffffff_333333_0_6.png?scode=124&domid=589&anc_id=54919"  alt="booked.net"/></a>
		<br><br>
		<a target="_blank" href="https://nochi.com/weather/astana-w1465"><img src="https://w.bookcdn.com/weather/picture/11_w1465_1_20_ffffff_118_2373ca_333333_ffffff_1_ffffff_333333_0_6.png?scode=124&domid=589&anc_id=54919"  alt="booked.net"/></a>
	</div>
	<h2 id='albums_h2'><?php echo $h2;?></h2>
	<?php 
	if($what==='images'){?>
	<p class='new_album' id='new_image_album'>New album</p>
	<?php
	foreach ($albums as $value){
		$album_name = $value['album'];
		$null = NULL;
		try{
			$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt1 = $connect1->prepare("SELECT image_name, tag_person FROM images WHERE user_id = :user_id AND album = :album");
			$stmt1->bindParam(':user_id', $user_id);
			$stmt1->bindParam(':album', $album_name);
			$stmt1->execute();
			$album_images = $stmt1->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error1: </p>" . $e->getMessage();
		}
		if($album_images[0]['image_name']==='no image' or $album_images[0]['image_name']==='profile'){
		$href = 'images.php?album=' . $album_name;
		$image_number = 0;?>
		<div class='albums'>
		<div class='album'><a href='<?php echo $href;?>'>
			<div class='album_cover_div' style='background-color: powderblue;'><img src='images/default_image.png' width=198 height=198 alt='No image'/></div>
			<center><p><? echo $album_name;?> | <?php echo $image_number?> img</p></center></a>
		</div>
		</div><?php
		}else{
		$image_number = count($album_images);
		$href = 'images.php?album=' . $album_name;
		$link = 'images/' . $album_images[$image_number-1]['image_name'];
		# to control the size of the album cover we use the overflow:hidden parameter
		# we get information about the width and the height of the image and according to what is larger: width or height we add parameter to the album cover 
		list($width, $height, $type, $attr) = getimagesize($link);
		$size = '';
		if($width > $height){
			$size = 'height=200';
		}elseif($height > $width){
			$size = 'width=200';
		}elseif($width === $height){
			$size = 'width=200 height=200';
		}?>
		<div class='albums'>
		<div class='album'><a href='<?php echo $href;?>'>
			<div class='album_cover_div'><img src='<?php echo $link?>' <?php echo $size;?> alt='No image'/></div>
			<center><p><? echo $album_name;?> | <?php echo $image_number?> img</p></center></a>
		</div>
		</div>
	<?php } }?>
	<center>
	<div id='window' style='height: 260px !important;'>
		<img id='close' src="images/close.png" width="15" height="15" />
		<p style='font-size: 20px; font-family: Raleway; color: indigo;'><strong>Create new album</strong></p><br>
		<form method="POST" action="albums.php?what=images">
			<input class='input' type='text' name='image_album_name' placeholder="Enter name of new album" style='width: 250px !important;' required><br><br>
			<button class='create_album_button' type='submit' name='submit'>Create album</button>
		</form>
	</div>
	</center>
	<?php }elseif($what==='videos'){?>
	<p class='new_album' id='new_image_album'>New album</p>
	<?php
	foreach ($albums as $value){
		$album_name = $value['album'];
		$null = NULL;
		try{
			$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt1 = $connect1->prepare("SELECT video, tag_person FROM video WHERE user_id = :user_id AND album = :album");
			$stmt1->bindParam(':user_id', $user_id);
			$stmt1->bindParam(':album', $album_name);
			$stmt1->execute();
			$album_videos = $stmt1->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error1: </p>" . $e->getMessage();
		}
		if($album_videos[0]['video']==='no video'){
		$href = 'videos.php?album=' . $album_name;
		$video_number = 0;?>
		<div class='albums'>
		<div class='album'><a href='<?php echo $href;?>'>
			<div class='album_cover_div' style='background-color: powderblue;'><img src='images/default_image.png' width=198 height=198 alt='No video'/></div>
			<center><p><? echo $album_name;?> | <?php echo $video_number?> vid</p></center></a>
		</div>
		</div><?php
		continue;
		}else{
		$video_number = count($album_videos);
		$href = 'videos.php?album=' . $album_name;
		$link = 'video/' . $album_videos[$video_number-1]['video'];
		# to control the size of the album cover we use the overflow:hidden parameter
		# we get information about the width and the height of the video and according to what is larger: width or height we add parameter to the album cover 
		include_once('getID3-master/getid3/getid3.php');
		$getID3 = new getID3;
		$video_file = $getID3->analyze($link);
		$duration_string = $video_file['playtime_string'];
		$format = $video_file['fileformat'];
		$resolution_x = $video_file['video']['resolution_x'];
		$resolution_y = $video_file['video']['resolution_y'];
		$size = '';
		if ($resolution_x > $resolution_y){
			$size = 'height=200';
		}elseif($resolution_y > $resolution_x){
			$size = 'width=200';
		}elseif($resolution_y === $resolution_x){
			$size = 'width=200 height=200';
		}
		}?>
		<div class='albums'>
		<div class='album'><a href='<?php echo $href;?>'>
			<div class='album_cover_div'><video <?php echo $size;?>><source src='<?php echo $link?>' alt='No video'/></video></div>
			<center><p><? echo $album_name;?> | <?php echo $video_number;?> vid</p></center></a>
		</div>
		</div>
	<?php } }?>
	<center>
	<div id='window'>
		<img id='close' src="images/close.png" width="15" height="15" />
		<p style='font-size: 20px; font-family: Raleway; color: indigo;'><strong>Create new album</strong></p><br>
		<form method="POST" action="albums.php?what=videos">
			<input class='input' type='text' name='video_album_name' placeholder="Enter name of new album" style='width: 250px !important;' required><br><br>
			<button class='create_album_button' type='submit' name='submit'>Create album</button>
		</form>
	</div>
	</center>
	</div>
	<div id='gray'></div>
	<div id='gray_search'></div>
	<center>
	<div id='window_search'>
		<?php if($msg1 !== ''){
			$placeholder = "placeholder = '". $msg1 . "'";
		}else{
			$placeholder = "placeholder = 'Search users...'";
		}?>
			<form method="post">
				<input class='search_text' name="users" <?php echo $placeholder?>>
				<input type="submit" name="search_user" value="Search" class="button_1">
				<input type='hidden' name='is_search' value='yes'>
			</form>
	<!-- here I display users -->
	<?php  foreach($users as $user): ?>
		<a href="user_profile.php?id=<?php echo $user['user_id']; ?>">
			<div class="each-user">
				<div class="user-avatar">
					<?php
					$image_status = $user['image_status'];
					$some_user_id = $user['user_id'];
					if ($image_status === '0'){
						$src = 'images/profile_default.jpg';
						$href = 'user_profile.php?id=' . $some_user_id;
						$size = 'width=80 height=80';
					}else{
						$params = ["%{$some_user_id}%"];
						try{
							$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
							$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$stmt2 = $connect2->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
							$stmt2->bindParam(':user_id', $some_user_id);
							$stmt2->bindParam(':image_name1', $params[0]);
							$stmt2->execute();
							$data2 = $stmt2->fetchAll();
						}
						catch(PDOException $e) {
							echo "<p>Error: </p>" . $e->getMessage();
						}
						$image_name = $data2[0]['image_name'];
						$src = 'images/' . $image_name;
						$href = 'user_profile.php?id=' . $some_user_id;
						list($width, $height, $type, $attr) = getimagesize($src);
						$size = '';
						if($width > $height){
						 	$size = 'height=80';
						}elseif($height > $width){
						 	$size = 'width=80';
						}elseif($width === $height){
							$size = 'width=80 height=80';
					 	}
					}?><div class='image_div_pro'>
					<img <?php echo $size?> src="<?php echo $src?>"></div>
				</div>
				<div class="user-information">
					<div>
						<strong><?php echo $user['name'];?> <?php echo $user['surname']; ?></strong>
					</div>
					<div>
						Birthday: <?php echo $user['birthday']; ?>
					</div>
					<div>
					<?php 
					if($user['city']!=='' and $user['city']!==NULL){?>
						City: <?php echo $user['city']; 
					}?>
					</div>
					<div>
					<?php 
					if($user['job'] !== '' and $user['job'] !== NULL){?>
						Job: <?php echo $user['job']; 
					}?>
					</div>
				</div>
			</div>
		</a>
	<?php endforeach; ?>
	</div>
	</center>
	<script type="text/javascript">
		let is_search = "<?php echo $_POST['is_search'];?>";
		if(is_search === 'yes'){
			show_search('block');
		}
		$('search').onclick = function () {show_search('block')};
		document.getElementById('gray_search').onclick = function () {show_search('none')};
		function show_search(state){
			document.getElementById('window_search').style.display = state;
			document.getElementById('gray_search').style.display = state;
		}
	</script>
	<script type="text/javascript">
		$('new_image_album').onclick = function () {show('block')};
		$('gray').onclick = function () {show('none')};
		$('close').onclick = function () {show('none')};
		function show(state){
			$('window').style.display = state;
			$('gray').style.display = state;
		}
	</script>
	<?php
	unset($_POST['is_search']);?>
</body>
</html>