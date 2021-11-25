<?php
session_start();
if( isset($_SESSION['user'])!="" ){
 header("Location: profile.php");
}
include_once 'connect.php';

if ( isset($_POST['sca']) ) {
 $username = trim($_POST['username']);
 $fname = trim($_POST['fname']);
 $lname = trim($_POST['lname']);
 $pass = trim($_POST['pass']);
 $password = hash('sha256', $pass);

 $query = "insert into people(username,fname,lname,pass) values(?, ?, ?, ?)";
 $stmt = $pdo->prepare($query);
 $stmt->execute([$username,$fname,$lname,$password]);
 $rowsAdded = $stmt->rowCount();

  if ($rowsAdded == 1) {
   $message = "Success! Proceed to login";
   unset($fname);
   unset($lname);
   unset($pass);
   header("Location: login.php");
  }
  else
  {
   $message = "Failed! For some reason";
  }
}
?>

<html>
<head><title>Register</title></head>
<body>

<form action="register.php" method="post">
Username: <input type="text" name="username" /><br /><br />
First Name: <input type="text" name="fname" /><br /><br />
Last Name: <input type="text" name="lname" /><br /><br />
Password: <input type="password" name="pass" /><br /><br />
<input type="submit" name="sca" value="Create Account" /> <br />
</body>
</html>
