<!DOCTYPE html>
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
	<body onload="suggestNextGame()">
		<div id="container" class="container text-center">
		<h1>Here's what you should play together:</h1>
		
			<div id="whatToPlay">
				
				<p><span id="gameToPlay" class="lead"></span></p>
				<p><input type="button" id="nextButton" value="Lame. Next!" class="btn btn-large" onclick="suggestNextGame()"></p>
				
			</div> <!-- end whatToPlay -->
			
		</div> <!-- end container -->


<?php
	$userid_p1 = $_GET['userid_p1'];
	
	//examine $userid and decide whether it's a profile or id
	if(isProfile($userid_p1)){
		$url_p1 = buildUrlFromProfile($userid_p1); 
	}
	else{
		$url_p1 = buildUrlFromId($userid_p1);
	}
	
	$games_p1 = simplexml_load_file($url_p1);
					
	//Create empty array to store owned games
	$ownedGames_p1 = array();
	
	//List all owned games
	for($i = 0; $i < count($games_p1->games->game); $i++){
		array_push($ownedGames_p1, $games_p1->games->game[$i]->name);
	}

	$userid_p2 = $_GET['userid_p2'];
	//examine $userid and decide whether it's a profile or id
	if(isProfile($userid_p2)){
		$url_p2 = buildUrlFromProfile($userid_p2); 
	}
	else{
		$url_p2 = buildUrlFromId($userid_p2);
	}
	
	$games_p2 = simplexml_load_file($url_p2);
					
	//Create empty array to store owned games
	$ownedGames_p2 = array();
	//List all owned games
	for($i = 0; $i < count($games_p2->games->game); $i++){
		array_push($ownedGames_p2, $games_p2->games->game[$i]->name);
	}
	
	$jointlyOwnedGames = array_intersect($ownedGames_p1, $ownedGames_p2);
	$jointlyOwnedGames = array_values($jointlyOwnedGames);					//reduces the array indexs to 0 - whatever 				

	//**PHP Functions**
	
	function buildUrlFromId($id){
		$url = 'http://steamcommunity.com/id/';
		$url = $url . $id;
		$url = $url . '/games?tab=all&xml=1';
		return $url;
	}

	function buildUrlFromProfile($profile){
		$url = 'http://steamcommunity.com/profiles/';
		$url = $url . $profile;
		$url = $url . '/games?tab=all&xml=1';
		return $url;
	}

	function isProfile($user){
		if(countDigits($user) == 17 && !hasAlphaChars($user)){
			return true;	
		}
		else{
			return false;	
		}
	}

	function countDigits($user){
			return strlen((string)$user);
	}

	function hasAlphaChars($user){
		$alphas = array('A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h', 'I', 'i', 'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r', 'S', 's', 'T', 't', 'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z'); //consider changing this to a range of ascii chars
		foreach(range(0, count($user)) as $i){
			if(in_array($user[$i], $alphas)){
				return true;
			}	
		}
		return false;
	}
?>

<!-- **JavaScript** -->

<script>
	var remaining = ["<?php echo join("\", \"", $jointlyOwnedGames); ?>"];		//convert PHP array to JS array
	var tried = [];
	var nextBtn = ['Lame.', 'Next!', 'Keep \'em coming.', 'I can do better.', 'Boring.', 'Not cool enough.', 'Try again!', 'Nah.', 'Nope.', 'Uh uh.', 'Moar!', 'Pff.', 'Gross.', 'Eeeewwww.', 'Ugh.', 'Garbage.'];
	
	var suggestNextGame = function(){
		var rand = Math.floor(Math.random() * remaining.length);
		tried.push(remaining[rand]);
		document.getElementById("gameToPlay").innerHTML = "<p>" + remaining[rand] + "</p>";	
		$('#gameToPlay').addClass('animated bounceIn');	
		remaining.splice(rand, 1); //remove the displayed game from 'remaining'
		document.getElementById("nextButton").value = nextBtn[Math.floor(Math.random() * nextBtn.length)];
		
		if(remaining.length == 0){
			document.getElementById("gameToPlay").innerHTML = "<p>You're all out of games!</p>";
			document.getElementById("nextButton").value = "All done.";
		}
	};
	
</script>

<script src="http://code.jquery.com/jquery.js"></script>
<script src="./js/bootstrap.min.js"></script>

	</body>
</html>


