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
    $registered= $_REQUEST["registered"];
    $friend = $_REQUEST["friend"];

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
    else if($function==3){ //query friends
        $result_json=array();
        if($registered == '0'){
            $sql = "SELECT * FROM `debt_accounts` WHERE Email='{$email}'";
            $result = mysql_query($sql) or die('MySQL query error11');
            $row = mysql_fetch_array($result);
            $name=$row['Name'];
        }
        else{
            $sql = "SELECT * FROM `debt_accounts` WHERE Name='{$friend}'";
            $result = mysql_query($sql) or die('MySQL query error24');
            $row = mysql_fetch_array($result);
            $friend_email=$row['Email'];
        }
       // echo "SELECT * FROM `debt_accounts` WHERE Name='{$friend}'",",name=",$name," friend=",$friend,", friendemail=",$friend_email," registered=",$registered,"<br>";
        $sql = "SELECT * FROM `debt_records` WHERE DebtorEmail='{$email}' OR CreditorEmail='{$email}'";
        $result = mysql_query($sql) or die('MySQL query error12');  
        $balance=0;   
        while($row = mysql_fetch_array($result)){
            if($registered == '0'){//unregistered
                if($row['Debtor'] == $friend && $row['Creditor']== $name&&$row['DebtorEmail']==''){     
                    $records[]=array("Debtor"=>$row['Debtor'],"Type"=>$row['Type'],"Creditor"=>$row['Creditor'],"Time"=>$row['Time'],"Deadline"=>$row['Deadline'],"Amount"=>$row['Amount'],"Info"=>$row['Info']);
                    if($row['Type']=='debt'){
                        $balance-=$row['Amount'];
                    }
                    else 
                    if($row['Type']=='payback'){
                        $balance+=$row['Amount'];
                    } 
                }
                else 
                if($row['Debtor'] ==$name &&$row['Creditor']== $friend&&$row['CreditorEmail']==''){
                    $records[]=array("Debtor"=>$row['Debtor'],"Type"=>$row['Type'],"Creditor"=>$row['Creditor'],"Time"=>$row['Time'],"Deadline"=>$row['Deadline'],"Amount"=>$row['Amount'],"Info"=>$row['Info']);
                    if($row['Type']=='debt'){
                        $balance+=$row['Amount'];
                    }
                    else 
                    if($row['Type']=='payback'){
                        $balance-=$row['Amount'];
                    }
                }
            }
            else if($registered == '1'){//Registered
                if($row['DebtorEmail']== $friend_email && $row['CreditorEmail']== $email && $row['DebtorEmail']!=''){
                    $records[]=array("Debtor"=>$row['Debtor'],"Type"=>$row['Type'],"Creditor"=>$row['Creditor'],"Time"=>$row['Time'],"Deadline"=>$row['Deadline'],"Amount"=>$row['Amount'],"Info"=>$row['Info']);
                    if($row['Type']=='debt'){
                        $balance-=$row['Amount'];
                    }
                    else 
                    if($row['Type']=='payback'){
                        $balance+=$row['Amount'];
                    }
                }
                else 
                if($row['DebtorEmail']== $email && $row['CreditorEmail']== $friend_email &&$row['CreditorEmail']!=''){
                    $records[]=array("Debtor"=>$row['Debtor'],"Type"=>$row['Type'],"Creditor"=>$row['Creditor'],"Time"=>$row['Time'],"Deadline"=>$row['Deadline'],"Amount"=>$row['Amount'],"Info"=>$row['Info']);
                    if($row['Type']=='debt'){
                        $balance+=$row['Amount'];
                    }
                    else 
                    if($row['Type']=='payback'){
                        $balance-=$row['Amount'];
                    }
                }
            }
        }
        // if($balance>=0){
        //     echo '<font size=\'6\'>You own ',$friend,' $',$balance,'</font><br>';
        // }
        // else{
        //     echo '<font size=\'6\'>',$friend,' own you $',-$balance,'</font><br>';
        // }
        foreach ($records as $record) {
             array_push($result_json,array('debtor'=>$record['Debtor'],'type'=>$record['Type'],'creditor'=>$record['Creditor'],'time'=>$record['Time'],'deadline'=>$record['Deadline'],'amount'=>$record['Amount'],'info'=>$record['Info']));

        }

        echo json_encode(array("result"=>$result_json)),"";
    } 
    mysql_close($conn);

?> 
