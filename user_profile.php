<?php
session_start();
$user_id = $_SESSION['user_id'];
require_once 'user_search.php';
require_once 'weather.php';
$current_user_id = $_GET['id'];// id of the user which profile we are looking at
$one = '1';
require_once 'friends_array.php';
try{
	$connect0 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt0 = $connect0->prepare("SELECT f.user_id, f.friend_id, f.request, u.surname, u.name, u.image_status
								FROM friends f
								INNER JOIN user_information u
								ON f.friend_id = u.user_id
								WHERE f.user_id = :user_id AND request = :request
								ORDER BY u.surname ASC;");
	$stmt0->bindParam(':user_id', $current_user_id);
	$stmt0->bindParam(':request', $one);
	$stmt0->execute();
	$data0 = $stmt0->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error1: </p>" . $e->getMessage();
}
$common_friends = array();
foreach ($friends_array as $value1){
	foreach ($data0 as $value2) {
		if($value1['user_id'] === $value2['friend_id']){
			array_push($common_friends, $value1);
		}
	}
}

try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect ->prepare("SELECT * FROM user_information WHERE user_id = :user_id;");
	$stmt->bindParam(':user_id', $current_user_id);
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
$image_status = $data[0]['image_status'];

require_once 'friends_array.php';
$array1 = array_keys($friends_array);
$array2 = array_keys($my_requests);
$array3 = array_keys($requests_to_me);
?>
<html>
<head>
	<title>Profile | <?php echo $data[0]['name'] . ' ' . $data[0]['surname'];?></title>
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
		<p><a href="index.php?logout=yes" style='color: navy !important; '>Log out</a></p>
		<a target="_blank" href="https://nochi.com/weather/almaty-10297"><img src="https://w.bookcdn.com/weather/picture/11_10297_1_20_ffffff_118_2373ca_333333_ffffff_1_ffffff_333333_0_6.png?scode=124&domid=589&anc_id=54919"  alt="booked.net"/></a>
		<br><br>
		<a target="_blank" href="https://nochi.com/weather/astana-w1465"><img src="https://w.bookcdn.com/weather/picture/11_w1465_1_20_ffffff_118_2373ca_333333_ffffff_1_ffffff_333333_0_6.png?scode=124&domid=589&anc_id=54919"  alt="booked.net"/></a>
	</div>
	<div class='profile_image' style='overflow: hidden;'>
		<?php if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$size = 'width=220 height=220';
		}else{
			$params = ["%{$current_user_id}%"];
			try{
				$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $connect->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
				$stmt->bindParam(':user_id', $current_user_id);
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
		<div style='width: 220px; height: 220px; overflow: hidden; border: 4px solid white;'><img id='profile_image' src='<?php echo $src;?>' <?php echo $size;?>></div>
		<br><?php
		if(in_array($current_user_id, $array1)){
			$href = 'send_message.php?delete_friend_id=' . $current_user_id;?>
			<h2 id='your_friend' class='friend_request' onmouseover="hover('none', 'your_friend', 'block', 'delete_friend')" onmouseout="hover('block', 'your_friend', 'none', 'delete_friend')">Your friend</h2>
			<h2 id='delete_friend' class='friend_request2 none'><a href=<?php echo $href;?>>Delete from friends</a></h2>
			<?php
		}elseif(in_array($current_user_id, $array2)){
			$href = 'send_message.php?cancel_my_request_friend_id=' . $current_user_id;?>
			<h2 id='my_request' class='friend_request' 
			onmouseover="hover('none', 'my_request', 'block', 'cancel_my_request_friend_id')" 
			onmouseout="hover('block', 'my_request', 'none', 'cancel_my_request_friend_id')">Request is sent</h2>
			<h2 id='cancel_my_request_friend_id' class='friend_request2 none'><a href=<?php echo $href;?>>Cancel request</a></h2><?php
		}elseif(in_array($current_user_id, $array3)){
			$href1 = 'send_message.php?accept_friend_id=' . $current_user_id;
			$href2 = 'send_message.php?reject_friend_id=' . $current_user_id;?>
			<h2 id='request_to_me' class='friend_request'>Sent friend request<br>to you</h2>
			<h2 id='accept_request' class='friend_request2'><a href=<?php echo $href1;?>>Accept request</a></h2>
			<h2 id='cancel_request' class='friend_request2'><a href=<?php echo $href2;?>>Reject request</a></h2>
			<?php
		}else{
			$href = 'send_message.php?add_friend_id=' . $current_user_id;?>
			<h2 id='add_friend' class='friend_request' onmouseover="change('add_friend', 'friend_request2')" 
			onmouseout="change('add_friend', 'friend_request')"><a href=<?php echo $href;?>>Add to friend</a></h2>
			<?php
		}
		?>
	</div>
	<div class='profile_info' style='min-height: 1000px;'>
		<h2 style='color: darkmagenta;'><?php echo $name . ' ' . $surname;?></h2><?php 
		echo "<p class='characteristics'><strong>Born in:</strong></p>";
		echo "<p class='info1'>" . $birthday . "</p>";
		if($gender!==null){
		echo "<p class='characteristics'><strong>Gender:</strong></p>";
		echo "<p class='info1'>" . $gender . "</p>";}
		if($family_status!==null){
		echo "<p class='characteristics'><strong>Family status:</strong></p>";
		echo "<p class='info1'>" . $family_status . "</p>";}
		if($country!==null && $city!==null){
			echo "<p class='characteristics'><strong>Lives in:</strong></p>";
			echo "<p class='info1'>" . $country . ', ' . $city . "</p>";
		}elseif($country!==null && $city===null){
			echo "<p class='characteristics'><strong>Lives in:</strong></p>";
			echo "<p class='info1'>" . $country . "</p>";
		}elseif($country===null && $city!==null){
			echo "<p class='characteristics'><strong>Lives in:</strong></p>";
			echo "<p class='info1'>" . $city . "</p>";}
		if($university!==null){
		echo "<p class='characteristics'><strong>University:</strong></p>";
		echo "<p class='info1'>" . $university . "</p>";}
		if($job!==null){
		echo "<p class='characteristics'><strong>Works as:</strong></p>";
		echo "<p class='info1'>" . $job . "</p>";}
		if($about_me!==null){
		echo "<p class='characteristics'><strong>About me:</strong><br></p>";
		echo "<p class='info1'>" . $about_me . "</p>";} 
		if($hobbies!==null){
		echo "<p class='characteristics'><strong>Hobbies:</strong><br></p>";
		echo "<p class='info1'>" . $hobbies . "</p>";} 
		if($favourite_books!==null){
		echo "<p class='characteristics'><strong>Favourite books:</strong><br></p>";
		echo "<p class='info1'>" . $favourite_books . "</p>";} 
		if($favourite_movies!==null){
		echo "<p class='characteristics'><strong>Favourite movies:</strong><br></p>";
		echo "<p class='info1'>" . $favourite_movies . "</p>";} 
		?></p>
	</div>
	<div id='div1' style='float: right !important;'>
	<?php if(!empty($data0)){?>
	<div class='his_friends_div'>
		<center><h2>Friends</h2></center>
		<?php
		foreach ($data0 as $value) {
		$friend_id = $value['friend_id'];
		$friend_name = $value['name'] . ' ' . $value['surname'];
		$image_status = $value['image_status'];
		if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$href = 'user_profile.php?id=' . $friend_id;
		}else{
			$params = ["%{$friend_id}%"];
			try{
				$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt2 = $connect2->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
				$stmt2->bindParam(':user_id', $friend_id);
				$stmt2->bindParam(':image_name1', $params[0]);
				$stmt2->execute();
				$data2 = $stmt2->fetchAll();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			$image_name = $data2[0]['image_name'];
			$src = 'images/' . $image_name;
			$href = 'user_profile.php?id=' . $friend_id;
			list($width, $height, $type, $attr) = getimagesize($src);
		 	$size = '';
		 	if($width > $height){
		 		$size = 'height=40';
		 	}elseif($height > $width){
		 		$size = 'width=40';
		 	}elseif($width === $height){
		 		$size = 'width=40 height=40';
		 	}
		} 
		?>
		<div class='his_friend'>
		<p><a href=<?php echo $href;?>><?php echo $friend_name; ?></a></p>
		<div style='width: 40px; height: 40px; overflow: hidden; border-radius: 50%;'><a href=<?php echo $href;?>><img class='friend_img' src='<?php echo $src;?>' <?php echo $size;?>></a>
		</div></div>
	<?php } ?>
	</div>
	<?php } ?>
	</div>
	<div id='div2' style='float: right !important;'>
	<?php 
	if(!empty($common_friends)){?>
	<div class='his_friends_div'>
		<center><h2>Common friends</h2></center>
		<?php
		foreach ($common_friends as $value) {
		$friend_id = $value['user_id'];
		$friend_name = $value['name'] . ' ' . $value['surname'];
		$image_status = $value['image_status'];
		if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$href = 'user_profile.php?id=' . $friend_id;
		}else{
			$params = ["%{$friend_id}%"];
			try{
				$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt2 = $connect2->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
				$stmt2->bindParam(':user_id', $friend_id);
				$stmt2->bindParam(':image_name1', $params[0]);
				$stmt2->execute();
				$data2 = $stmt2->fetchAll();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			$image_name = $data2[0]['image_name'];
			$src = 'images/' . $image_name;
			$href = 'user_profile.php?id=' . $friend_id;
			list($width, $height, $type, $attr) = getimagesize($src);
		 	$size = '';
		 	if($width > $height){
		 		$size = 'height=40';
		 	}elseif($height > $width){
		 		$size = 'width=40';
		 	}elseif($width === $height){
		 		$size = 'width=40 height=40';
		 	}
		} 
		?>
		<div class='his_friend'>
		<p><a href=<?php echo $href;?>><?php echo $friend_name; ?></a></p>
		<div style='width: 40px; height: 40px; overflow: hidden; border-radius: 50% !important;'><a href=<?php echo $href;?>><img class='friend_img' src='<?php echo $src;?>' <?php echo $size;?>></a>
		</div></div>
	<?php } ?>
	</div>
	<?php } ?>
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
	<script type="text/javascript">
		function hover(state1, id1, state2, id2){
			$(id1).style.display = state1;
			$(id2).style.display = state2;
		}
		function change(id, nameclass){
			$(id).className = nameclass;
		}
	</script>
	<?php unset($_POST['is_search']);?>
</body>
</html>