<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>WhoMentioned.me</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
 
    <!-- Loading Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="../css/flat-ui.css" rel="stylesheet">
    
	<!--  Loading 'Satisfy' Font    -->
    <link href='http://fonts.googleapis.com/css?family=Satisfy' rel='stylesheet' type='text/css'>
    
    <style type="text/css">
        
      img {border-width: 0}
      * {font-family: 'Helvetica Neue', Arial, sans-serif;}
      html {
	      padding: 0 0 0 0;

      }
      body {
	      background-color: #ECF0F1;

      }
      h1 {
	      margin-top: 0;
      }
      li {
	      padding-top: 7px;
	      padding-bottom: 7px;
	      list-style-type: none;
	      font-family: Lato;
      }
      #top {
	      text-align: center;
	      background-color: #2C3E50;
	      height: 630px;
	      margin-bottom: 80px;
      }
      #bottom {
	      color: #2C3E50;
	      font-size: 1.2em;
	      font-family: Lato;
      }
      #bottom ul {
	      margin-top: 100px;
	      
      }
      #topText {
	      padding-top: 180px;
      }
      .byline {
	      color: #ECF0F1;
	      font-size: 20px;
	      font-weight: 100;
      }
      #signin {
	      padding: 15px;
	      display: block;
	      margin: 0 auto;
	      max-width: 200px;
	      min-height: 50px;
	      line-height: 50px;
      }
      #threshold {
	      width: 45px;
	      height: 5px;
      }
      .byline a {
	      color: #ECF0F1;
      }
      .headText {
	      color: #ECF0F1;
      }
      .mention {
	      margin-bottom: 3px;
	      min-height: 200px;
	      font-size: 2.5em;
	      line-height: 55px;
	      font-weight: 100;
      }
      .mentioner {
	      font-size: 2.3em;
	      font-weight: bold;
	      }
	  .mentioner a {
	      text-decoration: none;
	      color: #3498DB;
	  }
	  .mentioner a:hover{
		  color: #9B59B6;
	  }
	  .actions {
		  margin-left: 10px; 
		  margin-bottom: 8px;
	  }
	  .avatar {
		  margin-bottom: 27px;
		  border: 3px solid #3498DB;
	  }
    </style>
  </head>
  <body>
  
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-11962204-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

  </script>
  
	<!-- Later: List names of people who mentioned you, Bar graph on the right side, maybe with an animation :)   -->

 
    <div id="top">
    	<div id="topText">
	      <h1 class="headText demo-logo">Who Mentioned Me?<small style="color: #3498DB; font-size: 35px; font-family: 'Satisfy', cursive;">Recent Mentions from Influential People</small></h1>
	      <p class="byline" style="padding-top: 50px;">by @<a href="http://www.mikelipson.com" target="_blank">rockitude</a></p>
	      
	      <?php
	      	if(gettype($content) == "array"){
	      		displayThresholdForm();
			}
		  ?> 

	      </form>
    	</div>
      
      <?php if (isset($menu)) { ?>
        <?php echo $menu; ?>
      <?php } ?>
    </div>
    
    
    <?php if (isset($status_text)) { ?>
      <?php echo '<h3>'.$status_text.'</h3>'; ?>
    <?php } ?>
    
    
    <p>
      <div class="container" id="bottom">
      	
	        <?php
	        	
	        	if(isset($_POST['threshold'])){
					$threshold = $_POST['threshold'];
				}
				else{
					$threshold = 500;
				}
					        	
	        	if(gettype($content) == "array"){
	        		//display list of mentions
	        		displayMentionList($content, $threshold);
	        	}
	        	else{
	        		//display Sign In button
		        	//echo "<div>" . var_dump($content) . "</div>";
		        	echo "<div>" . $content . "</div>";
	        	}
	        	
	        	//PHP Functions
	        	function displayThresholdForm(){
    			    echo "<form name='threshold_form' method='post'><p class='byline'>'Influential' means more than <input id='threshold' name='threshold' type='text' width='1px' placeholder='500'> followers.</p>";
					echo "<input type='submit' class='btn btn-large'>";
					return true;
	        	}
	        	
	        	function displayMentionList($content, $threshold){
		        	foreach($content as $key => $val){
		        		if($content[$key]->user->followers_count > $threshold){
		        			//open div
				        	echo "<div class='mentioner'>";
				        	
				        	//mentioner's avater
				        	echo "<img src='" . $content[$key]->user->profile_image_url_https . "' class='avatar' />&nbsp";
				        	
				        	//mentioner's name and link
				        	echo "<a href='http://twitter.com/";
				        	echo $content[$key]->user->screen_name . "' >";
				        	echo $content[$key]->user->screen_name . "</a>";
				        	
				        	//view context button
				        	echo "<a href='http://twitter.com/" . $content[$key]->user->screen_name . "/status/";
				        	echo $content[$key]->id . "' class='btn actions' style='color: #ECF0F1; background-color: #3498DB'>";
				        	echo "<span class='fui-bubble-16'>&nbsp Context</span></a></div>";
				        	echo "\r\n";
				        	echo "<div class='mention'>" . $content[$key]->screen_name . $content[$key]->text . "</div>";
				        	
			        	}
		        	}
	        	return true;
	        	}
	        ?>
      </div>
    </p>

  </body>
</html>
