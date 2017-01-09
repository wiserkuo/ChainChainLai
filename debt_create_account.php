<html>

<body>  
<script>
function check(){
   var EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if(document.getElementById("name").value==''){
      document.getElementById("validate_name").innerHTML=" Please fill Name";
  }
  else if(document.getElementById("password").value==''){
      document.getElementById("validate_name").innerHTML="";
      document.getElementById("validate_password").innerHTML=" Please fill Password";
  }
  else if(document.getElementById("email").value=='') {
      document.getElementById("validate_password").innerHTML="";
      document.getElementById("validate_email").innerHTML=" Please fill E-mail";
  }
  else if(!EMAIL_REGEX.test(document.getElementById("email").value)){
      document.getElementById("validate_email").innerHTML=" Please fill valid E-mail format!";
  }
  else{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        var result=xmlhttp.responseText;
          document.getElementById("result").innerHTML = result;
        if(result=="already"){
          document.getElementById("validate_email").innerHTML = "This E-mail is already been used.";
        }
        else if(result=="failed"){
              document.getElementById("result").innerHTML = result;
        }
        else{
           document.getElementById("register").action="debt_complete_register.php";
           document.getElementById("register").submit();
        }
      }    
    };
    var name=document.getElementById("name").value;
    var password=document.getElementById("password").value;
    var email=document.getElementById("email").value
    xmlhttp.open("GET", "debt_ajax_command.php?f=4"+"&email="+email+"&password="+password+"&name="+name, true);
    xmlhttp.send();

  }
}
</script>


<p>Register New Account </p>

<form id="register"  method="post">
Name:     <input type="text" name="name" id='name'><span id="validate_name"></span><br>
Password: <input type="text" name="password" id='password'><span id="validate_password"></span><br>
E-mail:   <input type="email" name="email" id='email' ><span id="validate_email"></span><br>
<input type="button" onclick="check()"  value="submit">

</form>

<p><span id="result"></span></p>

</body>
</html>