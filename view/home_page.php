<html>
	<head>
		<link rel='stylesheet' type="text/css" href='bootstrap/css/bootstrap.css'>
		<style>
			.container{
				margin-top:20px;
			}
			input[type="text"]{
				height:40px;
				width:100%;
			}
			.time{
				color:gray;
			}
			.active{
				color:gray;
			}
		</style>
	</head>
	<body>
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
					<div>
						<b>Post a Status Message</b> 		
						<input type="text" id="status" placeholder="Type a Status Message...">
						<button class="btn btn-primary" id="post">Post</button>
					</div>
					<hr>
					<div id="posts"></div>
				</div>
			</div>
			
		</div>
	</body>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script>
		var name = "<?php echo $_SESSION['name']; ?>";
		var id = <?php echo $_SESSION['id']; ?>;
		
		$(document).ready(function(){
		$.post('home.php', {action: 'getposts', id: id, type:'allfriends'}, function(callback){
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

		$('#post').click(function(){
			var content = $('#status').val();
			if(content != ''){
				$.post('home.php', {action: 'poststatus', content: content}, function(data){
					var post = "<div><a href='profile.php?id=" + id + "'>" + name + "</a> " +  content +"<br><span class='time'> Just Now <span><hr></div>";
					$('#posts').prepend(post);
				});
				$('#status').val('');
			}
		});
		
	</script>
</html>
