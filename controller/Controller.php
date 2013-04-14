<?php
	include_once("model/Account.php");
	include_once("model/Friends.php");
	include_once("model/Posts.php");
	
	$server_name = "localhost";
	$username="root";
	$password="<REPLACE WITH YOUR MYSQL PASSWORD>";
	$db_name = "socialnetwork";
	
	if(!(@mysql_ping())){
		mysql_connect($server_name, $username, $password) or die ("cannot connect");
		mysql_select_db($db_name) or die ("cannot select database");
	}
	session_start();

	class Controller {
		public $account;
		public $friends;
		public $posts;
		
		public function __construct()  
		{  
			$this->account = new Account();
			$this->friends = new Friends();
			$this->posts = new Posts();
			
		} 
		
		public function index()
		{	
			//Already Logged In
			if(count($_SESSION) > 0){
				header('location:home.php');
			}
			//Logged Out
			else{
				include 'view/logged_out.html';	
			}
		}
			
		public function logout(){
			$this->account->logout();
			header('location:index.php');
			mysql_close();
		}
		
		public function profile($id){
			$information = $this->account->get_info_with_id($id);			
			$profile_name = $information['name'];
			$profile_email = $information['email'];
			$profile_birthday = $information['birthday'];
			$profile_school = $information['school']; 
			include 'view/profile.php';
		}	
		
		public function invites(){
			include 'view/view_invites.php';
		}
		
		public function search(){
			if(array_key_exists('content', $_POST)){
				$content = $_POST['content'];
				$this->account->search($content);
			}
			else{
				include 'view/searchview.html';
			}
		}
		
		public function friends(){
			$action = $_POST['action'];
			$person_id = $_POST['id'];
			
			if($action == 'add'){
				$this->friends->add_friend($person_id);
			} else if($action == 'accept'){
				$this->friends->accept_friend($person_id);
			}else if($action == 'decline'){
				$this->friends->add_friend($person_id);
			}else{
				$this->friends->get_friends($person_id);
			}
			
		}
		
		public function home(){
			
			//Post a status or post on a friend's wall
			if(array_key_exists('action', $_POST)){
				if($_POST['action'] == 'poststatus'){
					$this->posts->status_update($_POST['content'], $_SESSION['id'], $_SESSION['name']);
				}
				else if($_POST['action'] == 'postwall'){
					$this->posts->post_wall($_POST['content'], $_POST['id'], $_POST['receivername']);
				}
				else if($_POST['action'] == 'getposts'){
					$my_id = $_POST['id'];
					if($_POST['type'] == 'allfriends'){
						echo $this->posts->get_friend_posts($my_id);
					}
					else{
						echo $this->posts->get_personal_posts($my_id);
					}
				}

			}
			//Not Logged In
			else if(count($_SESSION) == 0 && count($_POST) == 0){
				header('location:index.php');
			}
			//Already Logged In
			else if(count($_SESSION) > 0 && count($_POST) == 0){
					include 'view/home_page.php';
			}
			else{
				$email = $_POST['email'];
				$password = $_POST['password'];
				
				
				//	public function create_account($name, $email, $password, $birthday, $school){

				//create account
				if( array_key_exists('name', $_POST)){
					$name = $_POST['name'];
					$birthday = $_POST['birthday'];
					$school = $_POST['school'];
					$this->account->create_account($name, $email, $password, $birthday, $school);
					$id = $this->account->get_id($email);
					$_SESSION['email'] = $email;
					$_SESSION['name'] = $name;
					$_SESSION['id'] = $id;
					header('Location:index.php');
				}
				//logging in
				else {
					if($this->account->login($email,$password)){
						$info = $this->account->get_info($email);
						$_SESSION['email'] = $info['email'];
						$_SESSION['name'] = $info['name'];
						$_SESSION['id'] = $info['id'];
								
						header('Location:index.php');

					}
					else{
						echo "Invalid Email or Username";
						mysql_close();
					}	
				}
			}
		}
	
	}
?>
