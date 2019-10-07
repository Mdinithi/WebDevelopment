<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8" />
 <meta name="description" content="Web application development" />
 <meta name="keywords" content="PHP" />
 <meta name="author" content="Your Name" />
 <title>LogIn Page</title>
<style>


h1 {
    
    margin-left: 40px;
	text-align: center;
} 
.content
{
	 background-color:LightGrey;
	 margin: 20px;
    padding: 20px;
	width:50%;
	align:center;
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

input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type=password], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit],input[type=reset] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}
</style>
</head>
<body>
<h1>My Friend System</h1>
<h2>Log in Page</h2>
<div class="content">
<form action="<?php $_PHP_SELF ?>" method="post">
<label>Email</label>
<input type="text" name="email"/>
<label>Password</label>
<input type="password" name="pasword"/>

<input type="submit" value="Log in"/>
<input type="reset" value="Clear"/>
</form>
</div>
</body>
</html>
<?php
//validate input data
if(isset($_POST["email"]))
{
	$email=$_POST["email"];

	//check if email is already exists
		
	//check the password
	if(!empty($_POST["pasword"]))
	{
					
		$Passwordpattern="/^[a-zA-Z0-9]+$/";
		$password=$_POST["pasword"];
		//get password for email from serv
		$data=GetPassword($email);
	
		$serverPasword=$data["password"];
		$userID=$data["friend_id"];
			if(strcmp($password,$serverPasword)===0)
			{			Session_start();
							$_SESSION["Status"]="LogIn";
							$_SESSION["profileName"]=$data["profile_name"];
						$_SESSION["email"]=$email;
						$_SESSION["LoggedUserID"]=$userID;
							//redirect to friends list page
							header("location:friendlist.php");
						
					
				}
				else
				{
					echo"<p>Password does not match.</p>";
				}
			
			
			
		
}
else
				{
					echo"<p>Password cannot be blank.</p>";
				}
}

function GetPassword($email)
{

require("settings.php");
 //open the connection
 $con=@mysql_pconnect($host,$user,$pswd)
 or die('Failed to connect to server'.mysql_error());
 //use database
 @mysql_select_db($dbnm) or die('Database not available');
 //set up sql string and execute
 $sqlString="SELECT password,profile_name,friend_id from friends where friend_email='$email'";
 //get the result
 $result=mysql_query($sqlString) or die ('Query failed: ' . mysql_error());
 //close the connection

 mysql_close($con); 
 if(mysql_num_rows($result)==0)
	{
		echo"<p>Email does not exist</p>";
		 mysql_free_result($result);
		
	}
	else
	{
		$row=mysql_fetch_array($result,0);
	
		 mysql_free_result($result); 
		 
		return $row;
	}
}

function insertData($email,$profileName,$password)
{
	require("settings.php");
 //open the connection
 $con=@mysql_pconnect($host,$user,$pswd)
 or die('Failed to connect to server'.mysql_error());
 //use database
 @mysql_select_db($dbnm) or die('Database not available');
 //set up sql string and execute
 $sqlString="insert into friends(friend_email,password,profile_name,date_started,num_of_friends) values('$email','$password','$profileName',CURDATE(),0)";
 //get the result
 $result=mysql_query($sqlString) or die ('Query failed: ' . mysql_error());
 //close the connection
 mysql_free_result($result);
 mysql_close($con); 
 
}
{
	
}
?>