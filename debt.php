<!DOCTYPE html>
<html>
<script>
var friend_registered=0;
function showRecords(email,friend,registered){
  document.getElementById("addtest").innerHTML="showRecords";
   friend_registered=registered;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      document.getElementById("debt_records").innerHTML = xmlhttp.responseText;
    }
  };
  xmlhttp.open("GET", "debt_ajax_command.php?f=1"+"&email="+email+"&friend="+friend+"&registered="+registered, true);
  xmlhttp.send();
}
function addRecord(email){
      var friend;
     if(!friend_registered)
        friend=document.getElementById("friends").value;
     else 
       friend=document.getElementById("friends_registered").value;
     if(friend==''){
          alert("Please choose a friend!!!!!");
          return true;
     }
      var form = document.getElementById("form_record");
      for(var i=0;i<form.type.length;i++){
          if(form.type[i].checked){
            type=form.type[i].value;
          }  
      }
      var debt_time=document.getElementById("debt_time").value;
      if(debt_time==''){
        alert("Please pick a specific time/date of debt.");
        return true;
      }
      var debt_time2=document.getElementById("debt_time2").value;
      if(debt_time2==''){
        alert("Please pick a specific time/date of debt's deadline.");
        return true;
      }
      var amount=document.getElementById("amount").value;
      if(amount==''){
        alert("Please fill field: Amount.");
        return true;
      }
      if(amount <0) {
        alert("Amount can not be smaller than zero.");
        return true;
      }    
      var info=document.getElementById("info").value;
      if(info==''){
        alert("Please fill field: info.");
        return true;
      }    
      document.getElementById("addtest").innerHTML = "addRecord! "+friend+" " +type+" "+debt_time+" "+debt_time2+" "+amount+" "+info;
      var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("debt_records").innerHTML = xmlhttp.responseText;
          }
        };
      xmlhttp.open("GET", "debt_ajax_command.php?f=2"+"&email="+email+"&friend="+friend+"&type="+type+"&debt_time="+debt_time+"&debt_time2="+debt_time2+"&amount="+amount+"&info="+info+"&registered="+friend_registered, true);
      xmlhttp.send();
}
function addFriend(email,registered){
    if(registered==0){
        var friend=document.getElementById("name_addfriend").value;
        if(friend==''){
          alert("Please fill field: Add Friend->Unregistered->Name.");
          return true;
        }    
      //document.getElementById("addtest").innerHTML = "addRecord! "+friend+" " +type+" "+debt_time+" "+debt_time2+" "+amount+" "+info;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("info_addfriend").innerHTML = xmlhttp.responseText;
          if(xmlhttp.responseText=="add friend"){
            var select=document.getElementById("friends");
            var opt=document.createElement('option');
            opt.value=friend;
            opt.innerHTML=friend;
            select.appendChild(opt);
          }
        }
      };
      xmlhttp.open("GET", "debt_ajax_command.php?f=3"+"&friend="+friend+"&email="+email+"&registered=0", true);
      xmlhttp.send();
    }
    else if(registered){

        var friend_email=document.getElementById("email_addfriend_registered").value;
        if(friend_email==''){
          alert("Please fill field: Add Friend->Registered->Email.");
          return true;
        }    
        //var friend = document.getElementById("friends_registered").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("info_addfriend").innerHTML = xmlhttp.responseText;
            if(xmlhttp.responseText=="nosuchaccount"){
              alert("There is no such account!!!!");
              return true;
            }
            else if (xmlhttp.responseText=="nosuchaccount"){
              alert("This account is already friend!!!");
              return true;
            }
            else{
              var friend=xmlhttp.responseText;
              var select=document.getElementById("friends_registered");
              var opt=document.createElement('option');
              opt.value=friend;
              opt.innerHTML=friend;
              select.appendChild(opt);
            }
          }
        };
        xmlhttp.open("GET", "debt_ajax_command.php?f=3"+"&friend_email="+friend_email+"&email="+email+"&registered=1", true);
        xmlhttp.send();
    }
}
</script>



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
	     echo "<br>There is no such accountt.",$sql;
    }
    else{
    	$row = mysql_fetch_array($result);
 	 	  if($row['Password']!=$password){
 	   	  echo "<br>Password is incorrect"; 
      }
      else{
 	      $name = $row['Name'];
        echo "<br>",$name," ,You have logged in Chain Chain Lai!!!!";	



        
        $sql = "SELECT * FROM `debt_friends` WHERE Email = '{$email}'";
        $result = mysql_query($sql) or die('MySQL query error');
        echo '<form>';
        echo '<select name="friends" id="friends" onchange="showRecords(\'',$email,'\',this.value,0)">';
        echo '<option value="">Select a friend:</option>'; 
        while($row = mysql_fetch_array($result)){
          echo '<option value=',$row['Friend'],'>',$row['Friend'],'</option>'; 
        }   
        echo '</select><br>';
        echo '</form>';


        
        $sql = "SELECT * FROM `debt_friends_registered` WHERE Email = '{$email}'";
        $result = mysql_query($sql) or die('MySQL query error');
        echo '<form>';
        echo '<select name="friends_registered" id="friends_registered" onchange="showRecords(\'',$email,'\',this.value,1)">';
        echo '<option value="">Select a friend:</option>'; 
        while($row = mysql_fetch_array($result)){
           $friend_email=$row['FriendEmail'];
           $sql = "SELECT * FROM `debt_accounts` WHERE Email = '{$friend_email}'";
           $result2 = mysql_query($sql) or die('MySQL query error');
           $row2 = mysql_fetch_array($result2);

          echo '<option value=',$row2['Name'],'>',$row2['Name'],'</option>'; 
        }   
        echo '</select><br>';
        echo '</form>';


        echo '<br>';
        echo '<fieldset>';
        echo '<legend>Add Friend:</legend>';
        echo '<fieldset>';
        echo '<legend>Unregistered</legend>';   
        echo '<form name="form_addfriend" id="form_addfriend">';
        echo 'Name: <input id="name_addfriend" name="name_addfriend" type="text"><br>';
        echo '<input onclick="addFriend(\'',$email,'\',0)" type="button" value="Submit">';
        echo '</form>';
        echo '</fieldset>';

        echo '<fieldset>';
        echo '<legend>Registered</legend>';   
        echo '<form name="form_addfriend_registered" id="form_addfriend_registered">';
        echo 'E-mail: <input id="email_addfriend_registered" name="email_addfriend_registered" type="text"><br>';
        echo '<input onclick="addFriend(\'',$email,'\',1)" type="button" value="Submit">';
        echo '</form>';
        echo '</fieldset>';
        echo '</fieldset>';


        echo '<form name="form_record" id="form_record">';
        echo '<fieldset>';
        echo '<legend>Enter record:</legend>';
        echo 'Type: <input type="radio" id="type" name="type" value="debt" checked>Debt ' ; 
        echo '<input type="radio" id="type" name="type" value="credit" >Credit ';  
        echo '<input type="radio" id="type" name="type" value="payback" >Payback<br>';
        echo 'Debt Time    :<input type="datetime-local" id="debt_time"     name="debt_time">    <br>';
        echo 'Deadline     :<input type="datetime-local" id="debt_time2"    name="debt_time2">   <br>';
        echo 'Amount: <input id="amount" name="amount" type="number" min="1"><br>';
        echo 'Info: <input id="info" name="info" type="text"><br>';
        echo '<input onclick="addRecord(\'',$email,'\')" type="button" value="Submit">';
        echo '</fieldset>';
        echo '</form>';

		    // $sql = "SELECT * FROM `debt_records` WHERE DebtorEmail = '{$Email}' OR Objector = '{$account}'";
		  // $result = mysql_query($sql) or die('MySQL query error');    	
        echo '<div id="debt_records"><b>';
        echo '<table border="1">';
		    echo '<tr><th>Debtor</th><th>Type</th><th>Creditor</th><th>Time</th><th>Deadline</th><th>Amount</th><th>Info</th></tr>';    
      // while($row = mysql_fetch_array($result)){
      //   echo '<tr>';	
      //   echo '<td>',$row['Account'],'</td><td>',$row['Type'],'</td><td>',$row['Objector'],'</td><td>',$row['Time'],'</td><td>',$row['Deadline'],'</td><td>',$row['Amount'],'</td><td>',$row['Info'],'</td>';
      //   echo "</tr>";
      // }
        echo '</table>';
        echo '</b></div>';        
      }
    }
?> 
</body>
<p>Suggestions: <span id="addtest"></span></p>
<p>Suggestions2: <span id="addtest2"></span></p>
<p>Suggestions2: <span id="info_addfriend"></span></p>
</html> 