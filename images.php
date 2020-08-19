<?php 
session_start();
$user_id = $_SESSION['user_id'];
require_once 'user_search.php';
require_once 'weather.php';
$album_name = $_GET['album'];
$href = 'delete.php?deleteAlbum=' . $album_name;
try{
	$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt1 = $connect1->prepare("SELECT image_name, tag_person FROM images WHERE user_id = :user_id AND album = :album ORDER BY date_time DESC;");
	$stmt1->bindParam(':user_id', $user_id);
	$stmt1->bindParam(':album', $album_name);
	$stmt1->execute();
	$album_images = $stmt1->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error1: </p>" . $e->getMessage();
}
?>
<html>
<head>
	<title><?php echo $album_name;?> | <?php echo $_SESSION['username'];?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type = "text/javascript" src = "/javascript/prototype.js"></script>
    <script type = "text/javascript" src = "/javascript/scriptaculous.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Baloo+2" rel="stylesheet" type="text/css">
</head>
<body style='width: 100%;'>
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
	<a href='albums.php?what=images' style='text-decoration: none;'>
	<img id='back' src='images/back.jpg' width="40" height="40"/></a>
	<div style='display: inline;'><h2 id='albums_h2'><?php echo $album_name;?></h2></div>
	<?php if($album_name!=='Profile images'){?>
	<p class='upload_images'>Upload images</p>
	<p class='album_rename' id='album_rename'>Rename album</p>
	<p class='delete_album' style='margin-right: 328px;'>
	<a href='<?php echo $href;?>'>Delete album</a></p><?php } ?>
	<center>
	<div id='window_rename'>
		<img id='close' src="images/close.png" width="15" height="15" />
		<p style='font-size: 20px; font-family: Raleway; color: indigo;'><strong>Rename album</strong></p><br>
		<form method="POST" action="albums.php?what=images">
			<input class='input' type='text' name='new_album_name' placeholder="New name of an album" style='width: 250px !important;' required><br><br>
			<button class='create_album_button' type='submit' name='submit'>Create album</button>
		</form>
	</div>
	</center>
	<?php if($_SESSION['where']==='upload'){ ?>
		<div id='window' class='window' style='display: block !important;'>
	  		<img class='close' id='close' src='images/close.png' width='15' height='15' />
	  		<p><?php echo $_SESSION['message'];?></p>
		</div>
		<div id='gray' class='gray' style='display: block !important;'></div>
	<?php
	unset($_SESSION['where']);
	unset($_SESSION['message']);
	}?>
	<?php
	foreach ($album_images as $value){
	 	$image = $value['image_name'];
	 	if($image === 'no image'){ // if album is empty
	 	?><div class='images'>
	 	<div class='image'>
	 	<div class='image_div'><center><br><br><p>No images</p></center></div></div></div>
	 	<?php
	 	break;
	 	}elseif($image === 'profile'){
	 	?><div class='images'><br>
	 	<div class='image'>
	 	<div class='image_div'><center><br><br><p>No images</p></center></div></div></div>
	 	<?php
	 	break;
	 	}else{ // if album is not empty
	 	$src = 'images/' . $image;
	 	list($width, $height, $type, $attr) = getimagesize($src);
	 	$size = '';
	 	if($width > $height){
	 		$size = 'height=150';
	 	}elseif($height > $width){
	 		$size = 'width=150';
	 	}elseif($width === $height){
	 		$size = 'width=150 height=150';
	 	}?><div class='images'>
	 	<div class='image'>
	 	<div class='image_div'><img class='click_image' src='<?php echo $src;?>' <?php echo $size;?>/></div></div>
	 	</div>
	<?php } }
	?>
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
	let album_name = "<?php echo $album_name;?>";
	console.log(album_name);
	$(".click_image").click(function(){	// Событие клика на маленькое изображение
	  	let img = $(this);	// Получаем изображение, на которое кликнули
		let src = img.attr('src'); // Достаем из этого изображения путь до картинки
		let href1 = src.replace('images/', '');
		<?php $image_name = "<script>document.writeln(href1);</script>";
		$_SESSION['album'] = $album_name;
		?>
		href = 'delete.php?delete=' + href1;
		$("body").append("<div class='popup'>"+ //Добавляем в тело документа разметку всплывающего окна
						 "<div class='popup_bg'>" + 
						 "</div>"+ // Блок, который будет служить фоном затемненным
						 "<div class='img_button_div'>" + 
						 "<a href=" + href + "><p class='img_delete'>Delete</p></a></div>" +
						 "<img src='"+ src +"' class='popup_img' />"+ // Само увеличенное фото
						 "</div>"); 
		$(".popup").fadeIn(500); // Медленно выводим изображение
		$(".popup_bg").click(function(){	// Событие клика на затемненный фон	   
			$(".popup").fadeOut(500);	// Медленно убираем всплывающее окно
			setTimeout(function() {	// Выставляем таймер
			  $(".popup").remove(); // Удаляем разметку всплывающего окна
			}, 500);
		});
	});

	$(".upload_images").click(function(){
	<?php $_SESSION['album'] = $album_name;?>
  	$("body").append("<center>" + "<div id='window' class='window' style='display: block !important;'>" + 
  		"<img class='close' id='close' src='images/close.png' width='15' height='15' />" + 
  		"<p><strong>Upload new images</strong></p>" + 
  		"<p>Please choose an image of <i>'.jpg', '.jpeg', '.png'</i> type.</p>" + 
  		"<form action='upload.php?what=image' method='POST' enctype='multipart/form-data'>" + 
  		"<input type='file' name='file'>" + "<button type='submit' name='submit'>Upload image</button><br><br>" + 
  		"</form>" + "</div>" + "</center>" + 
  		"<div id='gray' class='gray' style='display: block !important;'> ></div>"); 
	$(".window").fadeIn(500);
	$(".gray").fadeIn(500);
	$(".close").click(function(){
		$(".window").fadeOut(500);	
		$(".gray").fadeOut(500);
		setTimeout(function() {	
		  $(".window").remove(); 
		  $(".gray").remove(); 
		}, 500);
	});
	$(".gray").click(function(){
		$(".window").fadeOut(500);	
		$(".gray").fadeOut(500);
		setTimeout(function() {	
			$(".window").remove(); 
			$(".gray").remove(); 
			}, 500);
		});
	});
	$(".close").click(function(){
	$(".window").fadeOut(500);	
	$(".gray").fadeOut(500);
		setTimeout(function() {	
			$(".window").remove(); 
			$(".gray").remove(); 
		}, 500);
	});
	$(".gray").click(function(){
		$(".window").fadeOut(500);	
		$(".gray").fadeOut(500);
		setTimeout(function() {	
			$(".window").remove(); 
			$(".gray").remove(); 
			}, 500);
		});
	$('.album_rename').onclick = function () {show_r('block')};
		function show_r(state){
			$('window_rename').style.display = state;
			$('gray').style.display = state;
		}
	$(".album_rename").click(function(){
  	$("body").append("<center>" + 
  		"<div class='window_rename' id='window_rename' style='display: block !important;'>" + 
  		"<img id='close' class='close' src='images/close.png' width='15' height='15' />" + "<p style='font-size: 20px; font-family: Raleway; color: indigo;'>" + "<strong>Rename album</strong>" + "</p><br>" + 
  		"<form method='POST' action='delete.php'>" +
		"<input class='input' type='text' name='new_album_name' placeholder='New name of an album' style='width: 250px !important;'required>" + 
		"<br><br>" + "<button class='create_album_button' type='submit' name='submit'>Rename album</button>" + 
		"</form>" + "</div>" + "</center>" +
  		"<div id='gray' class='gray' style='display: block !important;'> ></div>");
  	<?php $_SESSION['old_album_name'] = $album_name;?>
	$(".window_rename").fadeIn(500);
	$(".gray").fadeIn(500);
	$(".close").click(function(){
		$(".window_rename").fadeOut(500);	
		$(".gray").fadeOut(500);
		setTimeout(function() {	
		  $(".window_rename").remove(); 
		  $(".gray").remove(); 
		}, 500);
	});
	$(".gray").click(function(){
		$(".window_rename").fadeOut(500);	
		$(".gray").fadeOut(500);
		setTimeout(function() {	
			$(".window_rename").remove(); 
			$(".gray").remove(); 
			}, 500);
		});
	});

	</script>
	<?php unset($_POST['is_search']);?>
</body>
</html> 