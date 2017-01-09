<?php
    //connect mysql and fetch data
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '70187017';
    $dbname = 'test';

    $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
    mysql_query("SET NAMES 'utf8'");
    mysql_select_db($dbname);
 
    $email = $_REQUEST["email"];
    $password = $_REQUEST["password"];
    $friend_registerd=0;

    $sql = "SELECT * FROM `debt_accounts` WHERE Email = '{$email}'";
    $result = mysql_query($sql) or die('MySQL query error');
    if(mysql_num_rows($result)== 0){
	     echo '{"login_result":"There is no such account."}';
    }
    else{
    	$row = mysql_fetch_array($result);
 	 	  if($row['Password']!=$password){
 	   	  echo '{"login_result":"Password is incorrect"}';
      }
      else{
 	      $name = $row['Name'];
        echo '{"login_result":"',$name,',You have logged in Chain Chain Lai!!!!"}';	
      }
    }
?> 
