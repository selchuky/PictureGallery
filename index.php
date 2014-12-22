<!DOCTYPE html>
<?php
include_once 'sites/php/functions.php'; 
?>
<html lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
		<!-- for mobile friendly start -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- import Google's hosted JQuery library -->
 		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<title>PiktureƧ</title>

		<style>
			* {  /* selects every tag on page */
  				-webkit-box-sizing: border-box;
     			-moz-box-sizing: border-box;
          		box-sizing: border-box;
          		max-width: 1000px; /* improves the browser handling of small windows */
				margin: 0;
				padding: 0;
				font-family: Tahoma, Geneva, sans-serif;
			}

			html, body {
				margin-right: auto;
				margin-left: auto;
				padding: 0;
				height: 100%;
				width: 100%;
				background-color: #567;
				color: #FFFFFF;
			}
			
			#header {
				width: 77%;
				float: left;
			}

			.mainHeader {
				!text-align: center;
				text-shadow: 4px 5px 3px #000;
				float: left;
				margin: 0px 0px 8px;
				padding: 8px;
				color: #FFFFFF;
			}
			
			#navigation {
				width: 23%;
				float: right;
				font-weight: bold;
				text-shadow: 4px 5px 3px #000;
			}
			
			#navigation ol{
				padding: 8px 8px;
				margin: 0px;
				position: fixed;
			}

			#navigation li {
				float: right;
				display: inline;
				padding: 8px 8px;
			}
			
			#navigation li a {
				text-decoration: none;
				padding: 8px;
				color: #FFFFFF;
			}

			#navigation li a:hover {
				color: #F90;
			}
			
			#container {
				width: 100%;
				margin: 10px auto;
			}
			
			.images {
				margin:3px;
				height: 240px;
				width: 324px;
				padding: 1px;
				border: 1px solid white;
			}
			
			#content {
				background-color: #567;
			}

			#copyrights {
				margin: 5px auto;
				position: fixed;
				bottom: 0px;
				width: 100%;
				text-align: center;
				font-family: serif;
				font-size: 11px;
				font-style: oblique;
				color: #FFFFFF;
			}

		</style>
		<!--	<link rel="stylesheet" type="text/css" href="/css/style.css"/>  -->
	</head>

	<body>
		<div id="header">
			<h2 class="mainHeader">All About Pictures</h2>
		</div>
		
		<div id="navigation">
			<ol>
				<li>
					<a href="#">Home</a>
				</li>
					
				<li>
					<a href="#">Login</a>
				</li>
					
				<li>
					<a href="#">About</a>
				</li>
			</ol>
		</div>
		
		<div id="container">
			<div id="content">
				<?php
				  $imgList = getImagesFromDir($imagePath);
				  foreach($imgList as $image) { ?>
				  	<img src="<?php echo $imagePath.'/'.$image; ?>" alt="Test" class="images"/> 
				 <?php } ?>
			</div>
		</div>

		<footer id="copyrights">
			Copyright © 2014 PiktureS
		</footer>

	</body>

</html>