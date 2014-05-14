<?php 
	//!Top PHP Section

	$server = 'localhost';
	$database = 'mikelips_commentbuilder';
	$DBuserName = 'mikelips_me';
	$DBpassword = 'macr0micr0';

	$username = $_POST["username"];
	$username = addslashes($username);
	$passAttempt = $_POST["password"];
	$newUsername = $_POST["newUsername"];
	$newPassword = $_POST["newPassword"];
	$retypeNewPassword = $_POST["retypeNewPassword"];
				          
	$con = mysql_connect($server, $DBuserName, $DBpassword);
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	  
	mysql_select_db($database);

	//if the user wanted to log in
	if($_POST["login"]){
		//fetch their password from the database, set as $password
		$sql = "SELECT password FROM users WHERE username = '$username'";
		$password = mysql_query($sql, $con);
		$password = mysql_fetch_row($password);
		$password = $password[0];
		//alert appears below if password doesn't match
	}
	
	//if the user wanted to register a new account
	if($_POST["register"]){
		$username = $newUsername;
		$password = $newPassword;
		
		//check if username exists
		$sql = "SELECT username
				FROM users
				WHERE username = '$username'";
				
		$doesUserExist = mysql_query($sql, $con);
		$doesUserExist = mysql_fetch_row($doesUserExist);
		$doesUserExist = $doesUserExist[0];
		
		if($retypeNewPassword == $password){
			$mismatchedPassword = false;
			if($username && $doesUserExist && $doesUserExist == $username){ //if the username is already in use (and not blank)
				//pop an alert
				$userExists = true;
			}
			else{ //if the username is new
				//add user to users table
				$sql = "INSERT INTO users (username, password)
						VALUES ('$username','$password')";
				$accountCreated = mysql_query($sql, $con);
				//create new table for this user's buttons
				
			}
		}
		else{
			$mismatchedPassword = true;
		}
	}
	
	mysql_close();	
?>

