<?php
session_start();
$user_id = $_SESSION['user_id'];
require_once 'user_search.php';
include_once 'friends_array.php';
require_once 'weather.php';
$array1 = array();

foreach($friends_array as $value){
	$friend_id = $value['user_id'];
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("SELECT * FROM friends WHERE user_id = :user_id AND request = :request 
			AND friend_id != :friend_id;");
		$stmt->bindParam(':user_id', $friend_id);
		$stmt->bindParam(':request', $one); // $one from friends_array.php
		$stmt->bindParam(':friend_id', $user_id);
		$stmt->execute();
		$data = $stmt->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	array_push($array1, $data);
}

for($k=0; $k<count($array1); $k++){
	for($i=0; $i<count($array1[$k]);$i++){
	$pos_friend_id = $array1[$k][$i]['friend_id'];
	$keys1 = array_keys($friends_array);
	$keys2 = array_keys($requests_to_me);
	$keys3 = array_keys($my_requests);
	if (in_array($pos_friend_id, $keys1) or in_array($pos_friend_id, $keys2) or in_array($pos_friend_id, $keys3)){
		unset($array1[$k][$i]);
	}
	if(empty($array1[$k])){
		unset($array1[$k]);
	}
	}
}

$friend_of_friend_array = array();

for($k=0; $k<count($array1); $k++){
	for($i=0; $i<count($array1[$k]);$i++){
		$friend_of_friend = $array1[$k][$i]['friend_id'];
		array_push($friend_of_friend_array, $friend_of_friend);
	}
}

// getting ids of all users of Aloha except current user id
try{
	$connect0 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt0 = $connect0->prepare("SELECT user_id FROM user_information WHERE user_id != :user_id;");
	$stmt0->bindParam(':user_id', $user_id);
	$stmt0->execute();
	$data0 = $stmt0->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error: </p>" . $e->getMessage();
}

for($i=0; $i<count($data0); $i++){
	$pos_friend_id = $data0[$i]['user_id'];
	$keys1 = array_keys($friends_array);
	$keys2 = array_keys($requests_to_me);
	$keys3 = array_keys($my_requests);
	if (in_array($pos_friend_id, $keys1) or in_array($pos_friend_id, $keys2) or in_array($pos_friend_id, $keys3)){
		unset($data0[$i]);
	}
	if(in_array($pos_friend_id, $friend_of_friend_array)){
		unset($data0[$i]);
	}
}
?>

<html>
<head>
	<title>Friends | <?php echo $_SESSION['username'];?></title>
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
	<div id='friends_buttons'>
		<p id='f_button1' class='friends_button1' onclick="show('f_button1', 'users_list')">My friend list</p>
		<p id='f_button2' class='friends_button' onclick="show('f_button2', 'find_friends_list')" style='margin-left: 128px !important;'>Find friends</p>
	</div>
	<div style='margin-left: 219px !important;'>
	<div id='div1'>
	<?php if(!empty($friends_array)){?>
	<div class='users_list' id='my_friends'>
		<center><h1>My friends</h1></center>
		<?php
		$num = 0;
		foreach ($friends_array as $value) {
		$num++;
		$friend_id = $value['user_id'];
		$friend_name = $value['name'] . ' ' . $value['surname'];
		$image_status = $value['image_status'];
		if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$href = 'user_profile.php?id=' . $friend_id;
			$size = 'width=80 height=80';
		}else{
			$params = ["%{$friend1_id}%"];
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
		 		$size = 'height=80';
		 	}elseif($height > $width){
		 		$size = 'width=80';
		 	}elseif($width === $height){
		 		$size = 'width=80 height=80';
		 	}
		} 
		?>
		<div class='friend'>
		<div style='width: 80px; height: 80px; overflow: hidden; border-radius: 50%;'><a href=<?php echo $href;?>><img class='friend_img' src='<?php echo $src;?>' <?php echo $size;?>></a></div>
		<p><a href=<?php echo $href;?>><?php echo $friend_name; ?></a></p>
		</div>
	<?php 
	if($num===9){
			break;
	}
	} ?>
	</div>
	<?php } ?>
	<?php if(!empty($my_requests)){?>
	<div class='users_list' id='my_requests'>
		<center><h1>My sent friend requests</h1></center>
		<?php foreach($my_requests as $value){
		$friend_id = $value['user_id'];
		$friend_name = $value['name'] . ' ' . $value['surname'];
		$image_status = $value['image_status'];
		if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$href = 'user_profile.php?id=' . $friend_id;
			$size = 'width=80 height=80';
		}else{
			$params = ["%{$friend1_id}%"];
			try{
				$connect3 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt3 = $connect3->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
				$stmt3->bindParam(':user_id', $friend_id);
				$stmt3->bindParam(':image_name1', $params[0]);
				$stmt3->execute();
				$data3 = $stmt3->fetchAll();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			$image_name = $data3[0]['image_name'];
			$src = 'images/' . $image_name;
			$href = 'user_profile.php?id=' . $friend_id;
			list($width, $height, $type, $attr) = getimagesize($src);
		 	$size = '';
		 	if($width > $height){
		 		$size = 'height=80';
		 	}elseif($height > $width){
		 		$size = 'width=80';
		 	}elseif($width === $height){
		 		$size = 'width=80 height=80';
		 	}
		} 
		?>
		<div class='friend'>
		<div style='width: 80px; height: 80px; overflow: hidden; border-radius: 50%;'><a href=<?php echo $href;?>><img class='friend_img' src='<?php echo $src;?>' <?php echo $size;?>></a></div>
		<p><a href=<?php echo $href;?>><?php echo $friend_name; ?></a></p>
		</div>
	<?php } ?>
	</div>
	<?php } ?>
	<?php if(!empty($requests_to_me)){?>
	<div class='users_list' id='requests_to_me'>
	<center><h1>Friend requests to me</h1></center>
	<?php
	$num = 0;
	foreach($requests_to_me as $value){
		$num++;
		$friend_id = $value['user_id'];
		$friend_name = $value['name'] . ' ' . $value['surname'];
		$image_status = $value['image_status'];
		if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$href = 'user_profile.php?id=' . $friend_id;
			$size = 'width=80 height=80';
		}else{
			$params = ["%{$friend_id}%"];
			try{
				$connect4 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt4 = $connect4->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
				$stmt4->bindParam(':user_id', $friend_id);
				$stmt4->bindParam(':image_name1', $params[0]);
				$stmt4->execute();
				$data4 = $stmt4->fetchAll();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			$image_name = $data4[0]['image_name'];
			$src = 'images/' . $image_name;
			$href = 'user_profile.php?id=' . $friend_id;
			list($width, $height, $type, $attr) = getimagesize($src);
		 	$size = '';
		 	if($width > $height){
		 		$size = 'height=80';
		 	}elseif($height > $width){
		 		$size = 'width=80';
		 	}elseif($width === $height){
		 		$size = 'width=80 height=80';
		 	}
		} 
		?>
		<div class='friend'>
		<div style='width: 80px; height: 80px; overflow: hidden; border-radius: 50%;'>
		<a href=<?php echo $href;?>><img class='friend_img' src='<?php echo $src;?>' <?php echo $size;?>></a></div>
		<p><a href=<?php echo $href;?>><?php echo $friend_name;?></a></p></div>
	<?php 
	if($num===9){
			break;
	}
	} ?>
	</div><?php } ?>
	</div>
	</div>
	<div style='margin-left: 219px !important;'>
	<div id='div2' class="find_friends_list">
	<center><h2>You may know</h2></center>
	<?php 
	$num = 0;
	foreach ($array1 as $value) {
		foreach($value as $var){
		$num++;
		$pos_friend_id = $var['friend_id']; // pos = possible
		try{
			$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $connect->prepare("SELECT * FROM user_information WHERE user_id = :user_id;");
			$stmt->bindParam(':user_id', $pos_friend_id);
			$stmt->execute();
			$data = $stmt->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error: </p>" . $e->getMessage();
		}
		$pos_friend_name = $data[0]['name'] . ' ' . $data[0]['surname'];
		$image_status = $data[0]['image_status'];
		if($image_status === '0'){
			$src = 'images/profile_default.jpg';
			$href = 'user_profile.php?id=' . $pos_friend_id;
			$size = 'width=80 height=80';
		}else{
			$params = ["%{$pos_friend_id}%"];
			try{
				$connect4 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt4 = $connect4->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
				$stmt4->bindParam(':user_id', $pos_friend_id);
				$stmt4->bindParam(':image_name1', $params[0]);
				$stmt4->execute();
				$data4 = $stmt4->fetchAll();
			}
			catch(PDOException $e) {
				echo "<p>Error: </p>" . $e->getMessage();
			}
			$image_name = $data4[0]['image_name'];
			$src = 'images/' . $image_name;
			$href = 'user_profile.php?id=' . $pos_friend_id;
			list($width, $height, $type, $attr) = getimagesize($src);
		 	$size = '';
		 	if($width > $height){
		 		$size = 'height=80';
		 	}elseif($height > $width){
		 		$size = 'width=80';
		 	}elseif($width === $height){
		 		$size = 'width=80 height=80';
		 	}
		} 
		?>
		<div class='pos_friend'>
		<div style='width: 80px; height: 80px; overflow: hidden; border-radius: 50%;'><a href=<?php echo $href;?>><img class='friend_img' src='<?php echo $src;?>' <?php echo $size;?>></a></div>
		<center>
		<p><a href=<?php echo $href;?>><?php echo $pos_friend_name; ?></a></p></center>
		</div><?php 
		if($num===9){
			break;
		}
	}}?>
	</div>
	<div id='div3' class="find_friends_list">
	<center><h2>Users of Aloha</h2></center>
	<?php 
	$num = 0;
	foreach($data0 as $value){
	$num++;
	$pos_friend_id = $value['user_id']; // pos = possible;
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("SELECT * FROM user_information WHERE user_id = :user_id;");
		$stmt->bindParam(':user_id', $pos_friend_id);
		$stmt->execute();
		$data = $stmt->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	$pos_friend_name = $data[0]['name'] . ' ' . $data[0]['surname'];
	$image_status = $data[0]['image_status'];
	if($image_status === '0'){
		$src = 'images/profile_default.jpg';
		$href = 'user_profile.php?id=' . $pos_friend_id;
		$size = 'width=80 height=80';
	}else{
		$params = ["%{$pos_friend_id}%"];
		try{
			$connect4 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt4 = $connect4->prepare("SELECT image_name FROM images WHERE user_id = :user_id AND image_name LIKE :image_name1;");
			$stmt4->bindParam(':user_id', $pos_friend_id);
			$stmt4->bindParam(':image_name1', $params[0]);
			$stmt4->execute();
			$data4 = $stmt4->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error: </p>" . $e->getMessage();
		}
		$image_name = $data4[0]['image_name'];
		$src = 'images/' . $image_name;
		$href = 'user_profile.php?id=' . $pos_friend_id;
		list($width, $height, $type, $attr) = getimagesize($src);
		 	$size = '';
		 	if($width > $height){
		 		$size = 'height=80';
		 	}elseif($height > $width){
		 		$size = 'width=80';
		 	}elseif($width === $height){
		 		$size = 'width=80 height=80';
		 	}
	} 
	?>
	<div class='pos_friend'>
	<div style='width: 80px; height: 80px; overflow: hidden; border-radius: 50%;'><a href=<?php echo $href;?>><img class='friend_img' src='<?php echo $src;?>' <?php echo $size;?>></a></div>
	<center>
	<p><a href=<?php echo $href;?>><?php echo $pos_friend_name; ?></a></p></center>
	</div><?php 
	if($num===9){
			break;
		}} ?>	
	</div>
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
		<?php if($msg1 != ""): ?>
				<h4><?php echo $msg1; ?></h4>
		<?php endif; ?>

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
		$('f_button1').onclick = function () {show1()};
		$('f_button2').onclick = function () {show2()};
		function show1(){
			$('f_button1').className = "friends_button1";
			$('f_button2').className = "friends_button";
			$('div1').style.display = 'block';
			$('div2').style.display = 'none';
			$('div3').style.display = 'none';
		}
		function show2(){
			$('f_button1').className = "friends_button";
			$('f_button2').className = "friends_button1";
			$('div1').style.display = 'none';
			$('div2').style.display = 'block';
			$('div3').style.display = 'block';
		}
		function margin(id){
			$(id).style.marginLeft = "260px !important";
		}
	</script>
	<?php unset($_POST['is_search']);?>
</body>
</html>