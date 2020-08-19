<?php 
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'aloha');

if (isset($_POST['query'])) {
	$query = $_POST['query'];
	$user_id = $_POST['user_id'];
	$output = '';
	// here I select only friends of user_id
	$sql = "SELECT u.* FROM user_information AS u INNER JOIN friends AS f ON f.user_id = '$user_id' WHERE u.user_id = f.friend_id AND u.name LIKE '%$query%'";
	$result = mysqli_query($connect, $sql);
	$output = '<ul class="list-unstyled">';

	if(mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$output .= '<li>'.$row['name'].' '.$row['surname'].'</li>';
		}
	}else{
		$output .= '<li> Friend Not Found </li>';
	}
	$output .= '</ul>';
	echo $output;
}
?>