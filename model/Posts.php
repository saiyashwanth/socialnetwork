<?php
	class Posts{
		//inserts -1 as the receiver 
		public function status_update($content, $id, $name){
			mysql_query("INSERT INTO posts (content, timestamp, poster, receiver, postername, receivername) VALUES('$content', NOW(), '$id',0, '$name','')"); 		
		}
		public function post_wall($content, $id, $receivername){
			$my_id = $_SESSION['id'];
			$postername = $_SESSION['name'];
			mysql_query("INSERT INTO posts (content, timestamp, poster, receiver, postername, receivername) VALUES('$content', NOW(), '$my_id', '$id', '$postername', '$receivername')"); 		
		}
		
		public function get_friend_posts($id){
			//This makes sure that you only see posts from you and direct friends (can see direct friends post on mutual friends)
			//This also orders from most recent to oldest
			$query = mysql_query("SELECT * FROM posts INNER JOIN friends ON((friends.firstperson= '$id' OR friends.secondperson='$id') 
									AND (friends.firstperson = posts.poster OR friends.firstperson = posts.receiver
									OR friends.secondperson = posts.poster OR friends.secondperson = posts.receiver)) ORDER BY timestamp DESC;");
			if(mysql_num_rows($query) == 0){
				$query = mysql_query("SELECT * FROM posts WHERE poster = '$id' ORDER BY timestamp DESC");
			}
			if($query){
				$posts = array();
				while($row = mysql_fetch_array($query)){
					$posterid = $row['poster'];
					$receiverid = $row['receiver'];
					$postername = $row['postername'];
					$receivername = $row['receivername'];
					$content = $row['content'];
					$time = $row['timestamp'];
					$json_data = json_encode(array("postername"=>$postername, "receivername"=>$receivername, "posterid"=>$posterid, "receiverid"=>$receiverid, "content"=>$content, "time"=>$time));
					$posts[] = $json_data;
				}
				return json_encode($posts);
			}
		}
	
		public function get_personal_posts($id){
			$query = mysql_query("SELECT poster, receiver,postername,receivername,content,timestamp FROM posts INNER JOIN friends ON((friends.firstperson= '$id' AND (posts.poster = friends.firstperson
								OR posts.receiver=friends.firstperson)) OR (friends.secondperson= '$id' 
								AND(posts.poster=friends.secondperson OR posts.receiver = friends.secondperson ))) GROUP BY poster, receiver,postername,receivername,content,timestamp ORDER BY timestamp DESC;");
			if(mysql_num_rows($query) == 0){
				$query = mysql_query("SELECT * FROM posts WHERE poster = '$id' ORDER BY timestamp DESC");
			}
			if($query){
				$posts = array();
				while($row = mysql_fetch_array($query)){
					$posterid = $row['poster'];
					$receiverid = $row['receiver'];
					$postername = $row['postername'];
					$receivername = $row['receivername'];
					$content = $row['content'];
					$time = $row['timestamp'];
					$json_data = json_encode(array("postername"=>$postername, "receivername"=>$receivername, "posterid"=>$posterid, "receiverid"=>$receiverid, "content"=>$content, "time"=>$time));
					$posts[] = $json_data;
				}
				return json_encode($posts);
			}
		}
		
		
	}
?>