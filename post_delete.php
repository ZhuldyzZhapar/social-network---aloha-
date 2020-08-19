<?php 
session_start();	
$user_id = $_SESSION['user_id'];
require_once 'user_search.php';
require_once 'weather.php';
$text = "";

if (isset($_SESSION['msg'])) {
	$_SESSION['msg'] = $_GET['msg'];
}
$db = mysqli_connect('localhost', 'root', '', 'aloha');
// if save button is clicked 
if(isset($_POST['save'])) {
	global $user_id;
	$file = $_FILES['file'];
	$date = time();
	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileType = $file['type'];
	// here I checked type of video and image for save it in "posts" <- name of table
	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));
	$allowed = array('jpg', 'jpeg', 'png', 'webp');//these are types of image
	$allowed1 = array('mp4', 'webm', 'mov', );// these are types of videos
	$text = addslashes($_POST['text']);
	$friend = addslashes($_POST['friend']);	
	if(in_array($fileActualExt, $allowed)){
		$file_new_name = uniqid('img_', true) . '.' . $fileActualExt;
		$fileDes = "images/". $file_new_name;	//This is the directory where images will be saved 
		$sql = "INSERT INTO posts (user_id, image, text, date1, tag_friend) VALUES ('$user_id','$file_new_name', '$text', '$date', '$friend') ";
		mysqli_query($db, $sql);
		move_uploaded_file($fileTmpName, $fileDes);
	}elseif(in_array($fileActualExt, $allowed1)){
		$file_new_name = uniqid('vid_', true) . '.' . $fileActualExt;
		$fileDes = "video/". $file_new_name;	//This is the directory where images will be saved 
		$text = addslashes($_POST['text']);
		$sql = "INSERT INTO posts (user_id, video, text, date1, tag_friend) VALUES ('$user_id','$file_new_name', '$text', '$date', '$friend') ";
		mysqli_query($db, $sql);
		move_uploaded_file($fileTmpName, $fileDes);
	}
	echo "<script> window.location.replace('post_delete.php')</script>";
}

//delete records
if (isset($_GET['del'])) {
	global $user_id;
	//here i delete post
	$id = $_GET['del'];
	mysqli_query($db, "DELETE FROM posts WHERE post_id=$id ");
	mysqli_query($db, "DELETE FROM comment WHERE post_id=$id ");
	mysqli_query($db, "DELETE FROM rating_info WHERE post_id=$id ");
	header("location: post_delete.php");
}

