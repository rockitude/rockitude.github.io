<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>PlayTogether</title>
		<link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="./css/animate.min.css" rel="stylesheet" media="screen">
	<style type="text/css">
		body{
		background-color: black;
		color: #FAFAFA;
		}	
	</style>
	</head>

<body>
	<div class="container">
	<div class="row">
		<h1>What should we play together?</h1>
		<h3>Find games to play with your Steam friends.</h3>
		
		<p class="lead">You sit down with a friend to play a game together, but you're not sure which one to play. You have lots of games, they have lots of games. No worries! Just put your Steam IDs or Profile numbers into the boxes to the right to get some game ideas! Before you click, make sure your Steam profile is public.</p>
	</div>
	<div class="row">
		<form method="get" action="DisplayGames.php" class="form-inline text-center">
		<fieldset>
		
				<label name="userid_p1" for="userid_p1" class="control-label">Player 1</label>
				<input type="text" placeholder="ID or Profile" name="userid_p1" id="userid_p1">

				<label name="userid_p2" for="userid_p2" class="control-label">Player 2</label>
				<input type="text" placeholder="ID or Profile" name="userid_p2" id="userid_p2">
			
				<input type="submit" class="btn">
		</fieldset>
		</form>
	
	</div>
	</div>
<!--
	To Do:
	Find out whether the user's profile is public and warn them if it isn't
-->
<script src="http://code.jquery.com/jquery.js"></script>
<script src="./js/bootstrap.min.js"></script>
	
</body>
<footer class="text-center">
	<small>Powered by <a href="http://steampowered.com">Steam</a></small>.
</footer>
</html>