<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; }
        .container { width: 350px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        input[type=text], input[type=password] { width: 100%; padding: 10px; margin: 8px 0 16px; border: 1px solid #ccc; border-radius: 4px; }
        input[type=submit] { width: 100%; background: #4CAF50; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; }
        input[type=submit]:hover { background: #45a049; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Login</h2>
    <?php if (isset($_GET['error'])): ?>
        <div class="error">Invalid credentials!</div>
    <?php endif; ?>
    <form method="post" action="../logic/adminlogin.php">
        <label for="username">Username</label>
        <input type="text" name="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" required>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>