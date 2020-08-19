<?php
session_start();
$user_id = $_SESSION['user_id'];
$text = "";
include 'post_server.php';
require_once 'weather.php';
// connect to database in PDO and sample mysql
$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db = mysqli_connect('localhost', 'root', '', 'aloha');
$stmt = $connect->prepare("SELECT DISTINCT p.*, u.name, u.surname, f.friend_id 
							FROM posts AS p 
							LEFT JOIN user_information AS u ON p.user_id = u.user_id 
							LEFT JOIN friends AS f ON f.friend_id = p.user_id 
							WHERE f.user_id = $user_id OR p.user_id = $user_id 
							ORDER BY p.date DESC; ");
$stmt->execute();
$posts = $stmt->fetchAll();
$msg = "";
// search posts, between mine and my friend's
// if(isset($_POST['search'])){
// 	global $connect;
// 	$text1 = addslashes($_POST['text1']);
// 	// this if for display username of post 
// 	$stmt = $connect->prepare("SELECT p.*, u.name, u.surname, f.friend_id FROM posts AS p 
// 							LEFT JOIN user_information AS u ON p.user_id = u.user_id 
// 							LEFT JOIN friends AS f ON f.friend_id=p.user_id 
// 							WHERE (f.user_id = $user_id OR p.user_id = $user_id)  
// 							AND text LIKE :text ORDER BY p.date DESC;");
// 	$text = "%$text1%";
// 	$stmt->bindParam(':text', $text); 
// 	$stmt->execute();
// 	$posts = $stmt->fetchAll();
// 	if (empty($posts)) {
// 		$msg = "No posts like this";
// 	} 
// }
// here you can put suggested users that  living in user_id's city or friends, this is just sample
// getting information about all users and their profile image
$stmt = $connect->prepare("SELECT * 
						FROM user_information 
						WHERE user_id != $user_id
						ORDER BY surname ASC 
						LIMIT 4");
$stmt->execute();
$users = $stmt->fetchAll();
$msg1 = "";
// search users
if(isset($_POST['search_user'])){
	global $connect;
	$find_user = addslashes($_POST['users']);
	$params = ["%{$find_user}%"];
	// this code for search user by name or surname 
	$stmt = $connect->prepare("SELECT * FROM user_information WHERE name LIKE :name OR surname LIKE :surname ORDER BY user_id LIMIT 5");
	$stmt->bindParam(':name', $params[0]);
	$stmt->bindParam(':surname', $params[0]); 
	$stmt->execute();
	$users = $stmt->fetchAll();
	if (empty($users)) {
		$msg1 = "Not user like this";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Main page | <?php echo $_SESSION['username'];?></title>
	<link rel="stylesheet" type="text/css" href="CSS/main.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
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
		<!-- body of search-users -->
		<li style='margin-top: -10px;'><p class='search' id='search' onclick="show_search('block')">Find User</p></li>
		</li>
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
<div id="id"></div>
<section class="main">
<!-- body of posts -->
<div class="posts">
	<tr><div style='float: right; margin-right: 250px;'>
		<a style='position: absolute; text-decoration: none;' class='button' href="post_delete.php">Add Post</a></div>
	</tr>
<table>	
<tbody>
	<?php if($msg != ""): ?>
		<h3><?php echo $msg; $msg = '';?></h3>
	<?php endif;?>
	<!-- here I display all posts -->
	<?php foreach($posts as $post): ?>
	<tr><td>
	<div class="body">
		<!-- here I send logged user_id and id, whose posted this post. You can use it for go to profile where user_id = $id-->
		<h4><a href="user_profile.php?id=<?php echo $post['user_id'];?>"><?php echo $post['name'];?> <?php echo  $post['surname'];?></a></h4>
		<?php 
		$post_time = $post['date1'];
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
		<i class="calendar"> <?php echo  $time_string; ?></i>
		<?php $blabla = strlen($post['text']) > 600 ? substr($post['text'],0, 600)."...more": $post['text'] ?>
		<a href="post_singlePost.php?post_id=<?php echo $post['post_id'];?>"><p style='font-size:16px; color: black; font-family: Calibri;'><?php echo $blabla ?></p></a>

		<!-- Here I checked is that post mine, if not then  I can add it into my post-table -->
		<?php if ($post['user_id'] != $user_id){ ?>
			<a href="post_delete.php?post_id=<?php echo $post['post_id'];?>">
				<input type="submit" name="share" value="Share" class="btn"></a>
		<?php } ?>

		<!-- if user likes post, style button differently -->
      	<i <?php if (userLiked($post['post_id'],$user_id)): ?>
      		  class="fa fa-thumbs-up like-btn"
      	  <?php else: ?>
      		  class="fa fa-thumbs-o-up like-btn"
      	  <?php endif ?>
      	  data-id="<?php echo $post['post_id'] ?>&<?php echo $user_id ?>"></i>
      	<span class="likes"><?php echo getLikes($post['post_id']); ?></span>
      	
      	&nbsp;&nbsp;&nbsp;&nbsp;

	    <!-- if user dislikes post, style button differently -->
      	<i 
      	  <?php if (userDisliked($post['post_id'],$user_id)): ?>
      		  class="fa fa-thumbs-down dislike-btn"
      	  <?php else: ?>
      		  class="fa fa-thumbs-o-down dislike-btn"
      	  <?php endif ?>
      	  data-id="<?php echo $post['post_id'] ?>&<?php echo $user_id ?>" ></i>
      	<span class="dislikes"><?php echo getDislikes($post['post_id']); ?></span>
      </div>
	<div class="snip0016">
		<?php if(empty($post['video'])): 
			$src = 'images/' . $post['image'];
			list($width, $height, $type, $attr) = getimagesize($src);
		 	$size = '';
		 	if($width > $height){
		 		$size = "height = '272'";
		 	}elseif($height > $width){
		 		$size = "width = '480'";
		 	}elseif($width === $height){
		 		$size = "width = '272' height = '272'";
		 	}?>
			<div class='image_div'><img src="<?php echo $src;?>" <?php echo $size;?>></div>
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
				$size = "height = '272'";
			}elseif($resolution_y > $resolution_x){
				$size = "width = '480'";
			}elseif($resolution_y === $resolution_x){
				$size = "width = '272' height = '272'";
			}?>
			<div class='image_div'>
			<video controls src="<?php echo $src;?>" <?php echo $size;?>></video></div>
		<?php endif; ?>
		<?php if(!empty($post['tag_friend'])){?>
		<figcaption>
			<h1>Tagged friend</h1>
			<p><?php echo $post['tag_friend'];?></p>
			<?php } ?>
		</figcaption>			
	</div>
	</td></tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>
<!-- Searching the users 
when "User Find" is clicked followin window will appear
if user not found, in placeholder will be message about not founding the user-->
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
			<input type="submit" name="search_user" value="Search" class="button">
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
		show('block');
	}
	document.getElementById('gray_search').onclick = function () {show_search('none')};
	function show_search(state){
		document.getElementById('window_search').style.display = state;
		document.getElementById('gray_search').style.display = state;
	}
</script>
<?php unset($_POST['is_search']);?>
</section>
<script src="js/scripts.js">
</script>
</body>
</html>