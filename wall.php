<?php

session_start();
include_once("class_lib.php");
include_once("make_friends.php");

$post = new Wall();
$posts = $post->make_post();

$comments = $post->comments_on_posts();



$list_of_friends = array(); 
$friend = new Friendly();
$friends = $friend->get_friends($_SESSION['user']['id']);
$users = $friend->get_users($_SESSION['user']['id']);

foreach ($friends as $friend)
{
	$friend_lists[$friend['id']] = "TRUE";
}

if (!$_SESSION['logged_in'])
{
	header('Location: index.php');
}

?>


<html>
<head>
	<meta charset="UTF-8">
	<title>KyleAnde Network | Join the community</title>
	<meta name="author" content="Kyle Anderson">
	<meta name="description" content="Network for PaperStreet">
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/main-2.css">

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
	<body>
		<header>
			<div class="paperstreet">
				KyleAnde<span> The Social Network</span>
			</div>
		</header>
		<div class="wrapper_wall">

			<div class="wall_right">
				
					<h2 class="user_home">Welcome, <?= $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'] ?> </h2>
					<form id="logout" action="process.php" method="post">
							<input type="hidden" name="logout_action" value="logout">
							<input type="submit" value='Logout' id="logout">
				    </form>
				
				<h2>My Friends</h2>
				<table>
					<th>Name</th>
					<th>Email</th>
					<th>Friendship Started On:</th>
				<?php

					foreach ($friends as $friend) 
					{ 
								$time = $friend['created_at'];
								$time = date("m-d-y", strtotime($time));
					?> 
						<tr>
							<td><?=$friend['first_name'] . " " . $friend['last_name'] ?></td>
							<td><?= $friend['email'] ?></td>
							<td><?= $time ?></td>
						</tr>						
				<?php }
				
				?>
				</table>
				<h2>Add Friends</h2>
				<table>
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Add Friend</th>
						</tr>
					</thead>
					<tbody>
					   <?php
							foreach ($users as $user) 
							{ 
								?>

								<tr>
									<td><?= $user['first_name'] . " " . $user['last_name'] ?></td>
									<td><?= $user['email'] ?></td>
									<td>
										<?php
											if(isset($friend_lists[$user['id']]))
											{
												echo "Friends";
											}
											else
											{ 
												?> 
												<form action="process.php" method="post">
													<input type="hidden" name="friend_action" value="add_friend">
													<input type="hidden" name="friend_id" value=<?= $user['id'] ?>>
													<input type="submit" value= "+1 Add Friend"> 	
												</form>											
										<?php
											}
											?>
									</td>
								</tr>
					  <?php }
							  ?>
					
					</tbody>
				</table>	
			</div><!-- end of wall right --> 

			<div class="wall_left">
				<div class="post-form">
					<form id ="form-section" action="process.php" method="post">
						<input type="hidden" name="post_action" value="post">
						<textarea type="text" rows='4' cols='65' name="post_name" value="post_value" placeholder="What's going on?"></textarea>
						<input type="submit" value="POST" id="post-wall">
					</form>
				</div>
				<?php
					foreach ($posts as $post)   
					{ 
						?>
						<h4 class='wall_name'><?= $post['first_name'] . " " . $post['last_name']  ?></h4>
						<p class='wall_content'><?= $post['content'] ?></p>
						<div class="likes">
							<form class="like_form" action="process.php" method="post">
								<input type="hidden" name="like_action" value="like">
								<input type="hidden" name="user_id" value=<?= $user['id']?>>
								<input type="hidden" name="post_id" value=<?= $post['id']?>>
								<input type="submit" value="like" id="like"> 
							</form>
							<p class="p-like"><?php if($post['likes'] == 0)
								{
									echo "";
								}
								elseif($post['likes'] == 1)
								{
									echo $post['likes']. "" . 'like';
								}

								else
								{
									echo $post['likes']. " " . 'likes';
								}

							 ?></p>
						</div>
						<?php
								foreach ($comments as $comment) 
								{
									$time = $comment['created_at'];
									$time = date("m-d-y", strtotime($time));

									if($post['id'] == $comment['post_id']) 
									{
									   ?>
									<div class="comments">
									<p class="user-comment"><?= "<span>" . $comment['first_name'] . " " . $comment['last_name'] . ":" . "</span>" . " " . $comment['content']  ?></p>
									<h6 class="time"><?= $time ?></h6> 
									</div>					
						<?php
									}	
								}		
									?>

						<form id="comment-form" action="process.php" method="post">
							<input type="hidden" name="comment_action" value="comment">
							<textarea type="text" rows='2' cols='35' name="comment_name" value="post_value" placeholder="write a comment.."></textarea><br />
							<input type="hidden" name="comment_user" value=<?= $post['id'] ?>>
							<input type="submit" value="Comment" id="post-comment-btn">
						</form>
				<?php }
						?>
			</div><!-- end of wall left -->
		</div><!-- end of wrapper --> 
	</body>
</html>
