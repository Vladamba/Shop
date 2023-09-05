<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<form action="index.php" method="post">
    <input type="text" name="addCustomer" value="1" hidden="hidden"/>
    <input type="text" name="login" placeholder="Login"/>
    <input type="text" name="password" placeholder="Password"/>
    <input type="text" name="email" placeholder="Email"/>
    <input type="submit" value="SignUp"/>
</form>

<hr>

<form action="index.php" method="post">
    <input type="text" name="selectCustomer" value="1" hidden="hidden"/>
    <input type="text" name="login" placeholder="Login"/>
    <input type="text" name="password" placeholder="Password"/>
    <input type="submit" value="login"/>
</form>

</body>
</html>