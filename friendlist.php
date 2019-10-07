<?php
Session_start();
if(isset($_SESSION["profileName"]))
{
	$profileName=$_SESSION["profileName"];
}	
if(isset($_SESSION["LoggedUserID"]))
{
	$userID=$_SESSION["LoggedUserID"];
	
}	


?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8" />
 <meta name="description" content="Web application development" />
 <meta name="keywords" content="PHP" />
  <meta name="author" content="Your Name" />
 <title>Friends List</title>
 <style>


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

input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

</style>
</head>
<body>
<h1><?php echo "<p>My Friend System <br/>".$profileName."s Friends List"."</p>";?></h1>
</body>
</html>
<?php
$email=$_SESSION["email"];
function ConnectToDb()
{
	//get the friends list
require("settings.php");
 //open the connection
 $con=mysqli_connect($host,$user,$pswd)
 or die('Failed to connect to server');
 //use database
 mysqli_select_db($con,$dbnm) or die('Database not available');
 
 return $con;
}

function DisplayFriends($email)
{
	
	$con=ConnectToDb();
//get the profile name of people who are friends with logged in user
 $sqlStringAllRecs="select fr.profile_name,fr.friend_id from friends fr 
 where fr.friend_id IN 
 (SELECT myfr.friend_id2 from myfriends myfr inner join friends fr 
 on fr.friend_id=myfr.friend_id1 
 where fr.friend_email='$email' )";
 //get the result
 $result=mysqli_query($con,$sqlStringAllRecs) or die ('Query failed: ' . mysqli_error($con));
 $count=mysqli_num_rows($result); 
 //set the friends count
 $_SESSION["count"]=$count;
 
 echo"<h2>Total Number of friends $count</h2>";
 //show the data in the table
 echo "<table width='100%' border='1'>";

while ($row = mysqli_fetch_row($result))
      {
		  echo"<form action=friendlist.php method=post>";
         echo"<tr>";
		 echo"<td>{$row[0]}</td>";
		 echo"<td style=display:none;>"."<input type=hidden name=hidden value=".$row[1]."</td>";
		 echo"<td>"."<input type=submit name=Friendid value=unfriend>"."</td>";
		 echo"</form>";
		
      }
	  echo"</table>";
	 
	 echo '<br/>';
	
 //close the connection
 mysqli_free_result($result);
 mysqli_close($con);
}




DisplayFriends($email);
 $addFriend_address="friendadd.php";
 $logOut_address="logout.php";
echo "<a href='".$addFriend_address."'>Add friend</a>";
echo "<a href='".$logOut_address."'>Log Out</a>";


if(isset($_POST['Friendid']))
{
	$friendId=$_POST["hidden"];
	$unfriendQuery="Delete from myfriends 
	where friend_id1='$userID' and friend_id2='$friendId'";
	$con=ConnectToDb();
	$result=mysqli_query($con,$unfriendQuery);
	 	echo"Successfully deleted friend";
 mysqli_close($con);
 header("Refresh:0");
	DisplayFriends($email);

}


?>