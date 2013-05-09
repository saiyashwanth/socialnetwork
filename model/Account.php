<?php

class Account{

	private function account_exists($email){
		$query_result = mysql_query("SELECT id FROM accounts WHERE email='$email'");
		return (is_null($query_result)) ? false: (mysql_num_rows($query_result) > 0);
	}
	
	public function validate_password($email,$password){
		return mysql_num_rows(mysql_query("SELECT id FROM accounts WHERE email='$email' AND password='$password'")) == 1;
	}
	
	public function create_account($name, $email, $password, $birthday, $school){
		if($this->account_exists($email) == false){
			mysql_query("INSERT INTO accounts (name, email, password, birthday, school) VALUES ('$name', '$email','$password', '$birthday', '$school')");
			return true;
		}
		else{
			echo "This email has already been taken";
			return false;
		}
	}
	
	public function search($content){
		$query = mysql_query("SELECT * FROM accounts where name='$content' OR email='$content' OR school='$content'");
		if($query){
			while($row = mysql_fetch_array($query)){
				echo "<a href='profile.php?id=".$row['id']."'>".$row['name']."</a><br>";
			}
		}
	}
	
	public function get_info($email){
		$name_arr = mysql_fetch_array(mysql_query("SELECT * FROM accounts WHERE email='$email'"));
		return $name_arr;
	}
	
	public function get_info_with_id($id){
		
		$name_arr = mysql_fetch_array(mysql_query("SELECT * FROM accounts WHERE id='$id'"));
		return $name_arr;
	}
	public function get_id($email){
		$id_arr = mysql_fetch_array(mysql_query("SELECT id FROM accounts WHERE email='$email'"));
		return $id_arr['id'];
	}
    
	public function login($email,$password){
		return $this->account_exists($email) && $this->validate_password($email, $password);
	}
	
	public function logout(){
		session_unset();
	}
	public function change_name($name){
		$my_id = $_SESSION['id'];
		mysql_query("UPDATE accounts set name = '$name' WHERE id='$my_id'");
	}
	
	public function change_password($password){
		$my_id = $_SESSION['id'];
		mysql_query("UPDATE accounts set password = '$password' WHERE id='$my_id'");
	}
	
}

?>
