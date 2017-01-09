<?php
// Array with names
$function= $_REQUEST["f"];
$account = $_REQUEST["account"];
$friend  = $_REQUEST["friend"];
$type =$_REQUEST["type"];
$debt_time=$_REQUEST["debt_time"];
$debt_time2=$_REQUEST["debt_time2"];
$amount=$_REQUEST["amount"];
$info=$_REQUEST["info"];
$email=$_REQUEST["email"];
$name=$_REQUEST["name"];
$password=$_REQUEST["password"];
$registered=$_REQUEST["registered"];
$friend_email=$_REQUEST["friend_email"];
//connect mysql and fetch data
 $dbhost = 'localhost';
 $dbuser = 'root';
 $dbpass = '70187017';
 $dbname = 'test';
 $records=array();
 $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
 mysql_query("SET NAMES 'utf8'");
 mysql_select_db($dbname);
 	if($function == '1'){//query records
    
 		if($registered == '0'){
 			$sql = "SELECT * FROM `debt_accounts` WHERE Email='{$email}'";
			$result = mysql_query($sql) or die('MySQL query error11');
			$row = mysql_fetch_array($result);
			$name=$row['Name'];
      echo "not registered<br>";
		}
		else{
			$sql = "SELECT * FROM `debt_accounts` WHERE Name='{$friend}'";
			$result = mysql_query($sql) or die('MySQL query error24');
			$row = mysql_fetch_array($result);
			$friend_email=$row['Email'];
      echo "registered<br>";
		}
    echo "SELECT * FROM `debt_accounts` WHERE Name='{$friend}'",",name=",$name," friend=",$friend,", friendemail=",$friend_email," registered=",$registered,"<br>";
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
        if($balance>=0){
        	echo '<font size=\'6\'>You own ',$friend,' $',$balance,'</font><br>';
        }
        else{
        	echo '<font size=\'6\'>',$friend,' own you $',-$balance,'</font><br>';
        }

 		echo '<table border="1">';
		echo '<tr><th>Debtor</th><th>Type</th><th>Creditor</th><th>Time</th><th>Deadline</th><th>Amount</th><th>Info</th></tr>';    
   	    foreach ($records as $record) {
      	  		echo '<tr>';
      	  		echo '<td>',$record['Debtor'],'</td><td>',$record['Type'],'</td><td>',$record['Creditor'],'</td><td>',$record['Time'],'</td><td>',$record['Deadline'],'</td><td>',$record['Amount'],'</td><td>',$record['Info'],'</td>';
          		echo '</tr>';
        }
        echo '<tr><td>Balance</td><td colspan="6" align="center">',$balance,'</td></tr>';
    	echo '</table>';  
	}
	if($function == '2'){//add records

		if($registered=='0') {
			$sql = "SELECT * FROM `debt_accounts` WHERE Email='{$email}'";
			$result = mysql_query($sql) or die('MySQL query error21');
			$row = mysql_fetch_array($result);
			$name=$row['Name'];

 			//echo $account," ",$friend," ",$type," ",$dept_time," ",$dept_time2," ",$amount," ",$info;
 			$datetime_debt=DateTime::createFromFormat('Y-m-d\TH:i',$debt_time);
 			$datetime_deadline=DateTime::createFromFormat('Y-m-d\TH:i',$debt_time2);
   //  		echo '<br>yuioo',$dept_time,$datetime_dept->format('Y-m-d H:i:s'),$datetime_deadline->format('Y-m-d H:i:s'),'<br>';
    		if($type == 'debt' || $type == 'payback'){
    		 	$sql = "INSERT INTO `debt_records`(Debtor,Type,Creditor,Time,Deadline,Amount,Info,DebtorEmail)     VALUES ('{$name}','{$type}','{$friend}','{$datetime_debt->format('Y-m-d H:i:s')}','{$datetime_deadline->format('Y-m-d H:i:s')}','{$amount}','{$info}','{$email}')";
 			}
 			else if($type =='credit'){
 			 	$sql = "INSERT INTO `debt_records`(Debtor,Type,Creditor,Time,Deadline,Amount,Info,CreditorEmail) VALUES ('{$friend}','debt','{$name}'   ,'{$datetime_debt->format('Y-m-d H:i:s')}','{$datetime_deadline->format('Y-m-d H:i:s')}','{$amount}','{$info}','{$email}')";
 			}
 			$result = mysql_query($sql) or die('MySQL query error22'); 
		} 		
		else  {
			$sql = "SELECT * FROM `debt_accounts` WHERE Email='{$email}'";
			$result = mysql_query($sql) or die('MySQL query error23');
			$row = mysql_fetch_array($result);
			$name=$row['Name'];
			
			$sql = "SELECT * FROM `debt_accounts` WHERE Name='{$friend}'";
			$result = mysql_query($sql) or die('MySQL query error24');
			$row = mysql_fetch_array($result);
			$friend_email=$row['Email'];

 			//echo $account," ",$friend," ",$type," ",$dept_time," ",$dept_time2," ",$amount," ",$info;
 			$datetime_debt=DateTime::createFromFormat('Y-m-d\TH:i',$debt_time);
 			$datetime_deadline=DateTime::createFromFormat('Y-m-d\TH:i',$debt_time2);
   //  		echo '<br>yuioo',$dept_time,$datetime_dept->format('Y-m-d H:i:s'),$datetime_deadline->format('Y-m-d H:i:s'),'<br>';
    		if($type == 'debt' || $type == 'payback'){
    		 	$sql = "INSERT INTO `debt_records`(Debtor,Type,Creditor,Time,Deadline,Amount,Info,DebtorEmail,CreditorEmail) VALUES ('{$name}','{$type}','{$friend}','{$datetime_debt->format('Y-m-d H:i:s')}','{$datetime_deadline->format('Y-m-d H:i:s')}','{$amount}','{$info}','{$email}','{$friend_email}')";
 			}
 			else if($type =='credit'){
 			 	$sql = "INSERT INTO `debt_records`(Debtor,Type,Creditor,Time,Deadline,Amount,Info,DebtorEmail,CreditorEmail) VALUES ('{$friend}','debt','{$name}'   ,'{$datetime_debt->format('Y-m-d H:i:s')}','{$datetime_deadline->format('Y-m-d H:i:s')}','{$amount}','{$info}','{$friend_email}','{$email}')";
 			}
 			$result = mysql_query($sql) or die('MySQL query error25'); 
		} 	


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
        if($balance>=0){
          echo '<font size=\'6\'>You own ',$friend,' $',$balance,'</font><br>';
        }
        else{
          echo '<font size=\'6\'>',$friend,' own you $',-$balance,'</font><br>';
        }

    echo '<table border="1">';
    echo '<tr><th>Debtor</th><th>Type</th><th>Creditor</th><th>Time</th><th>Deadline</th><th>Amount</th><th>Info</th></tr>';    
        foreach ($records as $record) {
              echo '<tr>';
              echo '<td>',$record['Debtor'],'</td><td>',$record['Type'],'</td><td>',$record['Creditor'],'</td><td>',$record['Time'],'</td><td>',$record['Deadline'],'</td><td>',$record['Amount'],'</td><td>',$record['Info'],'</td>';
              echo '</tr>';
        }
        echo '<tr><td>Balance</td><td colspan="6" align="center">',$balance,'</td></tr>';
      echo '</table>';   

	}
	else if($function=='3')//add friend
	{
		if(!$registered){
			$sql = "SELECT * FROM `debt_friends` WHERE Email='{$email}' AND Friend='{$friend}'";
			$result = mysql_query($sql) or die('MySQL query error1');  
			if(mysql_num_rows($result)>0){
				echo 'already exist'; 
			}
			else if(mysql_num_rows($result)==0){
				$sql = "INSERT INTO `debt_friends`(Email,Friend) VALUES ('{$email}','{$friend}')";
				$result = mysql_query($sql) or die('MySQL query error2');
				echo 'add friend';
			}
		}
		else if($registered){
			$sql = "SELECT * FROM `debt_accounts` WHERE Email='{$friend_email}'";
			$result = mysql_query($sql) or die('MySQL query error');  
			if(mysql_num_rows($result)==0){
					echo 'nosuchaccount'; 
			}else{
				$row = mysql_fetch_array($result);
				$name_friend=$row['Name'];
				$sql = "SELECT * FROM `debt_friends_registered` WHERE Email='{$email}' AND FriendEmail='{$friend_email}'";
				$result = mysql_query($sql) or die('MySQL query error33');  
				if(mysql_num_rows($result)>0){
					echo 'alreadyfriend'; 
				}
				else if(mysql_num_rows($result)==0){
					$sql = "INSERT INTO `debt_friends_registered`(Email,FriendEmail) VALUES ('{$email}','{$friend_email}')";
					$result = mysql_query($sql) or die('MySQL query error34');
					echo "{$name_friend}";
				}
			}
		}
	}	
	else 
	if($function=='4')//check duplicate account then register 
	{
			$sql = "SELECT * FROM `debt_accounts` WHERE Email='{$email}'";
			$result = mysql_query($sql) or die('MySQL query error');  
			if(mysql_num_rows($result)>0){
				echo 'already'; 
			}
			else if(mysql_num_rows($result)==0){
				$sql = "SELECT * FROM `debt_accounts`";
          		$result = mysql_query($sql) or die('MySQL query error4444');
          		$num_account = mysql_num_rows($result) +1;
          		$sql = "INSERT INTO `debt_accounts`(`Id`,`Name`,`Password`,`Email`) VALUES ('{$num_account}','{$name}','{$password}','{$email}')";
          		$result = mysql_query($sql) or die('MySQL query error5555');
 	      		$sql = "SELECT * FROM `debt_accounts`";
          		$result = mysql_query($sql) or die('MySQL query error66666');
          		$num = mysql_num_rows($result);
          		if($num==$num_account){
          			echo 'completed';
          		}         
          		else{
          			echo 'failed';
          		}
			}
	}
	else if($function=='5') 
	{

	}
?>