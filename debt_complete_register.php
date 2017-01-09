<html>

<body>  
<p>Register Completed!!! </p>
<?php 
    $name = $_POST["name"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    echo 'Name:',$name,'<br>';
    echo 'E-mail:',$email,'<br>';
?>
<a href="/debt_login.php">Back to login</a>
</body>
</html>



