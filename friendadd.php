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
 $FriendCount=$_SESSION["count"];
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

function DisplayRegisteredUsers($email)
{
	//set the page numbers
	if(isset($_GET['page']))
	{
		$page=$_GET["page"];//get the page no
	}
	else
	{
		$page=1;
	}
	
		$page1=($page-1)*5;
	
	$con=ConnectToDb();

 //get the profile name of people who are not friends with logged in user
 $sqlString="select fr.profile_name,fr.friend_id from friends fr 
 where fr.friend_id NOT IN 
 (SELECT myfr.friend_id2 from myfriends myfr inner join friends fr 
 on fr.friend_id=myfr.friend_id1 
 where fr.friend_email='$email' ) AND fr.friend_email!='$email' limit $page1,5";
 //get the result according to the limit
 $result=mysqli_query($con,$sqlString) or die 
 ('Query failed: ' . mysqli_error($con));
 //get full data
  //get the profile name of people who are not friends with logged in user
 $sqlAllString="select fr.profile_name,fr.friend_id from friends fr 
 where fr.friend_id NOT IN 
 (SELECT myfr.friend_id2 from myfriends myfr inner join friends fr 
 on fr.friend_id=myfr.friend_id1 
 where fr.friend_email='$email' ) 
 AND fr.friend_email!='$email'";
 //get the result 
 $resultAll=mysqli_query($con,$sqlAllString) or die 
 ('Query failed: ' . mysqli_error($con));
 
 $count=mysqli_num_rows($resultAll);
 //get logged in users friend list
 $loggesUserFriendsQuery="select fr.profile_name,fr.friend_id from friends fr 
 where fr.friend_id IN 
 (SELECT myfr.friend_id2 from myfriends myfr inner join friends fr 
 on fr.friend_id=myfr.friend_id1 
 where fr.friend_email='$email' ) 
 AND fr.friend_email!='$email'";
 
 $loggesUserFriendsResult=mysqli_query($con,$loggesUserFriendsQuery) or die 
 ('Query failed: ' . mysqli_error($con));
	$FriendCount=$_SESSION["count"];

 echo"<h2>Total Number of friends $FriendCount</h2>";
 $loggedUserFriendsList=mysqli_fetch_array($loggesUserFriendsResult);
 

$mutualFriends=array();
 //show the data in the table
 echo "<table width='100%' border='1' style=border-collapse: collapse;>";

while ($row = mysqli_fetch_row($result))
      {//get the reg users list of friends
  $getFriendsString="SELECT myfr.friend_id2 from myfriends myfr where myfr.friend_id1='$row[1]'";
  
  //get the result
		  $friendsResult=mysqli_query($con,$getFriendsString) or die ('Query failed: ' . mysqli_error($con));
 //get the result into an array
 $RegUserFriendList=mysqli_fetch_array($friendsResult);
 
 if(!empty($RegUserFriendList) && !empty($loggedUserFriendsList))
 {
 //compare the friends friend list and logged in user's friend list and get matching records into an array
 $mutualFriends=array_intersect($loggedUserFriendsList,$RegUserFriendList);
 }
		  echo"<form action=friendadd.php method=post>";
		  //display the reg user's name
         echo"<tr><td>{$row[0]}</td>";
		 //get the mutual friend count
		 echo" <td>".count($mutualFriends)." mutual friends</td>";
		echo"<td style=display:none;>"."<input type=hidden name=hidden value=".$row[1]."</td>";
		 echo"<td>"."<input type=submit name=AddFriend value=Addfriend>"."</td>";
		
		 echo"</form>";
		
      }
	  echo"</table>";
 $numberOfPages=ceil($count/5);
for($i=1;$i<=$numberOfPages;$i++)
{
	 echo '<a href="friendadd.php?page='.$i.'">'.$i.'  '.'</a>';
	
}
echo'<br/>';
 //close the connection
 mysqli_free_result($result);
 mysqli_close($con);
}




DisplayRegisteredUsers($email);
 $FriendList_address="friendlist.php";
 $logOut_address="logout.php";
echo "<a href='".$FriendList_address."'>Friends list</a>";
echo "<a href='".$logOut_address."'>Log Out</a>";


if(isset($_POST['AddFriend']))
{
	$friendId=$_POST["hidden"];
	echo"$friendId";
	$AddfriendQuery="insert into myfriends(friend_id1,friend_id2) 
	values('$userID','$friendId')";
	$con=ConnectToDb();
	$result=mysqli_query($con,$AddfriendQuery);
	 	echo"Successfully inserted friend";
 mysqli_close($con);
 //increase the friend count
 $FriendCount=$FriendCount+1;
 $_SESSION["count"]=$FriendCount;
 header("Refresh:0");
 
	DisplayRegisteredUsers($email);

}


?>