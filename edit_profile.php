<?php
session_start();
$user_id = $_SESSION['user_id'];
require_once 'user_search.php';
require_once 'weather.php';
try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect ->prepare("SELECT * FROM user_information WHERE user_id = :user_id;");
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();
	$data = $stmt->fetchAll();
}
catch(PDOException $e){
	echo "<p>Error: </p>" . $e->getMessage();
}

if(isset($data[0]['phone']) and isset($data[0]['email'])){
	$phone_email = 'phone_email';
}elseif(isset($data[0]['phone']) and !isset($data[0]['email'])){
	$phone_email = 'phone';
}elseif(isset($data[0]['email']) and !isset($data[0]['phone'])){
	$phone_email = 'email';
}

// make two windows about profile image changes and profile information changes
$gender = ['Male', 'Female', 'Other'];
// links that website goes to when user click delete button
$links = array("gender" => "update.php?delete=gender", "family_status" => "update.php?delete=family_status",
			"email" => "update.php?delete=email", "phone" => "update.php?delete=phone",
			"country" => "update.php?delete=country", "city" => "update.php?delete=city",
			"university" => "update.php?delete=university", "job" => "update.php?delete=job",
			"about_me" => "update.php?delete=about_me", "hobbies" => "update.php?delete=hobbies", 
			"favourite_books" => "update.php?delete=favourite_books", 
			"favourite_movies" => "update.php?delete=favourite_movies");
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit profile information || <?php echo $_SESSION['username'];?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Baloo+Tamma+2" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
</head>
<body style='height: 1000px;'>
	<header>
	<nav class="navbar">
		<ul>
		<li id='logo'>Aloha</li>
		<li style='margin-top: -10px;'><p class='search' id='search' onclick="show_search('block')">Find User</p></li>
		</ul>
	</nav>
	</header>
	<div class='navigation' style='min-height: 1700px;'>
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
	<a href='my_profile.php' style='text-decoration: none;'>
	<img id='back' src='images/back.jpg' width="40" height="40"/></a>
	<div id='edit_info'>
		<form action='update.php' method="POST">
		<div id='div1'>
		<center><p class='special' id='personal_info'>Personal information</p></center>
		<p><label style='padding-left: 76px;'>Name:</label></p>
		<input type="text" name="name" placeholder='<?php echo $data[0]['name'];?>' /><br>
		<p><label style='padding-left: 58px;'>Surname:</label></p>
		<input type="text" name="surname" placeholder='<?php echo $data[0]['surname'];?>' /><br>
		<p><label style='padding-left: 58px; margin-top: -5px !important;'>Birthday:</label></p>
		<input id='birth' type="date" name="birthday" min="1920-01-01" max="2005-01-01"/>
		<p id='setted_birthday'><?php echo $data[0]['birthday']; ?></p><br>
		<p><label style='padding-left: 67px; margin-top: -5px;'>Gender:</label></p>
		<a href=<?php echo $links['gender'];?>><img class='delete_img_gender' src='images/delete1.png' height="23" width="23"></a>
		<?php
		for ($i=0;$i<count($gender);$i++){
			if($data[0]['gender'] === $gender[$i]){?>
			<input style='transform: translate(4px, -5px) !important;' type='radio' name='gender' id=<?php echo $gender[$i];?> value=<?php echo $gender[$i];?> checked>
			<label style='margin-left: -110px; margin-top: -5px;' for=<?php echo $gender[$i];?>><?php echo $gender[$i];?></label><?php
			}else{?>
			<input style='transform: translate(4px, -5px) !important;' type='radio' name='gender' id=<?php echo $gender[$i];?>  value=<?php echo $gender[$i];?>>
			<label style='margin-left: -110px; margin-top: -5px;' for=<?php echo $gender[$i];?>><?php echo $gender[$i];?></label><?php
			}
		}?>
		<p><label style='padding-left: 27px;'>Family status:</label></p>
		<a href=<?php echo $links['family_status'];?>><img class='delete_img1' src='images/delete1.png' height="23" width="23"></a>
		<input type="text" name="family_status" placeholder='<?php echo $data[0]['family_status'];?>'/>
		<p><label style='padding-left: 52px;'>Password:</label></p>
		<input type="text" name="password" placeholder='<?php echo $data[0]['password'];?>' /><br><br>
		</div>
		<center><p class='special' id='contacts'>Contacts</p></center>
		<div id='div2'>
		<p><label style='padding-left: 75px;'>Email:</label></p>
		<?php if($phone_email==='phone_email'){?>
		<a href=<?php echo $links['email'];?>><img class='delete_img2' src='images/delete1.png' height="23" width="23"></a><?php }elseif($phone_email==='email'){?>
		<img class='delete_img2' src='images/delete2.png' height="23" width="23">
		<?php } ?>
		<input type="text" name="email" placeholder='<?php echo $data[0]['email'];?>'/><br>
		<p><label style='padding-left: 18px;'>Phone number:</label></p>
		<?php if($phone_email==='phone_email'){?>
		<a href=<?php echo $links['phone'];?>><img class='delete_img2' src='images/delete1.png' height="23" width="23"></a><?php }else{?>
		<img class='delete_img2' src='images/delete2.png' height="23" width="23"><?php } ?>
		<input type="text" name="phone" placeholder='<?php echo $data[0]['phone'];?>' /><br>
		<p><label style='padding-left: 60px;'>Country:</label></p>
		<a href=<?php echo $links['country'];?>>
		<img class='delete_img2' src='images/delete1.png' height="23" width="23"></a>
		<input type="text" name="country" placeholder='<?php echo $data[0]['country'];?>' /><br>
		<p><label style='padding-left: 86px;'>City:</label></p>
		<a href=<?php echo $links['city'];?>>
		<img class='delete_img2' src='images/delete1.png' height="23" width="23"></a>
		<input type="text" name="city" placeholder='<?php echo $data[0]['city'];?>' /><br>
		<p><label style='padding-left: 44px;'>University:</label></p>
		<a href=<?php echo $links['university'];?>>
		<img class='delete_img2' src='images/delete1.png' height="23" width="23"></a>
		<input type="text" name="university" placeholder='<?php echo $data[0]['university'];?>' /><br>
		<p><label style='padding-left: 90px;'>Job:</label></p>
		<a href=<?php echo $links['job'];?>>
		<img class='delete_img2' src='images/delete1.png' height="23" width="23"></a>
		<input type="text" name="job" placeholder='<?php echo $data[0]['job'];?>' /><br>
		</div>
		<center><p class='special' id='interests'>Interests</p></center>
		<div id='div3'>
		<p><label style='padding-left: 46px;'>About me:</label></p>
		<a href=<?php echo $links['about_me'];?>>
		<img class='delete_img3' src='images/delete1.png' height="23" width="23"></a>
		<textarea rows='7' cols='42' type="text" name="about_me" placeholder='<?php echo $data[0]['about_me'];?>'></textarea><br>
		<p><label style='padding-left: 57px;'>Hobbies:</label></p>
		<a href=<?php echo $links['hobbies'];?>>
		<img class='delete_img3' src='images/delete1.png' height="23" width="23"></a>
		<textarea rows='7' cols='42' type="text" name="hobbies" placeholder='<?php echo $data[0]['hobbies'];?>'></textarea><br>
		<p><label style='padding-left: 5px;'>Favourite books:</label></p>
		<a href=<?php echo $links['favourite_books'];?>>
		<img class='delete_img3' src='images/delete1.png' height="23" width="23"></a>
		<textarea rows='7' cols='42' type="text" name="favourite_books" placeholder='<?php echo $data[0]['favourite_books'];?>'></textarea><br>
		<p><label style='padding-left: 0;'>Favourite movies:</label></p>
		<a href=<?php echo $links['favourite_movies'];?>>
		<img class='delete_img3' src='images/delete1.png' height="23" width="23"></a>
		<textarea rows='7' cols='42' type="text" name="favourite_movies" placeholder='<?php echo $data[0]['favourite_movies'];?>'></textarea><br>
		</div>
		<br><br>
		<button class='button' style='margin-left: 200px;' type="submit" name="submit" value="update">Update</button>
		</form>
	</div>
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
		<?php
	unset($_POST['is_search']);?>
</body>
</html>