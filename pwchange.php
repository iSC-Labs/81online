<?php
include("./data/config.inc.php");
//  防止全局变量造成安全隐患
$admin = false;
session_id($_GET['s']);
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    echo "";
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
    echo("You are not logged in. Redirecting to login page.");
    echo("<meta http-equiv=refresh content='2; url=login.php'>");
    die();
}

function check_input($value){
// Stripslashes
if (get_magic_quotes_gpc())  {  $value = stripslashes($value);  }
// Quote if not a number
if (!is_numeric($value))  {  $value = "'" . mysql_real_escape_string($value) . "'";  }
return $value;}

//  表单提交后...
$posts = $_POST;
//  清除一些空白符号
foreach ($posts as $key => $value) {
    $posts[$key] = trim($value);
}
$username          =$_SESSION["username"];
mysql_connect($db_host,$db_user,$db_pass) //填写mysql用户名和密码  
   or die("Could not connect to MySQL server!");  
mysql_select_db($db_name) //数据库名  
   or die("Could not select database!");  
mysql_query('set names "gbk"'); //数据库内数据的编码 
$oldpassword =  mysql_real_escape_string($posts["oldpassword"]);
$newpassword =  mysql_real_escape_string($posts["newpassword"]); 
$renewpassword =  mysql_real_escape_string($posts["renewpassword"]);
$sql = "SELECT * FROM user WHERE password = password('$oldpassword') AND username = '$username'";
$result = mysql_query($sql) or die ("wrong"); 
$userInfo = mysql_fetch_array($result); 

  if( $newpassword != $renewpassword){
    echo('Password confirmation doesn\'t match. The password should be between 6-20 characters. Redirecting back.');
    echo("<meta http-equiv=refresh content='2; url=change.php'>");
  }else{   
   if(mysql_num_rows( mysql_query($sql) )==1 ){
    $sql="Update user set password=password('$newpassword') where username='$username'";
    mysql_query($sql) or die(mysql_error());
    echo('Successfully changed the password. Redirecting to login page.');
    echo("<meta http-equiv=refresh content='2; url=login.php'>");
   }else{
    echo('The current password is not correct. Redirecting back.');
    echo("<meta http-equiv=refresh content='2; url=change.php'>");
   }
  }

 
?>