// add someone's post
if (isset($_GET['post_id'])){
	global $user_id;
	$post_id = $_GET['post_id'];
	$date = time();
	$sql1 = mysqli_query($db,"SELECT text, date, image FROM posts WHERE post_id=$post_id");
	$sql1 =mysqli_fetch_array($sql1);
	mysqli_query($db, "INSERT INTO `posts`( `user_id`, `text`, `image`, `date1`) VALUES ( $user_id, '".$sql1['text']."','".$sql1['image']."', '$date')");
	header("location: post_delete.php");
}
//retrieve records
$results = mysqli_query($db, " SELECT * FROM posts WHERE user_id = $user_id ORDER BY date DESC");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add new post | <?php echo $_SESSION['username'];?></title></title>
	<link rel="stylesheet" type="text/css" href="CSS/style.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Baloo+2" rel="stylesheet" type="text/css">
	<style>
		.list-unstyled{
			display: block;
		}
		ul{
			cursor: pointer;
		}
		li{
			display: block;
			margin-left: -30px;
			color: midnightblue;
			font-family: Calibri;
			font-size: 20px;
		}
	</style>
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
	<a href='main_page.php' style='text-decoration: none;'>
	<img id='back' src='images/back.jpg' width="40" height="40"/></a>
	<p id='my_posts' style='margin-left: 310px;'>My posts</p>
	<table>
	<tbody>
		<!-- here I display list of my posts -->
		<?php while ($row = mysqli_fetch_array($results)){ ?>
			<tr>
				<td style='width: 300px;'><div>
					<?php if(empty($row['video'])): 
						$src = 'images/' . $row['image'];
						list($width, $height, $type, $attr) = getimagesize($src);
					 	$size = '';
					 	if($width > $height){
					 		$size = "height = '150'";
					 	}elseif($height > $width){
					 		$size = "width = '300'";
					 	}elseif($width === $height){
					 		$size = "width = '150' height = '150'";
					 	}?>
					 	<div class='image_div_1'>
						<img src="<?php echo 'images/'.$row['image']; ?> " <?php echo $size;?>></div>
					<?php endif; ?>
					<?php if(empty($row['image'])): 
						$src = 'video/' . $row['video'];
						include_once('getID3-master/getid3/getid3.php');
						$getID3 = new getID3;
						$video_file = $getID3->analyze($src);
						$duration_string = $video_file['playtime_string'];
						$format = $video_file['fileformat'];
						$resolution_x = $video_file['video']['resolution_x'];
						$resolution_y = $video_file['video']['resolution_y'];
						$size = '';
						if ($resolution_x > $resolution_y){
							$size = "height = '150'";
						}elseif($resolution_y > $resolution_x){
							$size = "width = '300'";
						}elseif($resolution_y === $resolution_x){
							$size = "width = '150' height = '150'";
						}?>
						<div class='image_div_1'>
						<video controls src="<?php echo $src;?>" <?php echo $size;?>></video></div>
						<?php endif; ?>
				</div></td>
				<td id='text'><?php echo $row['text'];?><br>
				<?php $post_time = $row['date1'];
				$curr_time = time();
				$real_time = $curr_time - $post_time;
				$real_min = round($real_time / 60);
				$real_hour = $real_time / 3600;
				if ($real_min >= 1 and $real_min < 60 and $real_hour < 1) {
					$time_string = round($real_min)." minutes ago " ;
				}else if ($real_min < 1){
					$time_string = $real_time . " seconds ago ";
				}else if ($real_time >= 1 and $real_hour < 24) {
					$time_string = round($real_hour)." hours ago " ;
				}else{
					$time_string = $post['date'];
				}?>
				<i class="calendar"> <?php echo  $time_string; ?></i></td>
				<td><a class="del_btn" href="post_delete.php?del=<?php echo $row['post_id']; ?>&user_id=<?php echo $user_id; ?>">Delete</a></td>
			</tr>
			<div>
			</div>
		<?php } ?>
	</tbody>
</table>

<!-- those codes for add new post -->
<form id='form' method="post" enctype="multipart/form-data" action='<?php echo $_SERVER['PHP_SELF'];?>'>
	<div class="input-group">
		<label>Text</label>
		<input type="text" name="text" placeholder="Text of a post">
		<label>Tag friend</label>
		<input type="text" class="friend" id="<?php echo $user_id;?>" name="friend" placeholder="Friend's name">
		<div id="nameList"></div>
	</div> 
	<br>
	<div>
		<label id='label'>Image/video</label>
		<input id='file' type="file" name="file">
	</div>
	<br>
	<div class = "input-group">
		<button class="del_btn" type="submit" name="save">Post</button>	
	</div>
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
<script>
	// here I used autocomplete system using json and jquery. You can find it on YouTube if you don't understand 
	$(document).ready(function(){
		$('.friend').keyup(function(){
			var user_id = $(this).attr('id');
			var query = $(this).val();
			if (query != '') {
				$.ajax({
					url:"post_tag_friend.php",
					method:'post',
					data:{
						query: query,
						user_id: user_id
					},
					success: function(data){
						$('#nameList').fadeIn();
						$('#nameList').html(data);
					}
				});
			}
			else{
				$('#nameList').fadeOut();
				$('#nameList').html("");
			}
		});
		$(document).on('click', 'li', function(){
			$('.friend').val($(this).text());
			$('#nameList').fadeOut();
		});
	});
</script>
<?php unset($_POST['is_search']);?>
</body>
</html>