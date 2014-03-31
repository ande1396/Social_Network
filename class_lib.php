<?php

include_once("connection.php");

class Wall extends Database 
{

	function make_post()
	{
		$query = "SELECT users.first_name, users.last_name, posts.content, posts.likes, posts.created_at, posts.id from posts left join users on posts.user_id = users.id GROUP by posts.id ORDER BY posts.created_at DESC";
		
		return $this->fetch_all($query);
	}

	function comments_on_posts()
	{
		$query = "SELECT comments.id, users.first_name, users.last_name, comments.content, comments.created_at, comments.post_id, comments.user_id  FROM comments LEFT JOIN users on comments.user_id = users.id GROUP BY comments.id ASC";
		return $this->fetch_all($query);
	}

}


?>
