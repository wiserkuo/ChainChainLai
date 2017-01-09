<!DOCTYPE html>
<html>

<body>

<h1>Chain Chain Lai - Debt Manager</h1>


<?php
    //connect mysql and fetch data
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '70187017';
    $dbname = 'test';

    $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
    mysql_query("SET NAMES 'utf8'");
    mysql_select_db($dbname);
 
    $email = $_POST["email"];
    $password = $_POST["password"];
    $friend_registerd=0;

    $sql = "SELECT * FROM `debt_accounts` WHERE Email = '{$email}'";
    $result = mysql_query($sql) or die('MySQL query error');
    if(mysql_num_rows($result)== 0){
	     echo '{"login_result":There is no such account."}';
    }
    else{
    	$row = mysql_fetch_array($result);
 	 	  if($row['Password']!=$password){
 	   	  echo '{"login_result":Password is incorrect"}';
      }
      else{
 	      $name = $row['Name'];
        echo '{"login_result":'{$name}' ,You have logged in Chain Chain Lai!!!!"}';	
      }
    }
?> 
</body>
<p>Suggestions: <span id="addtest"></span></p>
<p>Suggestions2: <span id="addtest2"></span></p>
<p>Suggestions2: <span id="info_addfriend"></span></p>
</html> 