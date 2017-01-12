<?php
    //connect mysql and fetch data
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '70187017';
    $dbname = 'test';

    $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
    mysql_query("SET NAMES 'utf8'");
    mysql_select_db($dbname);
    $function = $_REQUEST['function'];
    $email = $_REQUEST["email"];
    $password = $_REQUEST["password"];
    $friend_registerd=0;


    if($function==1){//login
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
    }
    else if($function==2){ //query friends
        $sql = "SELECT * FROM `debt_friends` WHERE Email = '{$email}'";
        $result = mysql_query($sql) or die('MySQL query error');
        $result_json=array();
        while($row = mysql_fetch_array($result)){
            array_push($result_json,array('friend_name'=>$row['Friend'],'registered'=>'0'));
        }
        $sql = "SELECT * FROM `debt_friends_registered` WHERE Email = '{$email}'";
        $result = mysql_query($sql) or die('MySQL query error');
        
        while($row = mysql_fetch_array($result)){
           $friend_email=$row['FriendEmail'];
           $sql = "SELECT * FROM `debt_accounts` WHERE Email = '{$friend_email}'";
           $result2 = mysql_query($sql) or die('MySQL query error');
           $row2 = mysql_fetch_array($result2);
            array_push($result_json,array('friend_name'=>$row2['Name'],'registered'=>'1'));
        }
        echo json_encode(array("result"=>$result_json)),"";

    } 
    mysql_close($conn);

?> 
