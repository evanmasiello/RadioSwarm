<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Edit User - Radio Swarm</title>
    <link href="./css/styleV45.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="./assets/Icon.png">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2258919223240321"
     crossorigin="anonymous"></script>
  <style>
.error {color: #FF0000;}
</style>
<script>

</script>
</head>
<body>
<?php include "heading.html" ?>
<div class="contentBox">
<br>
<a href="."><h4>Edit User</h4></a>
<br>
<h2>Edit Your Account Information</h2>
<br>
<form id="changeName" method="post" action="./changeName.php" target="_blank">
  <label for="uname">Username:</label><br>
  <input type="text" id="uname" name="uname"><br>
  <label for="pword">Password:</label><br>
  <input type="password" id="pword" name="pword"><br>
  <label for="pwordNew">New Username:</label><br>
  <input type="text" id="unameNew" name="unameNew"><br><br>
  <input type="submit" value="Submit">
</form> 
<br>
<form id="changePassword" method="post" action="./changePassword.php" target="_blank">
  <label for="uname">Username:</label><br>
  <input type="text" id="uname" name="uname"><br>
  <label for="pword">Old Password:</label><br>
  <input type="password" id="pword" name="pword"><br>
  <label for="pwordNew">New Password:</label><br>
  <input type="password" id="pwordNew" name="pwordNew"><br><br>
  <input type="submit" value="Submit">
</form> 
</div>
<?php include "upArrow.html" ?>
</body>
</html>