<?php 

session_start();
include_once("connection.php");
include_once("make_friends.php");


class Process {

	var $connection; 

	public function __construct()
	{

		$this->connection = new Database();

		if(isset($_POST['register_action']) && $_POST['register_action'] == 'register')
		{
			$this->register();
		}

		if(isset($_POST['login_action']) && $_POST['login_action'] == 'login')
		{
			$this->login();
		}
		if(isset($_POST['post_action']) && $_POST['post_action'] == 'post')
		{
			$this->post();
		}
		if(isset($_POST['comment_action']) && $_POST['comment_action'] == 'comment')
		{
			$this->comment();
		}
		if(isset($_POST['friend_action']) && $_POST['friend_action'] == 'add_friend' AND isset($_SESSION['logged_in']))
		{
			$this->add_friend();
		}
		if(isset($_POST['like_action']) && $_POST['like_action'] == 'like')
		{
			$this->like(); 
		}
		if(isset($_POST['logout_action']) && $_POST['logout_action'] == 'logout')
		{
			$this->logout();
		}

	}
	private function register()
	{

	// for the register function we need to makes sure all errors work 
		$error_messages = array();

		if(!(isset($_POST['first_name']) && !empty($_POST['first_name'])))
		{
			$error_messages['first_name'] = "Please enter a valid First Name";
		}
		if(!(isset($_POST['last_name']) && !empty($_POST['last_name'])))
		{
			$error_messages['last_name'] = "Please enter a valid Last Name";
			// var_dump($error_messages);
			// die();		
		}
		if(!(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)))
		{
			$error_messages['email'] = "Please enter a valid Email";;
		}
		if($_POST['birth_date'] == "")
		{
			$error_messages['birth_date'] = "Please enter a valid birth date with mm/dd/yyyy";
		}
		if(!(isset($_POST['password']) && strlen($_POST['password'])>=6))
		{
			$error_messages['password'] = "Please double check your password";
		}
		// if the confirm password is == to the password 
		if(!(isset($_POST['confirm_password']) and isset($_POST['password']) and $_POST['password'] == $_POST['confirm_password']))
		{
			$error_messages['confirm_password'] = "Passwords do not match";
		}
			
			// if there are error messages, we need to put these on the index page
			if(!empty($error_messages))
			{
				$_SESSION['failure'] = $error_messages;
				header('Location: index.php');
			}
			elseif(empty($error_messages))
			{

				$query = "SELECT * from users WHERE email = '{$_POST['email']}'";
				$users = $this->connection->fetch_all($query);
			}

				if(count($users)>0)
				{
					$error_messages['same_email'] = "Email address is taken. Please enter a new one";
					$_SESSION['failure'] = $error_messages;
					header('Location: index.php');
				}
				else
				{
					$password = md5($_POST['password']);
					$query = "INSERT INTO users (first_name, last_name, email, birthdate, password, created_at, updated_at) VALUES ('".mysql_real_escape_string($_POST['first_name'])."', '".mysql_real_escape_string($_POST['last_name'])."', '".mysql_real_escape_string($_POST['email'])."', '".mysql_real_escape_string($_POST['birth_date'])."', '".mysql_real_escape_string($password)."', NOW(), NOW())"; 
					// echo $query; 
					// die();
					mysql_query($query);

					$_SESSION['success'] = "Thank you for registering";
					header('Location: index.php');
				}
	}

	function login()
	{
		$login_errors = array();

		if(empty($_POST['email']))
		{
			$login_errors['email'] = 'Please enter an Email';
		}
		elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
		{
			$login_errors['email'] = "Invalid email address";
		}
		if(empty($_POST['password']))
		{
			$login_errors['password'] = "Please enter a password";
		}


		if(!empty($login_errors))
		{
			$_SESSION['login_errors'] = $login_errors;
			header('Location: index.php');
		}

		elseif (empty($login_errors)) 
		{
			//we want to see if the login info matches what is in the db//
			$password = md5($_POST['password']);
			$query = "SELECT * from users where email = '{$_POST['email']}' && password = '$password'";
			$user = $this->connection->fetch_record($query);
			if(!empty($user))
			{
				//set sessions from the db user stuff, right? 
				$_SESSION['logged_in'] = true; // user is logged in
				$_SESSION['user']['first_name'] = $user['first_name'];
				$_SESSION['user']['last_name'] = $user['last_name'];
				$_SESSION['user']['email'] = $user['email'];
				$_SESSION['user']['id'] = $user['id'];
				header('Location: wall.php');
			}
			else
			{
				$login_errors['email_pw_db'] = "Invalid login information";
				$_SESSION['login_errors'] = $login_errors; 
				header('Location: index.php');

			}
		}
	
	}


	function logout()
	{
		session_destroy();
		header("location: index.php");
	}

	function post()
	{
		$query = "INSERT INTO posts (user_id, content, likes, created_at, updated_at) VALUES ('{$_SESSION['user']['id']}', '{$_POST['post_name']}', '0', now(), now())";
		$posts = mysql_query($query);		
		header('Location: wall.php');

	}

	function comment()
	{
		$query = "INSERT INTO comments (content, created_at, updated_at, post_id, user_id) VALUES ('{$_POST['comment_name']}', now(), now(), {$_POST['comment_user']}, '{$_SESSION['user']['id']}')";
		$comments = mysql_query($query);
		header('Location: wall.php');
	}

	function add_friend()
	{ 
		$friend = new Friendly();
		$friend->add_friend_to_db($_SESSION['user']['id'], $_POST['friend_id']);
		header('Location: wall.php');		
	}

	function like() 
	{
		  $post_id = $_POST['post_id']; 
		  $user_id = $_POST['user_id'];
		  $query = "UPDATE posts SET likes = likes + 1 WHERE id =" . $post_id;
		  mysql_query($query);
		  header('Location: wall.php');
	}
}

$process = new Process();

?>
