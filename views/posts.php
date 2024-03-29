<?php
	session_start();
	session_regenerate_id();
	if(!isset($_SESSION['username'])) {
		header("Location:../views/access_denied.html");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Posts</title>
		<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../css/posts.css">
		<link rel="stylesheet" type="text/css" href="../css/navigation.css">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	</head>

	<body >
		<div class="row">
		<?php
			include "../engine/db_connect.php";
 			$db_conn = new DBConnection();
 			$url = $_SERVER['QUERY_STRING'];
			?>
			<div class="col col-lg-8">
				<div id='cssmenu'>
				<?php
				include_once "../entities/user.php";
				$username = $_SESSION['username'];
				$users = $db_conn -> getUsers();
				foreach ($users as $value);
				$userId = $value -> getId();
				?>
					<ul>
					   <li class='active'><a href='posts.php?id=main'>Home</a></li>
					   <li><a href='posts.php?id=Lost'>Lost</a></li>
					   <li><a href='posts.php?id=Found'>Found</a></li>
						 <li><a href='post-create.php'>Create</a></li>
						 <li><a href='user-profile.php'><?php echo $username;?></a></li>
					   <li><a href='#'>About</a></li>
					   <li><a href='#'>Contact</a></li>
						 <li><a href='../engine/sign_out.php'>Sign out</a></li>
					</ul>
				</div>
			</div>
			<div class="col col-lg-4">
				<div class="ui-widget">
  				<input id="search-box">
				</div>
				<button id="search-btn" onclick="loadByAjax()"><span class="glyphicon glyphicon-search"></span></button>
			</div>
		</div>

		<div class="title">
			<img src="../images/Black-Logo.png" >
 		</div>

 		<div id="container">

 			<?php
 				$type = $_GET['id'];
				$posts = $db_conn -> getAllPosts($type);

				function LimitCharacter($description,$limit = 20)
				{
    			if (strlen($description) > $limit)
    			{
        			$description = substr($description, 0, strrpos(substr($description, 0, $limit), ' ')) . '...';
        			return $description;
    			}
    			else
    			{
       				 return $description;
    			}
				}
 			?>
 			 <?php foreach ($posts as $value):  ?>
 			 <div class="post-box" >
 			 <div class="post-title">
 			 <?php
 			 	include_once "../entities/post.php";
 			 	$id = $value -> getID();
 			 	$title = $value -> getTitle();
 			 	$description = $value -> getDescription();

 			 ?>
 		     <a href="post-view.php?id=<?php echo $id?>"><h1 class="post-title" id="$id"><?php echo $title ?></h1></a>
 		     </div>
 			 <hr>
 		     <div class="row">
 			 <div class="col col-lg-3">
 			 	<?php
 			 		$image = $value -> getImage();
 			 		if(!empty($image)) {
 			 			echo '<img style="width: 150px; height: 150px;" src="'.$image.'">';
 			 		} else {
 			 			echo '<img style="width: 150px; height: 150px;" src="../images/Post-image.jpg">';
 			 		}
 			 	?>
 			 </div>
 			 <div class="col col-lg-8">
 			 <p id="p_description"><?php echo LimitCharacter($description,50) ?></p>
 			 </div>
	 		</div>
	 		</div>
	 		<?php endforeach; ?>

	 		<script src="../js/jquery-3.1.1.js"></script>
	 		<script src="../js/navigation.js"></script>
			<script src="../js/jquery-ui.js"></script>
			<script type="text/javascript">
				$(function() {
					var availableTags = <?php echo json_encode($db_conn->getPostTitles());?>;
					$("#search-box").autocomplete({
							source: availableTags
					});
				});
  		</script>
			<script src="../js/search.js"></script>
   	</body>
</html>
