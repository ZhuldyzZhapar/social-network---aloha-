<?php 
session_start();
$_SESSION['post_id'] = $_GET['post_id'];
require_once 'user_search.php';
require_once 'weather.php';
include 'post_db.php';
$data = all_usernames_commented($post_id);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Post | <?php echo $_SESSION['username'];?></title>
	<link rel="stylesheet" type="text/css" href="CSS/comment.css">
	<link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
	<script type = "text/javascript" src = "/javascript/prototype.js"></script>
    <script type = "text/javascript" src = "/javascript/scriptaculous.js"></script>
</head>
<body id='body'>
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
<div class='comment_div'>
	<?php foreach($posts as $post):?>
	<div class="each-post">
		<div>
			<?php 
			if($post['user_id']===$_SESSION['user_id']){
				$link = "href = 'my_profile.php'";
			}else{
				$link = "href = 'user_profile.php?id='" . $post['user_id'];
			} ?>
			<h4><a <?php echo $link;?>><?php echo $post['name']?> <?php echo $post['surname']?></a></h4>
			<?php 
			$post_time = $post['date1'];
			$curr_time = time();
			$real_time = $curr_time - $post_time;
			$real_min = round($real_time / 60);
			$real_hour = $real_time / 3600;
			if ($real_min >= 1 and $real_min < 60 and $real_hour < 1) {
				$time_string = round($real_min)." minutes ago " ;
			}elseif($real_min < 1){
				$time_string = $real_time . " seconds ago ";
			}elseif($real_time >= 1 and $real_hour < 24) {
				$time_string = round($real_hour)." hours ago " ;
			}else{
				$time_string = $post['date'];
			}?>
			<i class="calendar"> <?php echo  $time_string; ?></i>
		</div>
		<div id='p'>
			<p><?php echo $post['text']?></p>
		</div>
		<div>
			<?php if(empty($post['video'])): 
				$src = 'images/' . $post['image'];
				list($width, $height, $type, $attr) = getimagesize($src);
			 	$size = '';
			 	if($width > $height){
			 		$size = "height = '300'";
			 	}elseif($height > $width){
			 		$size = "width = '550'";
			 	}elseif($width === $height){
			 		$size = "width = '300' height = '300'";
			 	}?><div class='image_div'>
				<img src="<?php echo $src;?>" id='click_image' onclick="show('block')" <?php echo $size;?>></div>
				<div class='popup' id='popup'>
				<div class='popup_bg' id='popup_bg'></div>
				<img src="<?php echo $src;?>" class='popup_img'/>
				</div>
				<script type="text/javascript">
					$('click_image').onclick = function () {show('block')};
					$('popup_bg').onclick = function () {show('none')};
					function show(state){
						$('popup').style.display = state;
					}
				</script>
			<?php endif; ?>
			<?php if(empty($post['image'])): 
				$src = 'video/' . $post['video'];
				include_once('getID3-master/getid3/getid3.php');
				$getID3 = new getID3;
				$video_file = $getID3->analyze($src);
				$duration_string = $video_file['playtime_string'];
				$format = $video_file['fileformat'];
				$resolution_x = $video_file['video']['resolution_x'];
				$resolution_y = $video_file['video']['resolution_y'];
				$size = '';
				if ($resolution_x > $resolution_y){
					$size = "height = '300'";
				}elseif($resolution_y > $resolution_x){
					$size = "width = '550'";
				}elseif($resolution_y === $resolution_x){
					$size = "width = '300' height = '300'";
				}?><div class='image_div'>
				<video id='click_video' src="<?php echo $src;?>" <?php echo $size;?>></video></div>
				<div class='popup' id='popup'>
				<div class='popup_bg' id='popup_bg'></div>
				<video src="<?php echo $src;?>" class='popup_img' controls autoplay loop/></video>
				</div>
				<script type="text/javascript">
					$('click_video').onclick = function () {show('block')};
					$('popup_bg').onclick = function () {show('none')};
					function show(state){
						$('popup').style.display = state;
					}
				</script>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>

<?php if (isset($_SESSION['msg'])): ?>
	<div class="msg">
		<?php 
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
		?>
	</div>
<?php endif ?>
		
<div class="comment" action='post_db.php'>
<?php foreach($data as $comm): ?>
	<div class="each-post">
		<div>
			<h4><a href="Profile of Friend"><?php echo  $comm['name'];?> <?php echo $comm['surname']; ?></a></h4>
			<i class="calendar"> <?php echo $comm['date']?></i>
		</div>
		<div id='c'>
			<p><?php echo $comm['comm']?></p>
		</div>
	</div>
<?php endforeach; ?>
</div>

<form method="post">
	<input type='hidden' name='uid' value='Anonymous'>
	<input type='hidden' name='date' >
	<textarea name='message'></textarea><br><br>
	<button type='submit' name='send' class='button'>Comment</button>
</form>
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
	<?php unset($_POST['is_search']);?>
	<!-- <script type="text/javascript">
		$("click_image").addEventListener("click", show());
		function show() {	// Событие клика на маленькое изображение
		let img = $(this); // Получаем изображение, на которое кликнули
		let src = img.attr('src'); // Достаем из этого изображения путь до картинки
		let href1 = src.replace('images/', ''); 
		console.log(src);
		$("body").append("<div class='popup'>"+ //Добавляем в тело документа разметку всплывающего окна
						 "<div class='popup_bg'>" + "</div>"+ // Блок, который будет служить фоном затемненным
						 "<img src='"+ src +"' class='popup_img' />"+ // Само увеличенное фото
						 "</div>"); 
		$(".popup_bg").addEventListener("click", function(){	// Событие клика на затемненный фон	   
			$(".popup").fadeOut(500);	// Медленно убираем всплывающее окно
			setTimeout(function() {	// Выставляем таймер
			  $(".popup").remove(); // Удаляем разметку всплывающего окна
			}, 500);
		});
		};
		$("click_video").addEventListener("click", function() {
		alert('clicked');
	 	let vid = $(this);
		let src = vid.attr("src");
		let href1 = src.replace('video/', '');
		$("body").append(
			"<div class='popup'>"+ //Добавляем в тело документа разметку всплывающего окна
			"<div class='popup_bg'>" + "</div>"+ // Блок, который будет служить фоном затемненным
			"<video src='"+ src +"' class='popup_img' controls autoplay loop></video>"+ // Само увеличенное фото
			"</div>"); 
		$(".popup").fadeIn(500); // Медленно выводим изображение
		$(".popup_bg").click(function(){	// Событие клика на затемненный фон	   
			$(".popup").fadeOut(500);	// Медленно убираем всплывающее окно
			setTimeout(function() {	// Выставляем таймер
			  $(".popup").remove(); // Удаляем разметку всплывающего окна
			}, 500);
		});
		});
	</script> -->
</div>
</body>
</html>