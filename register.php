<?php

///*

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$con =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$id = $_SESSION['id'];
$color;
$stmt = $con->prepare('SELECT colors FROM accounts WHERE id =?');

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($color);
$stmt->fetch();
$stmt->close();

//*/

if ($color != NULL){
	$color = explode(";", $color);
	$colora= color[0];
	$colorb= color[1];
	$colorc= color[2];
	$colord= color[3];
	$colore= color[4];
	$colorf= color[5];
	
	$colora2= color[6];
	$colorb2= color[7];
	$colorc2= color[8];
	$colord2= color[9];
	$colore2= color[10];
	$colorf2= color[11];
	
	$colora3= color[12];
	$colorb3= color[13];
	$colorc3= color[14];
	$colord3= color[15];
	$colore3= color[16];
	$colorf3= color[17];
	
	$colort = color[18];
	$fontSize = [19];
	
}
else{
	$colora= "#ababab";
$colorb= "#bcbcbc";
$colorc= "#cdcdcd";
$colord= "#dcdcdc";
$colore= "#ededed";
$colorf= "#dfdfdf";

$colora2= "#0a0a0a";
$colorb2= "#1b1b1b";
$colorc2= "#2c2c2c";
$colord2= "#3d3d3d";
$colore2= "#4e4e4e";
$colorf2= "#5f5f5f";

$colora3= "#a3a3a3";
$colorb3= "#b2b2b2";
$colorc3= "#c1c1c1";
$colord3= "#d1d1d1";
$colore3= "#e2e2e2";
$colorf3= "#f3f3f3";



$colort = "#000000";
$fontSize = "14";
}?>
<!DOCTYPE html>
<meta name="viewport" content="width=device-width">
<meta charset="utf-8">
<html class = "tabletime">
	<head class = "html">
		<link href="style.php" rel="stylesheet" type="text/css">
		<h1><b><a href="home.php">TABLETIME</a></b></h1>
<a href = "login.php"><h2>Login</h2></a>
		<title>tabletime</title>
	
			


<body class = "content">
<div>
    		
<?php include 'newuser.php'; ?>

			<h1>tabletime<br></h1>
<p>enter your name and code, msg<br>and solve the puzzle to access tabletime</p>
        <form method="post"action="newuser.php" autocomplete="off">
				<label for="username">
				
				</label>
				<input type="text" name="username" placeholder="name" id="username" required><br>
				<label for="password">
				
				</label>
				<input type="password" name="password" placeholder="code" id="password" required>
	<input type="email" name="email" placeholder="msg" id="email" required><br>
				<label for="email">
									</label>
			
				
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label for="quiz" 
                                   class="col-sm-3 col-form-label">
                                <?php echo $num1 . '+' . $num2; ?>
                            </label>
                            <div>
                                <input type="hidden" 
                                       name="no1" 
                                       value="<?php echo $num1 ?>">
                                <input type="hidden"
                                       name="no2" 
                                       value="<?php echo $num2 ?>">
                                <input type="text" 
                                       name="test"
                                       class="form-control quiz-control" 
                                       autocomplete="off"
                                       id="test" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" name= "submit" id = "submit" value="Register">
        </form><br>

		
</th></table>
	</div>

       
<h1>How It Works - SAVE THIS TEXT</h1><br>
	tabletime, while a shockingly detailed social algorithm, <br>obtains its beauty from rather simple effects. the majority <br>of the work is done through tags.<br>
	tags can be modified in their index values by unique <br>numeric parameters. You can look up and see all useful <br>calculated stats and how by searching anything you want. Tags <br>are considered, modified and delimited where a regular tag <br>value is 1, but with the exception that it is considered a <br>measureless topic tag, automatically factored as 1-to-1 in a <br>separate group from other tags. For example, if you posted a <br>picture of food that got done before the clams and soup with <br>appetizers, and wanted people to find your post only if they <br>were likely to want to show up on time and have a <br>predictable history of doing so;<br><br>
	...you make a post that says:<br>