<!DOCTYPE html>
<html lang="en">
  <head>   
  	<meta charset="utf-8" />
    <title>Comment Builder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Automatic comment builder for teachers." />
    <meta name="author" content="Mike Lipson" />

    <!-- Le styles -->
    <link href="./assets/css/bootstrap.css" rel="stylesheet" />
    <link href="./assets/css/docs.css" rel="stylesheet" />
    <link href="./assets/css/prettify.css" rel="stylesheet" />
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
      li {
      	list-style: none;
      }
      .btn {
      	margin: 1 0;
      }
    </style>
    <link href="./assets/css/bootstrap-responsive.css" rel="stylesheet" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="./assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./assets/ico/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="./assets/ico/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="./assets/ico/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="./assets/ico/apple-touch-icon-57-precomposed.png" />
        
  </head>

  <body>

  	<!-- !Black Bar -->
  	
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">CommentBuilder</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <!-- <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li> -->
              <li><a data-toggle="modal" href="#login" >Log In</a></li>
              <li><a data-toggle="modal" href="#register" >Register</a></li>
              <li><a data-toggle="modal" href="#edit" >Add/Edit Comments</a></li>
              <?php
              	if($passAttempt != $password){
	              	  //echo("<li><a>Not logged in</a></li>");
	              }
	              else{
		              echo("<li><a>" . $username . "</a></li>");
	              }
              ?>
            </ul>
          </div><!-- end .nav-collapse -->
        </div> <!-- end .container -->
      </div> <!-- end .navbar-inner -->
    </div> <!-- end .navbar .navbar-fixed.top -->
    
    <!-- !Log In Window -->
    
    <div class="modal hide" id="login">
	  <div class="modal-header">
	    <button class="close" data-dismiss="modal">×</button>
	    <h3>Log In</h3>
	  </div> <!-- end .modal-header -->
	  <form action="#" method="post">
		  <div class="modal-body">
		    <label>Username</label>
		    <input type="text" name="username" />
		    <label>Password</label>
		    <input type="password" name="password" />
		    <input type="hidden" name="login" value="true" />
		  </div> <!-- end .modal-body -->
	  <div class="modal-footer">
	    <!-- <a href="#" class="btn">Close</a> -->
	    <button type="submit" class="btn btn-primary">Log In</a>
	  </div> <!-- end .modal-footer -->
	  </form>
	</div> <!-- end #login -->

	<!-- !Registration Window -->

	<div class="modal hide" id="register">
	  <div class="modal-header">
	    <button class="close" data-dismiss="modal">×</button>
	    <h3>Register</h3>
	  </div> <!-- end .modal-header -->
	  <form action="#" method="post">
	  <div class="modal-body">
	    <p>Desired Username</p>
	    <input type="text" name="newUsername">
	    <p>Password</p>
	    <input type="password" name="newPassword">
	    <p>Retype Password</p>
	    <input type="password" name="retypeNewPassword">
	    <input type="hidden" name="register" value="true" />
	  </div> <!-- end .modal-body -->
	  <div class="modal-footer">
	    <!-- <a href="#" class="btn">Close</a> -->
	    <button type="submit" class="btn btn-primary">Register</a>
	  </div> <!-- end .modal-footer -->
	</div> <!-- end #register -->

	<!-- !Add/Edit Comments Window - This may change -->

	<div class="modal hide" id="edit">
	  <div class="modal-header">
	    <button class="close" data-dismiss="modal">×</button>
	    <h3>Add/Edit Comments</h3>
	  </div> <!-- End .modal-header -->
	  <form action="#" method="post">
	  <div class="modal-body">
	    <p>Button Text</p>
	    <input type="text" name="username">
	    <p>Comment</p>
	    <input type="text" name="password">
	    <p>Comment Type</p>
	    <!-- Segmented Button - Choose Color -->
	    <div class="btn-group" data-toggle="buttons-radio">
	      <button class="btn btn-success">Success</button>
	      <button class="btn btn-warning">Warning</button>
	      <button class="btn btn-danger">Danger</button>
	    </div> <!-- End Button Group -->
	    </div> <!-- End .modal-body -->
	  <div class="modal-footer">
	    <!-- <a href="#" class="btn">Close</a> -->
	    <a href="#" class="btn btn-primary">Save and Close</a>
	    <a href="#" class="btn btn-primary">Save and Add Another</a>
	  </form>
	 </div> <!-- End .modal-footer -->
	 </div> <!-- end #edit -->

	<!-- !Page Content -->

    <div class="container">

	<header class="jumbotron masthead">
      <h1>Comment Builder</h1>
      <p>Use the buttons below to build and copy your comment.</p>
      <!-- !Alerts -->
      <?php
      
      	//!Login Alerts
      	if($_POST["login"]){
	      	if($passAttempt != $password){
		      	$message = "<div class=\"alert alert-error\"><button class=\"close\" data-dismiss=\"alert\">×</button><strong>Warning!</strong> Incorrect username or password. Please try again.</div>";
		      	echo($message);
	      	}
      	}
      	
      	//!Registration Alerts
      	if($_POST["register"]){
	      	if($userExists){
		      	$message = "<div class=\"alert alert-error\"><button class=\"close\" data-dismiss=\"alert\">×</button><strong>Warning!</strong> The username you're trying to register is already in use. Please try another one. </div>";
		      	echo($message);
	      	}
	      	if($accountCreated){
		      	$message = "<div class=\"alert alert-success\"><button class=\"close\" data-dismiss=\"alert\">×</button><strong>Success!</strong> Your new account has been created. </div>";
		      	echo($message);
	      	}
	      	if($mismatchedPassword){
		      	$message = "<div class=\"alert alert-error\"><button class=\"close\" data-dismiss=\"alert\">×</button><strong>Warning!</strong> Your two password entries didn't match. Please try registering again.</div>";
		      	echo($message);
	      	}
      	}
      ?>
      </header>
      
      
      <div class="row">
      	
      	<div class="span11">
      		<div class="well">
      			<p id="well"></p>
      		</div> <!-- end #well -->
      	</div> <!-- end #span11 -->
      	
      	<div id="sideButtons">
      	<ul>
      	<li><a class="btn" id="copyButton">Copy</a><ul>
      	<li><a class="btn btn-inverse" onclick="document.getElementById('well').innerHTML = ''">Clear</a></li>
      	</ul>
      	</div> <!-- End #sideButtons -->
      	
      	
      </div> <!-- End #row -->
      
      <div class="row">
      	<div class="span4">
      		<!-- Success Button Area -->
      		<p>
      		      		
      		<?php makeButton("Excellent Job", "Excellent Job!", 1) ?>
      		<?php makeButton("Great Job", "Great Job!", 1) ?>
      		</p>
      		</div> <!-- end #span4 -->
      		
      		<div class="span4">
      		<!-- Warning Button Area -->
      		<p>
      		<?php makeButton("Missing Documentation", "You didn\'t include the required documentation with your project.", 2); ?>
      		<?php makeButton("Use Quantize", "Your project would likely benefit from use of the Quantize function in Garageband." , 2); ?>
      		</p>
      		</div> <!-- end #span4 -->
      		
      		<div class="span4">
      		<!-- Failure Button Area -->
      		<p>
      		<?php makeButton("Missed Important Aspect", "You missed an important aspect of this assignment. ", 3); ?>
      		<?php makeButton("Wrong Format", "The file you submitted is in the wrong format. ", 3); ?>
      		</p>
      		
      	</div> <!-- end #span4 -->
      	
      	</div> <!-- end #row -->
      	
    </div> <!-- end #container -->
    
    <!-- PHP Functions -->
    
	<?php
	function makeButton($name, $comment, $type){
		switch($type){
			case 1:
				echo("<a class=\"btn btn-success\" onclick=\"textToWell('" . $comment . "')\">" . $name . "</a>");
			break;
			case 2:
				echo("<a class=\"btn btn-warning\" onclick=\"textToWell('" . $comment . "')\">" . $name . "</a>");
			break;
			case 3:
				echo("<a class=\"btn btn-danger\" onclick=\"textToWell('" . $comment . "')\">" . $name . "</a>");
			break;
			default: 
				echo("Something went wrong at line 269.");
		}
		return true;
	}
	?>


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="./assets/js/jquery.zclip.js"></script>
    <script src="./assets/js/bootstrap.js"></script>
    
    <script>
    $(document).ready(function(){
        
    	$('#copyButton').zclip({
    		path:'./assets/js/ZeroClipboard.swf',
    		copy:function(){return $('p#well').text();}
    	});
    
    	// The link with ID "copy-dynamic" will copy the current value
    	// of a dynamically changing input with the ID "dynamic"
    
    });
	</script>
	
	<script>
		function textToWell(text){
			document.getElementById('well').appendChild(document.createTextNode(text));
		}
	</script>
		
	    
    <!--
    <script src="./assets/js/bootstrap-transition.js"></script>
    <script src="./assets/js/bootstrap-alert.js"></script>
    <script src="./assets/js/bootstrap-modal.js"></script>
    <script src="./assets/js/bootstrap-dropdown.js"></script>
    <script src="./assets/js/bootstrap-scrollspy.js"></script>
    <script src="./assets/js/bootstrap-tab.js"></script>
    <script src="./assets/js/bootstrap-tooltip.js"></script>
    <script src="./assets/js/bootstrap-popover.js"></script>
    <script src="./assets/js/bootstrap-button.js"></script>
    <script src="./assets/js/bootstrap-collapse.js"></script>
    <script src="./assets/js/bootstrap-carousel.js"></script>
    <script src="./assets/js/bootstrap-typeahead.js"></script>
    -->

  </body>
</html>
