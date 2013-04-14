<?php
class Friends{
	public function add_friend($id){
		$my_id = $_SESSION['id'];
		if($this->should_request($id)){
			mysql_query("INSERT INTO invite (initiator, respondent) VALUES ('$my_id', '$id')");
		}
	}
	
	public function should_request($id){
		$my_id = $_SESSION['id'];
		$not_invited = mysql_num_rows(mysql_query("SELECT * FROM invite WHERE (initiator = '$my_id' 
		AND respondent='$id') OR (initiator='$id' AND respondent='$my_id')")) == 0;
		$is_friend = $this->is_friend($id);
		return $not_invited && !$is_friend;
	}
	
	public function has_decision($id){
		$my_id = $_SESSION['id'];
		return mysql_num_rows(mysql_query("SELECT * FROM invite WHERE initiator = '$id' 
		AND respondent='$my_id'")) > 0;
	}
	
	public function is_friend($id){
		$my_id = $_SESSION['id'];
		return mysql_num_rows(mysql_query("SELECT * FROM friends WHERE (firstperson = '$my_id' 
		AND secondperson='$id') OR (firstperson='$id' AND secondperson='$my_id')")) > 0;
	}
	public function accept_friend($id){
		$my_id = $_SESSION['id'];
		mysql_query("INSERT INTO friends (firstperson, secondperson) VALUES ('$my_id', '$id')");
		mysql_query("DELETE FROM invite WHERE initiator = '$id' and respondent ='$my_id'");
	}
	
	public function decline_friend($id){
		$my_id = $_SESSION['id'];
		mysql_query("DELETE FROM invite WHERE initiator = '$id' and respondent ='$my_id'");
	}
	
	public function get_friend_requests(){
		$id = $_SESSION['id'];
		$friend_query = mysql_query("SELECT initiator, name  FROM invite INNER JOIN accounts ON initiator = id WHERE respondent='$id'");
		if($friend_query){
			while($row = mysql_fetch_array($friend_query)){
				echo "<div id='".$row['initiator']."'><a href='profile.php?id=".$row['initiator']."'>".$row['name']."</a><br>
				      <button class='btn btn-success' id='accept'>Accept Friend Request</button>
					  <button class='btn btn-danger' id='deny'>Deny Friend Request</button></div><hr>";
			}
		}
	}
	
	public function get_friends($id){
		$friend_query = mysql_query("SELECT * FROM friends WHERE firsperson='$id' OR secondperson='$id'");
		$id = $_SESSION['id'];
		if($friend_query){
			while($row = mysql_fetch_array($query)){
				$first = $row['firstperson'];
				$second = $row['secondperson'];
				if($first == $id){
					echo $second;
				}
				else{
					echo $first;
				}
				
			}
		}
	}
}
?>