'at 5:00 I'm going to table. Make sure you're not late for <br>dinner but please don't worry either way. I made food. Respect <br>my table and cooking skills. Don't be late or early. Also <br>just me: Do you really think forks matter? If so, eat off my <br>porch because I'm not having dinner with you.' <br><br>
If the tags are:<br>
'food=0.5; table; fork <=7500; late=-15000 - 7500; food; -<br>late; -early;time; time = +15000 - 7500; 5:00 + 7500; dinner = <br>2; -angry; '.<br><br>
	...The first thing to know about your post and where it <br>will be and how it will help you find friends, is tags go <br>into two groups which are weighted one-to-one in searches <br>and content streams: numeric and (versus) non numeric.<br>
	The tags in this post imply that this post is about <br>someone who will make dinner at 5:00 at the table, that it will <br>be food and while that's essential and mutually inclusive to <br>the fact that it's on the table... because they also typed <br>'food=0.5' it would remove three quarters the value of any <br>number tags valued 2, and half that of 1, and one sixth that <br>of a tag the value of 3. <br><br>
	In the list of tags, tags and their values can be <br>negative. If the value is negative the post is repelled and will <br>be less visible from content with the tag itself. The tag '-<br>angry;' implies that your content will be one tag less <br>similar to a post with 'angry;' as a tag and also a step less <br>visible unless '-angry;' is also included. In the list of <br>tags it is more likely to be associated with the other <br>content or users if they have a low or negative value to the <br>tag. This math applies the tag will consider the negativity <br>of '-late;', 'early-;' and '-angry;', so that if 'angry;' <br>plus 'late;' or 'early;' with or without '-early;' and '-<br>late;' exists on another user or post, unless tags or <br>content '+food;' and 'table+;' plus at least 1/3 of the numeric <br>tags' total value is is present, there is no net attraction <br>to the content in posts or searches (or even negative). Just <br>remember that if they are outnumbered, the sum of tags of <br>either numeric or non-numeric quality side of this one-to-<br>one will scale (after - tags are compared with + tags in <br>numberless, regardless of how many others to at most 50% of the <br>proportionate tag value, if including even just one tag in <br>its group. The post also implies 'fork' contributes a tag <br>value equal to being late, unless people care about them <br>more than the contribution of the value 7500 to the rest of <br>the post in general.<br><br>
	External users get to rate a post and that effects its <br>tags. Dependant variables on the outsideof a global, group <br>or user post can be assigned to any tag plus the general <br>quality about the post. Votes on the quality of a post, <br>(given as a solid continuous good/bad slider tool)  transfer in <br>proportion to each each tag value. The user has the ultimate <br>control to set the slider on their own post to vote the given <br>value of their post and tags to a one-to-one vote <br>against all other users. Because the user votes for the score of <br>thequality of a post, and it is their own to decide whether <br>they feel the  such as in the example '+time = 15000 - <br>7500; 5:00 = 0 - 7500; dinner=-7500 -7500;late=-7500 +7500;' <br>it would mean that being late will be even more positive than <br>negative to content with '-late,' neutral with 'late;', <br>and negative against '+late;' by a score of 7500. On the slider, <br>'good' will go in the direction of the arithmetic +/- sign <br>and 'bad will go in the reverse direction.<br>
 <i> Join today and get involved in the fun!<br></i><br><br><br><br><br><br><br><br>

<b>Rules,  plus How We Decide About You</b><br><br>
	End users keep some small limited IP tables of is online <br>and split and help coordinate verification between Host and <br>Admin servers. They authenticate and are voted on their <br>reliability over time via other Dedicated Hosts as well as <br>resitricted by the ability of Admin Servers to dissimilate <br>conflict data manually or by polling the users merging the <br>servers.<br>
Tabletime uses 1minute delays in forming messages, 3 minute <br>delays for group chats and video calls,  30 minute delays for <br>registration and groups,  5 minute delay posts, 10 minute <br>delays for login attempts. Just has to be that way. also<br> for event formation we have a 15 minute delay. We do provide <br>the timestamps for dm, post, etc and lock the buttons but if <br>any successful events (including dms)  happen what we'll do <br>is make it so people can vote ban them for extended periods <br>of time . if they have too many attempts failed  for login <br>or register or post or anything, we  double the delay for <br>that ip. if they have too many votebans or their posts get a <br>negative rating, use a delay factor that moves to infinite <br>time where they cant access the database and so  either <br>delete the posts, messages, account etc to get the timer back <br>down. <br><br>
<i>karma = delay time*((post downvotes * post downvote % from neutral)^2+1) is the delay increase factor under karma. <br> ...unless you delete the karma...<br>
and ((x number of votes * % voteban from neutral)^2 +1)*time = moksha from being votebanned. </i> <br><br>
	To calculate delay a user has for accessing the database, <br>their overall voteban score is in balance with their delay <br>lock score. the delay is given by ((time of action delay) * <br>(number of votes) / their voteban score.) they must delete <br>their content, wait, or they cannot post or register on this <br>ip. finally, votebans are practically permanent, deletes an <br>importable account, and only one account export is permitted <br>when someone does get banned - these expire at high priority <br>as our servers prefer the freshest, most popular and most <br>active accounts data only. it will send emails of data when <br>it is deleted and accounts.<br>
	We have <a href = https://github.com/wittymoniker/tabletime>software</a> for admin <br>and Dedicated Host as well as user peer hosting software. <br>They are deployable server programs which merge with <br>eachother in a network to expediate requests.<br>
	Admin servers manually merge with other admin servers and <br>use a IP organization scheme to host the IP under any single <br>domain server and link to each other. They are recommended <br>for 1/3 of data servers.<br>
	2/3 of data servers are Host servers which sit on IP <br>tables and do the same thing based on connected member webs, <br>they can be trusted to handle much data that is older than <br>the newest data.
<br><br>



</body>




 
</head>

</html>


