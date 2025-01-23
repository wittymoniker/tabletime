<?php ;?>


<html class = "tabletime">
<link href="style.php" rel="stylesheet" type="text/css">
<head class = "html">
		<meta charset="utf-8">
		<title>TABLETIME</title>
<body class = "content">  


<nav class = "navtop">
		<div class = "tabletime">		

<h1><b>TABLETIME</b></h1>
<p>
<a href="post.php"><i class="fas fa-user-circle"></i>Messages</a>
<a href="create.php"><i class="fas fa-user-circle"></i>Create</a>
<a href="friend.php"><i class="fas fa-user-circle"></i>Friends</a><br>
<a href="file.php"><i class="fas fa-user-circle"></i>Files</a>
<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
<a href="statsmap.php"><i class="fas fa-user-circle"></i>Stats/Map</a>
<a href="group.php"><i class="fas fa-user-circle"></i>Groups</a><br>
<a href="people.php"><i class="fas fa-user-circle"></i>People</a>
<a href="post.php"><i class="fas fa-user-circle"></i>Posts</a>
<a href="event.php"><i class="fas fa-user-circle"></i>Events</a></p><br>




			</div>
</nav>


<div>
	<table>
<form method ="POST">
	<th><br><label name ="range">private/public/global range: </label>"
<input type = "range" id = "view" name = "private/public/global" min = "-256" max = "256">

<br><label name ="index">username: </label>"
<input type = "text" name="index" placeholder = "username">"
</th>
<tr>
<br>
<input type = "submit" value = "submit">
</tr>
</form>
</table>
</div>




</form></form></form></form></form></form>
<form method ="POST">
<label name ="rate"> leave rating (-/+) karma/moksha: </label>"
<input type = "range" id = "perspective" name = "rate" min = "-256" max = "256">
</form>
<br>
</body>
</head>
</html>
