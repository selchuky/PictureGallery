<!DOCTYPE html>
<?php
include_once 'resources/functions.php'; 
?>
<html lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
		<!-- for mobile friendly start -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- import Google's hosted JQuery library -->
 		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 		<!-- add the CSS file reference -->
 		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<title>PiktureƧ</title>
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