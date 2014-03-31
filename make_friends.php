<?php

include_once("connection.php");

class Friendly extends Database 
{

	function get_users($user_id)
	{

		$query = "SELECT * from users WHERE id !=" . $user_id;
		return $this->fetch_all($query);
		
	}

	function get_friends($user_id)
	{
		if(is_numeric($user_id))
		{
			$query = "SELECT users.first_name, users.last_name, users.email, users.id, friends.created_at from users left JOIN friends on users.id = friends.friend_id where user_id =" .  $user_id;
			return $this->fetch_all($query);
		}
	}

	function add_friend_to_db($user_id, $friend_id)
	{
		if(is_numeric($user_id) AND is_numeric($friend_id))
		{
			$query = "INSERT INTO friends (user_id, friend_id, created_at) VALUES ({$user_id}, {$friend_id}, NOW())";
			mysql_query($query);

			$query = "INSERT INTO friends (user_id, friend_id, created_at VALUES ({$friend_id}, {$user_id}, NOW())";
			// echo $query;
			mysql_query($query);
		} 
	}



}