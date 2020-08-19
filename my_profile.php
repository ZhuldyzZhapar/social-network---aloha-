<?php 
session_start();
$user_id = $_SESSION['user_id'];
require_once 'user_search.php';
require_once 'weather.php';
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$one = '1';
$zero = '0';
require_once 'friends_array.php';

try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect ->prepare("SELECT * FROM user_information WHERE user_id = :user_id;");
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();
	$data = $stmt->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error: </p>" . $e->getMessage();
}

$name = $data[0]['name'];
$surname = $data[0]['surname'];
$gender = $data[0]['gender'];
$birthday = $data[0]['birthday'];
$country = $data[0]['country'];
$city = $data[0]['city'];
$university = $data[0]['university'];
$job = $data[0]['job'];
$family_status = $data[0]['family_status'];
$favourite_movies = $data[0]['favourite_movies'];
$favourite_books = $data[0]['favourite_books'];
$hobbies = $data[0]['hobbies'];
$about_me = $data[0]['about_me'];
$email = $data[0]['email'];
$phone_number = $data[0]['phone'];
$email = $data[0]['email'];
$phone = $data[0]['phone'];
$image_status = $data[0]['image_status'];

?>
<html>
<head>
	<title>My profile | <?php echo $_SESSION['username'];?></title>
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
	<div style='min-height: 100%;'>
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
	<center>
		<div id='window'>
		<img id='close' src="images/close.png" width="15" height="15" />
		<p><strong>Upload a new profile image</strong></p>
		<p>It would be a good idea if you'll upload your real photo, <br> so that friends could easily find you.</p>
		<p>Please choose an image of <i>'.jpg', '.jpeg', '.png'</i> type.</p>
		<form action='upload.php?what=profile_image' method='POST' enctype="multipart/form-data">
			<input type='file' name='file'>
			<button type='submit' name='submit'>Upload image</button>
		</form>
	</div>
	</center>
	<!-- if there is an message after updating profile information or updating profile image-->
	<?php if(isset($_SESSION['where'])){?>
	<center>
		<div id='window1'>
		<img id='close1' src="images/close.png" width="15" height="15" />
		<p id='message'><?php echo $message; unset($_SESSION['message']); ?></p><?php 
		if(isset($_SESSION['message_email']) or isset($_SESSION['message_phone']) or isset($_SESSION['message_password'])){?>
		<p><strong>Profile information updating error</strong></p>
		<p id='message_email'><?php echo $_SESSION['message_email']; unset($_SESSION['message_email']); ?></p>
		<p id='message_phone'><?php echo $_SESSION['message_phone']; unset($_SESSION['message_phone']); ?></p>
		<p id='message_password'><?php echo $_SESSION['message_password']; unset($_SESSION['message_password']);?></p>
		<?php } ?>
	</div>
	</center>
	<div id='gray1'></div>
	<?php } ?>
	<!--  -->
	<div class='profile_image' style='overflow: hidden;'>
		<div id='profile_image_hover'>
			<?php if($image_status === '1'){?>
			<a href='delete.php?profile=yes' style='text-decoration: none;'>
			<p id='delete_profile_image' style='cursor: pointer;'>Delete profile image</p></a><?php } ?>
			<p id='change_image'>Change profile image</p>
		</div><?php
		if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$size = 'width=220 height=220';
		}else{
			$params = ["%{$user_id}%"];
			try{
				$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $connect->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
				$stmt->bindParam(':user_id', $user_id);
				$stmt->bindParam(':image_name1', $params[0]);
				$stmt->execute();
				$data = $stmt->fetchAll();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			$image_name = $data[0]['image_name'];
			$src = 'images/' . $image_name;
			list($width, $height, $type, $attr) = getimagesize($src);
			$size = '';
			if($width > $height){
				$size = 'height=220';
			}elseif($height > $width){
				$size = 'width=220';
			}elseif($width === $height){
				$size = 'width=220 height=220';
			}
		} ?>
		<div style='overflow: hidden; width: 220px; height: 220px; border: 4px solid white;'>
		<img src='<?php echo $src;?>' <?php echo $size;?> onmouseover="hover('block')" onmouseout="hover('none')"></div>
		<br><h2><a href='edit_profile.php' id='edit_profile'>Edit profile information</a></h2><br>
	</div>
	<div id='gray'></div>
	<div class='profile_info'>
		<h2 style='color: olivedrab;'><?php echo $name . ' ' . $surname;?></h2><?php 
		echo "<p class='characteristics'><strong>Born in:</strong></p>";
		echo "<p class='info'>" . $birthday . "</p>";
		if($gender!==null){
		echo "<p class='characteristics'><strong>Gender:</strong></p>";
		echo "<p class='info'>" . $gender . "</p>";}
		if($family_status!==null){
		echo "<p class='characteristics'><strong>Family status:</strong></p>";
		echo "<p class='info'>" . $family_status . "</p>";}
		if($country!==null && $city!==null){
			echo "<p class='characteristics'><strong>Lives in:</strong></p>";
			echo "<p class='info'>" . $country . ', ' . $city . "</p>";
		}elseif($country!==null && $city===null){
			echo "<p class='characteristics'><strong>Lives in:</strong></p>";
			echo "<p class='info'>" . $country . "</p>";
		}elseif($country===null && $city!==null){
			echo "<p class='characteristics'><strong>Lives in:</strong></p>";
			echo "<p class='info'>" . $city . "</p>";}
		if($university!==null){
		echo "<p class='characteristics'><strong>University:</strong></p>";
		echo "<p class='info'>" . $university . "</p>";}
		if($job!==null){
		echo "<p class='characteristics'><strong>Works as:</strong></p>";
		echo "<p class='info'>" . $job . "</p>";}
		if($phone!==null){
		echo "<p class='characteristics'><strong>Phone number:</strong></p>";
		echo "<p class='info'>" . $phone . "</p>";}
		if($email!==null){
		echo "<p class='characteristics'><strong>Email:</strong></p>";
		echo "<p class='info'>" . $email . "</p>";}
		if($about_me!==null){
		echo "<p class='characteristics'><strong>About me:</strong><br></p>";
		echo "<p class='info'>" . $about_me . "</p>";} 
		if($hobbies!==null){
		echo "<p class='characteristics'><strong>Hobbies:</strong><br></p>";
		echo "<p id='hobbies' class='info'>" . $hobbies . "</p>";} 
		if($favourite_books!==null){
		echo "<p class='characteristics'><strong>Favourite books:</strong><br></p>";
		echo "<p id='favourite_books' class='info'>" . $favourite_books . "</p>";} 
		if($favourite_movies!==null){
		echo "<p class='characteristics'><strong>Favourite movies:</strong><br></p>";
		echo "<p class='info'>" . $favourite_movies . "</p>";} 
		?></p>
	</div></div>
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
		$('change_image').onclick = function () {show('block')};
		$('gray').onclick = function () {show('none')};
		$('close').onclick = function () {show('none')};
		function show(state){
			$('window').style.display = state;
			$('gray').style.display = state;
		}
		let variable = $('gray1');
		if(typeof(variable) != "undefined" && variable !== null){
			$('gray1').onclick = function () {show1('none')};
			$('close1').onclick = function () {show1('none')};
			function show1(state){
				$('window1').style.display = state;
				$('gray1').style.display = state;
			}
		}
		function hover(state){
			$('profile_image_hover').style.display = state;
		}
	<?php 
	unset($_SESSION['where']);?>
	</script>
	<?php unset($_POST['is_search']);?>
</body>
</html>