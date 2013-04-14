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
					<?php $this->friends->get_friend_requests(); ?>
				</div>
			</div>
		</div>
			
		
	</div>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
	var id = <?php echo $_SESSION['id'];?>;
	var name = '<?php echo $_SESSION['name'] ?>';
	$('#accept').click(function(){
		var id = $(this).parent().attr('id');
		$.post('friends.php', {action: 'accept', id:id}); 
		$(this).parent().remove();
	});
	
	$('#deny').click(function(){
		var id = $(this).parent().attr('id');
		$.post('friends.php', {action: 'decline', id:id}); 
		$(this).parent().remove();
	});
	
</script>
	
</html>