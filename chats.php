<?php 
session_start();
$user_id = $_SESSION['user_id'];
require_once 'user_search.php';
require_once 'weather.php';
try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("SELECT * FROM friends WHERE user_id = :user_id;");
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();
	$friends = $stmt->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error0: </p>" . $e->getMessage();
}

try{
	$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt1 = $connect1->prepare("SELECT * FROM chats WHERE user_1 = :user_1;");
	$stmt1->bindParam(':user_1', $user_id);
	$stmt1->execute();
	$chats1 = $stmt1->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error1: </p>" . $e->getMessage();
}
try{
	$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt2 = $connect2->prepare("SELECT * FROM chats WHERE user_2 = :user_2;");
	$stmt2->bindParam(':user_2', $user_id);
	$stmt2->execute();
	$chats2 = $stmt2->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error2: </p>" . $e->getMessage();
}

$chats = array();
for($i=0; $i<count($chats1); $i++){
	$array = array('chat_id' => $chats1[$i]['chat_id'], 'user_id' => $user_id, 'user_2' => $chats1[$i]['user_2']);
	array_push($chats, $array);
}
for($i=0; $i<count($chats2); $i++){
	$array = array('chat_id' => $chats2[$i]['chat_id'], 'user_id' => $user_id, 'user_2' => $chats2[$i]['user_1']);
	array_push($chats, $array);
}

$_SESSION['chats'] = $chats;
?>


<html>
<head>
	<title>Chats | <?php echo $_SESSION['username'];?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type = "text/javascript" src = "/javascript/prototype.js"></script>
    <script type = "text/javascript" src = "/javascript/scriptaculous.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
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
	<div class='chats' style='justify-content: center; margin: 0 auto;'>
		<h2 style='color: olivedrab;'>Chats</h2>
		<table class='chats_table'>
			<?php for($i=0; $i<COUNT($chats); $i++){
				$buddy_id = $chats[$i]['user_2'];
				$chat_id = $chats[$i]['chat_id'];
				try{
				$connect3 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt3 = $connect3->prepare("SELECT * FROM user_information WHERE user_id=:user_id;");
				$stmt3->bindParam(':user_id', $buddy_id);
				$stmt3->execute();
				$buddy = $stmt3->fetchAll();
				}
				catch(PDOException $e) {
					echo "<p>Error3: </p>" . $e->getMessage();
				}

				try{
				$connect4 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt4 = $connect4->prepare("SELECT message FROM messenger WHERE chat_id = :chat_id ORDER BY id DESC;");
				$stmt4->bindParam(':chat_id', $chat_id);
				$stmt4->execute();
				$buddy_last_message = $stmt4->fetchAll();
				}
				catch(PDOException $e) {
					echo "<p>Error3: </p>" . $e->getMessage();
				}
				$href = 'messages.php?chat_id=' . $chat_id;
				$buddy_name = $buddy[0]['name'] . ' ' . $buddy[0]['surname'];?>
				<tr><td><a class='link' href="<?php echo $href; ?>"><p><strong style='font-size: 20px; color: midnightblue;'><?php 
				echo $buddy_name . '</strong><br><br>' . $buddy_last_message[0]['message'] . '</p></a></td></tr>';
			}?>
		</table>
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
	<footer>
	<p>Aloha &copy 2020</p>
	</footer>
</body>
</html>