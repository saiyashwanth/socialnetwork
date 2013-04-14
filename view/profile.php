<html>
	<head>
		<link rel='stylesheet' type="text/css" href='bootstrap/css/bootstrap.css'>
		<style>
			.container{
				margin-top:20px;
			}
			input[type="text"], input[type="date"]{
				height:40px;
				width:300px;
			}
		</style>
	</head>
	
	<div class="container">
		<div class="row">
				<div class="span10">
					<h4> Welcome, <?php echo $_SESSION['name']; ?> </h4>
				</div>
				<div class="span2">
					<h4><a href="logout.php">Log Out </a></h4>
				</div>
		</div>
		<div class="row" >
			<div class="span4" id="sidebar">
				<a href="home.php">Home</a><br>
				<a href=<?php echo 'profile.php?id='.$_SESSION['id'];?>> Profile</a><br>
				<a href="search.php">Search</a><br>
				<a href="invites.php">Invites</a><br>
			</div>
			<div class="span8">
				<div class="row">
					<?php
						if($_GET['id'] == $_SESSION['id']){
							echo "<input type='text' placeholder='Post Status' class ='content' id='status'>";
						}	
						else{
							echo "<input type='text' placeholder='Write something' class ='content' id='postonwall'>";
						}
					?>
					<button class="btn btn-primary" id="post">Post</button>
					<h4>About</h4>
					<b>Name</b> <?php echo $profile_name;?><br>
					<b>Email</b> <?php echo $profile_email;?><br>
					<b>Birthday</b> <?php echo $profile_birthday;?><br>
					<b>School</b> <?php echo $profile_school;?><br>
					<?php
						if($_GET['id'] != $_SESSION['id']){
							if($this->friends->should_request($_GET['id'])){
								echo "<button class='btn btn-primary' id='addfriend'>Add Friend</button>";
							}else if($this->friends->has_decision($_GET['id'])){
								echo "<button class='btn btn-success' id='accept'>Accept Friend Request</button>
									  <button class='btn btn-danger' id='deny'>Deny Friend Request</button>
									 ";
							}
							else if(!$this->friends->is_friend($_GET['id'])){
								echo "<button class='btn btn-primary' disabled='disabled'>Friend Request Sent</button>";
							} else if($this->friends){
								echo "<span style='color:green'>Friends </span>";
							}
						}
					?>
					<hr>
					<h4>Posts</h4>
					<div id="posts"></div>
				</div>
			</div>
		</div>
			
		
	</div>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
	var profile_id = <?php echo $_GET['id'];?>;
	my_id = <?php echo $_GET['id'];?>;
	var profile_name = '<?php echo $profile_name; ?>';
	var my_name = '<?php echo $_SESSION['name'] ?>';
	$(document).ready(function(){
		$.post('home.php', {action: 'getposts', id: profile_id, type:'personal'}, function(callback){
			var json_array = jQuery.parseJSON(callback);
			for (var i = 0; i<json_array.length; i++) {
				var row = jQuery.parseJSON(json_array[i]);
				var posterID = row['posterid'];
				var receiverID = row['receiverid'];
				var posterName = row['postername'];
				var receiverName = row['receivername'];
				var postContent = row['content'];
				var timestamp = row['time'];
				var receiver = (receiverID == 0) ? "": "=><a href='profile.php?id=" + receiverID + "'>" + receiverName + "</a> ";
				var post = "<div><a href='profile.php?id=" + posterID + "'>" + posterName + "</a> " + receiver + postContent +"<br><span class='time'>at " + timestamp + "<span><hr></div>";
				$('#posts').append(post);
			}
			});
	});
	$('#addfriend').click(function(){
		$.post('friends.php', {action: 'add', id: profile_id}, function(data){
			$('#addfriend').text('Friend Request Sent').attr('disabled', 'disabled');
		});
	});
	$('#post').click(function(){
		//status or postonwall
		var type = $('.content').attr('id');
		var content = (type == 'status') ? $('#status').val(): $('#postonwall').val();

		if(content != ''){
			if(type == 'status'){
				$.post('home.php', {action: 'poststatus', content: content}, function(data){
					var post = "<div><a href='profile.php?id=" + profile_id + "'>" + my_name + "</a> " +  content +"<br><span class='time'> Just Now <span><hr></div>";
					$('#posts').prepend(post);
				});
			}
			else{
				$.post('home.php', {action: 'postwall', content: content, id: profile_id, receivername: profile_name}, function(data){
					var post = "<div><a href='profile.php?id=" + my_id + "'>" + my_name + "=></a><a href='profile.php?id=" + profile_id + "'>" + profile_name + "</a> " +  content +"<br><span class='time'> Just Now <span><hr></div>";
					$('#posts').prepend(post);
				});
			}
			$('.content').val('');
		}
	});
</script>
	
</html>