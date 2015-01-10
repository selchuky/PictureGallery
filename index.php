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
 		
 		<script type="text/javascript">
 		    /* Setting background image with the help of JQuery */
            $(document).ready(function() {
            $("html").css("background-image", "url(background2.jpg)");
            $("html").css("background-size","100%, 100%");
            });
        </script>
        
        <script type="text/javascript">
            /* Uses regular expression to image name from image source */
            String.prototype.filename=function(extension){
                var s= this.replace(/\\/g, '/');
                s= s.substring(s.lastIndexOf('/')+ 1);
                return extension? s.replace(/[?#].+$/, ''):s;
            }
            
            function showImage(image) {
                var imagePath = 'img/';
                var imageName = image.split('.')[0];
                var imageExtension = imageName.split('.')[1];
                var imageUrl = imagePath + image;
            //  That would be an alternative to regular expression
            //  var src = imageName; // "static/img/flower.jpg"
            //  var tarr = src.split('/');      // ["static","img","flower.jpg"]
            //  var file = tarr[tarr.length-1]; // "flower.jpg"
            //  var data = file.split('.')[0];  // "flower"
            //    alert("ImageName: "+ imageName + " URL: " + imageUrl);
                view(imageUrl, imageName);
            }
            
            /* Shows the image on a new window */
            function view(imageUrl, imageName) {
              window.open(imageUrl, imageName);
            }
        </script>
        
		<title>Pikturefarm.com</title>
	</head>

	<body>
		<div id="header">
			<h2 class="mainHeader">All Kind of Pictures</h2>
		</div>
		
		<div id="navigation">
			<ol>
				<li>
					<a href="about.php">About</a>
				</li>
					
				<li>
					<a href="login.php">Login</a>
				</li>
					
				<li>
					<a href="index.php">Home</a>
				</li>
			</ol>
		</div>
		
		<div id="container">
			<div id="content">
				<?php
				  $imgList = getImagesFromDir($imagePath);
				  foreach($imgList as $image) { ?>
				  	<img src="<?php echo $imagePath.'/'.$image; ?>" alt="Test" class="currentImage" onclick="showImage(this.src.filename())"/> 
				 <?php } ?>
			</div>
		</div>

		<footer id="copyrights">
			Copyright © 2015 Pikturefarm.com
		</footer>

	</body>

</html>