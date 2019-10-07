<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8" />
 <meta name="description" content="Web application development" />
 <meta name="keywords" content="PHP" />
 <meta name="author" content="Your Name" />
 <title>Home Page</title>
 <style>


h1 {
    
    margin-left: 40px;
	text-align: center;
} 
.content
{
	 background-color:DodgerBlue;
	 margin: 20px;
    padding: 20px;
}

label
{
	color: white;
	margin-left:40px;
	
}
p
{
	color: white;
	margin-left:40px;
}
a:link, a:visited {
   color:MediumSeaGreen
    color: white;
    padding: 14px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}


a:hover, a:active {
    background-color: MediumSeaGreen;
}
</style>
</head>
<body>
<h1>My Friend System <br/> Assignment Home Page</h1>
<form action="<php $_PHP_SELF>" method="post">
<div class="content">
<label>Name: Hettiarachchige Manisha Dinithi Silva</label><br/>
<label>Student ID:101143294</label><br/>
<label>Email: 101143294@student.swin.edu.au</Label><br/>
<p>I declare that this assignment is my individual work. I have not worked collaboratively
nor have I copied from any other student’s work or from any other source.</p>
</div>
<a href="signup.php">Sign-Up</a><br/>
<a href="login.php">Log-In</a><br/>
<a href="about.php">About</a>
</form>
</body>
</html>
<?php
 require_once ("settings.php");
 //open the connection
 $con=@mysql_pconnect($host,$user,$pswd)
 or die('Failed to connect to server');
 //use database
 @mysql_select_db($dbnm) or die('Database not available');
 //set up sql string and execute
 //create table friends
 $sqlCreateFriendsString="create table if not exists friends(
friend_id int not null auto_increment,
friend_email varchar(50) not null,
password varchar(20) not null,
profile_name varchar(30) not null,
date_started date not null,
num_of_friends int unsigned,
primary key(friend_id));";

//get the result
if (mysql_query($sqlCreateFriendsString)) 
{
    
//create table myfriends
	$myfriends="create table if not exists myfriends(
friend_id1 int not null ,
friend_id2 int not null)";
 if(mysql_query($myfriends))
 {
	 echo"Tables created successfully.";
 }
 else 
 {
    echo "Error creating table: " . mysql_error($con);
}
} 
else 
 {
    echo "Error creating table: " . mysql_error($con);
}
 
 
 




?>