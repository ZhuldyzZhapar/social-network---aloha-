<?php 
session_start();
if (isset($_GET['logout'])){ // IMPORTANT
	session_unset();
}

$phone_array = array();
$email_array = array();
$attempts = isset($_SESSION['attempts']) ? $_SESSION['attempts'] : 2;
// $wanted_user_id = id of the user that person trying to log in
$wanted_user_id = isset($_SESSION['wanted_user_id']) ? $_SESSION['wanted_user_id'] : '';

try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect ->prepare("SELECT phone, email
								FROM user_information;");
	$stmt->execute();
	$data = $stmt->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error: </p>" . $e->getMessage();
}

foreach($data as $i){
	$k = $i['email'];
	$email_array[] = $k;
}
foreach($data as $i){
	$k = $i['phone'];
	$phone_array[] = $k;
}	

if($_POST['submit']==='login'){ // login
	$message = '';
	$user_1 = $_POST['username'];
	$pass_1 = $_POST['password'];
	$correct_pass = '';
	// if it a phone number and in the database
	if(in_array($user_1, $phone_array)){
		try{
			$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt1 = $connect1 ->prepare("SELECT password, user_id, name, surname
										FROM user_information
										WHERE phone = :phone;");
			$stmt1->bindParam(":phone", $user_1);
			$stmt1->execute();
			$data1 = $stmt1->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error1: </p>" . $e->getMessage();
		}
		$correct_pass = $data1[0]['password'];
		// searching if an account blocked because of several unsuccessful attempts
		try{
			$connect0 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt0 = $connect0->prepare("SELECT * FROM login_attempts
										WHERE user_id=:user_id;");
			$stmt0->bindParam(":user_id", $data1[0]['user_id']);
			$stmt0->execute();
			$data0 = $stmt0->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error0: </p>" . $e->getMessage();
		}
		// if there is not a record about blocking an account OR if blocking time is ended -> deleting the record in table about blocking time
		if(empty($data0) or date('Y-m-d H:i:s') > $data0[0]['new_time_login']){
			try{
				$connect11 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect11->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt11 = $connect11->prepare("DELETE FROM login_attempts
												WHERE user_id=:user_id;");
				$stmt11->bindParam(":user_id", $data1[0]['user_id']);
				$stmt11->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error11: </p>" . $e->getMessage();
			}
			if ($pass_1 === $correct_pass){ // if password correct
				session_unset();
				$_SESSION['user_id'] = $data1[0]['user_id'];
				$_SESSION['username'] = $data1[0]['name'] . ' ' . $data1[0]['surname'];
				echo "<script> window.location.replace('my_profile.php')</script>";
			// if password is incorrect, starts counting attempts	
			}else{
				// the first time of incorrect entering password of one user OR attempt to login by the another user
				$message = 'Incorrect password';
				if($wanted_user_id === '' or $wanted_user_id!== $data1[0]['user_id']){
					$_SESSION['wanted_user_id'] = $data1[0]['user_id'];
					$_SESSION['attempts'] = 1; // 1 attempt was used, 2 attempts left 
				}
				// already was incorrect entering password
				elseif($wanted_user_id === $data1[0]['user_id']){
					$attempts --;
					$_SESSION['attempts'] = $attempts;
				}
			}
		//if there is still time left of blocking account
		}elseif(date('Y-m-d H:i:s') < $data0[0]['new_time_login']){
			$diff = strtotime($data0[0]['new_time_login']) - time();
			$message = 'Account is blocked due to exceeded login attempts.<br> Blocking time left is ' . gmdate("i:s", $diff);
			session_unset();// to prevent creating of the new record about blocking with the same account
		// if blocking time is ended, deleting the record in table about blocking time
		}
	//if it is an email and in database	
	}elseif(in_array($user_1, $email_array)){
		try{
			$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt1 = $connect1 ->prepare("SELECT password, user_id, name, surname
										FROM user_information
										WHERE email = :email;");
			$stmt1->bindParam(":email", $user_1);
			$stmt1->execute();
			$data1 = $stmt1->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error1: </p>" . $e->getMessage();
		}
		$correct_pass = $data1[0]['password'];
		// searching if an account blocked because of several unsuccessful attempts
		try{
			$connect0 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
			$connect0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt0 = $connect0->prepare("SELECT * FROM login_attempts
										WHERE user_id=:user_id;");
			$stmt0->bindParam(":user_id", $data1[0]['user_id']);
			$stmt0->execute();
			$data0 = $stmt0->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error0: </p>" . $e->getMessage();
		}
		// if there is not a record about blocking an account OR if blocking time is ended, deleting the record in table about blocking time
		if(empty($data0) or date('Y-m-d H:i:s') > $data0[0]['new_time_login']){
			try{
				$connect11 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
				$connect11->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt11 = $connect11->prepare("DELETE FROM login_attempts
												WHERE user_id=:user_id;");
				$stmt11->bindParam(":user_id", $data1[0]['user_id']);
				$stmt11->execute();
			}
			catch(PDOException $e) {
				echo "<p>Error11: </p>" . $e->getMessage();
			}
			if ($pass_1 === $correct_pass){ // if password correct
				session_unset();
				$_SESSION['user_id'] = $data1[0]['user_id'];
				$_SESSION['username'] = $data1[0]['name'] . ' ' . $data1[0]['surname'];
				echo "<script> window.location.replace('my_profile.php')</script>";
			// if password is incorrect, starts counting attempts	
			}else{
				// the first time of incorrect entering password of one user OR attempt to login by the another user
				$message = 'Incorrect password';
				if($wanted_user_id === '' or $wanted_user_id!== $data1[0]['user_id']){
					$_SESSION['wanted_user_id'] = $data1[0]['user_id'];
					$_SESSION['attempts'] = 1; // 1 attempt was used, 2 attempts left 
				}
				// already was incorrect entering password
				elseif($wanted_user_id === $data1[0]['user_id']){
					$attempts --;
					$_SESSION['attempts'] = $attempts;
				}
			}
		//if there is still time left of blocking account
		}elseif(date('Y-m-d H:i:s') < $data0[0]['new_time_login']){
			$diff = strtotime($data0[0]['new_time_login']) - time();
			$message = 'Account is blocked due to exceeded login attempts.<br> Blocking time left is ' . gmdate("i:s", $diff);
			session_unset(); // to prevent creating of the new record about blocking with the same account
		// if blocking time is ended, deleting the record in table about blocking time
		}
	}else{ // if an email and phone number is incorrect - access denied
	$message = 'Incorrect phone number or email';
	}
}elseif($_POST['submit']==='register'){ // registration
	$message_1 = '';
	$message_2 = '';
	$message_3 = '';
	$mails = ['yandex.kz', 'yandex.ru', 'gmail.com', 'yahoo.com', 'mail.ru']; // certified mails
	$new_phone_or_email = $_POST['username'];
	// check if it is already linked to existed page email
	if(!in_array($new_phone_or_email, $email_array)){
		// check if it is already linked to existed page phone number 
		if(!in_array($new_phone_or_email, $phone_array)){
			// if it is an email
			if(stripos($new_phone_or_email, '@')){
				$array = explode('@', $new_phone_or_email);
				if(in_array(end($array), $mails)){ // if new_email is certified mail
					/*requirements for the password 
					length >= 8 symbols and at least one number, one letter, one special symbol*/
					if(strlen($_POST['password']) >= 8){
						if($_POST['password'] === $_POST['password_1']){
							$_SESSION['name'] = $_POST['name'];
							$_SESSION['surname'] = $_POST['surname'];
							$_SESSION['birthday'] = $_POST['birthday'];
							$_SESSION['password'] = $_POST['password'];
							$_SESSION['is_email_or_phone'] = 'email';
							$_SESSION['phone_or_email'] = $new_phone_or_email;
							echo "<script> window.location.replace('registration.php')</script>";
						}else{
							$message_1 = 'Passwords are not identical';
						}
					}else{
						$message_1 = 'Password is too short';
					}
				}else{
					$message_3 = 'This mail is not certified';
				}
			//if it is a phone number (length > 11 and <=12 and all symbols are numbers)
			}elseif(strlen($new_phone_or_email) <= 12 and strlen($new_phone_or_email) > 10 and intval(substr($new_phone_or_email, 1, 11)) >= 1000000000){
				/*requirements for the password 
				length >= 8 symbols*/
				if(strlen($_POST['password']) >= 8){
					if($_POST['password'] === $_POST['password_1']){
						$_SESSION['name'] = $_POST['name'];
						$_SESSION['surname'] = $_POST['surname'];
						$_SESSION['birthday'] = $_POST['birthday'];
						$_SESSION['password'] = $_POST['password'];
						$_SESSION['is_email_or_phone'] = 'phone';
						$_SESSION['phone_or_email'] = $new_phone_or_email;
						echo "<script> window.location.replace('registration.php')</script>";
					}else{
						$message_1 = 'Passwords are not identical';
					}
				}else{
					$message_1 = 'Password is too short';
				}
			// if it is not an email or phone number
			}else{
				$message_2 = 'Please enter valid phone number';
			}
		}else{
			$message_2 = 'This phone number is already linked to the existed page';
		}
	}else{
		$message_3 = 'This email is already linked to the existed page';
	}
}
if($_SESSION['attempts'] === 0){
	$now = getdate();
	$now_hour = $now['hours']; 
	$now_minutes = $now['minutes'] + 30; // setting a 30 minutes timer
	$now_seconds = $now['seconds'];
	// taking value of current date
	$date = date('Y-m-d');
	$date = new DateTime($date);
	// setting a new datetime, when user can again enter a password to the blocked account
	$date->setTime($now_hour, $now_minutes, $now_seconds);
	$new_time_login = $date->format('Y-m-d H:i:s');
	try{
		$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt2 = $connect2->prepare("INSERT INTO login_attempts (user_id, new_time_login)
									VALUES (:user_id, :new_time_login)");
		$stmt2->bindParam(":user_id", $_SESSION['wanted_user_id']);
		$stmt2->bindParam(":new_time_login", $new_time_login);
		$stmt2->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error2: </p>" . $e->getMessage();
	}
}
?>

<html>
<head>
<title>Log In and Registration</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type = "text/javascript" src = "/javascript/prototype.js"></script>
<script type = "text/javascript" src = "/javascript/scriptaculous.js"></script>
<link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet" type="text/css">
</head>
<body>
	<header>
		<nav class="navbar">
			<ul><li id='logo'>Aloha</li>
			</ul>
		</nav>
	</header>
	<div class='registration'>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method="POST">
			<p style='font-weight: 900; font-size: 22px; text-align: center;'><strong>Log In</strong></p>
			<input class='input' type="text" name="username" placeholder="Phone number or email" required/>
			<input class='input' type="password" name="password" placeholder="Password" required/>
			<p style='font-size: 14px; color: navy;'><?php echo $message;?></p>
			<button class='button' type="submit" name="submit" value="login">Login</button>
		</form>
		<br>
		<br>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method="POST">
			<p style='font-weight: 900; font-size: 22px; text-align: center;'><strong>Registration</strong></p>
			<input class='input' type="text" name="name" placeholder="Name" required/>
			<input class='input' type="text" name="surname" placeholder="Surname" required/>
			<input class='input' type="text" name="username" placeholder="Phone number or email" required/>
			<p id='mess3'><?php echo $message_3;?></p>
			<p id='mess2'><?php echo $message_2;?></p>
			<input class='input' type="password" name="password" placeholder="Password (length at least 8 symbols)" required/>
			<input class='input' type="password" name="password_1" placeholder="Enter password again" required/>
			<p id='mess1'><?php echo $message_1;?></p>
			<p style='font-weight: 700'>Date of birth: <input type="date" name="birthday" min="1920-01-01" max="2005-01-01" required/></p>
			<button class='button' type="submit" name="submit" value="register">Register</button>
		</form>
	</div>
	<div style='padding: 130px 0 0 80px;'>
	<p class='index_caption'>Le plus grand luxe est le luxe de la communication humaine.</p>
	<p class='index_caption' style='color: olivedrab;'>The greatest luxury is the luxury of human communication.</p>
	<p class='index_caption_1'>Antoine de Saint Exupery</p>
	</div>
</body>
</html>