<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8" />
 <meta name="description" content="Web application development" />
 <meta name="keywords" content="PHP" />
 <meta name="author" content="Your Name" />
 <title>SignUp Page</title>
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
<h1>My Friend System Registration page </h1>
<div class="content">
<form action="<?php $_PHP_SELF ?>" method="post">
<label>Email</label>
<input type="text" name="email"/><br/>
<label>Profile Name</label>
<input type="text" name="ProfName"/><br/>
<label>Password</label>
<input type="password" name="pasword"/><br/>
<label>Confirm Password</label>
<input type="password" name="confirmPasword"/><br/>
<input type="submit" value="Register"/>
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
	$pattern="/^.+@.+\..{2,3}$/";
	if(!preg_match($pattern,$email))//check if email is valid
	{
		echo "<p>Email add ress is not valid</p>";
	}
	else
	{
		//check if email is already exists
		if(isEmailExist(trim($email))==false)
		{
			if(!empty($_POST["ProfName"]))
			{
				$profileName=$_POST["ProfName"];
				//validate profile name
				$profPattern="/^[a-zA-Z]+$/";
				if(!preg_match($profPattern,$profileName))
				{
					echo"<p>Profile name can only contains letters.</p>";
				}
				//check the password
				if(!empty($_POST["pasword"]))
				{
					
					$Passwordpattern="/^[a-zA-Z0-9]+$/";
					$password=$_POST["pasword"];
					if(!empty($_POST["confirmPasword"]))
					{
						$confirmPasword=$_POST["confirmPasword"];
						if(strcmp($password,$confirmPasword)===0)
						{
							//add data into friends table
							insertData($email,$profileName,$password);
						
							//redirect to login page
							header("location:login.php");
						}
						else
						{
							echo"<p>password does not match</p>";
						}
					}
					else
					{
						echo"<p>Confirm password cannot be empty.</p>";
					}
				}
				else
				{
					echo"<p>Password cannot be blank.</p>";
				}
			}
			else
			{
				echo"<p>Profile name cannot be blank.</p>";
			}
			
		}
	}
}

function isEmailExist($email)
{

require("settings.php");
 //open the connection
 $con=@mysql_pconnect($host,$user,$pswd)
 or die('Failed to connect to server'.mysql_error());
 //use database
 @mysql_select_db($dbnm) or die('Database not available');
 //set up sql string and execute
 $sqlString="SELECT count(*) from friends where friend_email='$email'";
 //get the result
 $result=mysql_query($sqlString) or die ('Query failed: ' . mysql_error());
 //close the connection

 mysql_close($con); 
 if(mysql_result($result,0)>0)
	{
		echo"<p>Email already exist</p>";
		 mysql_free_result($result);
		return true;
	}
	else
	{
		 mysql_free_result($result);
		return false;
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