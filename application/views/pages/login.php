<html>
    <head>
        <title>Admin login</title>
    </head>
    <body>
        <h1>Admin login</h1>
        <form method="post" action="<?= site_url('admin_login') ?>">
            <label >Email: 
                <input type="email" name="email" id="email" required>
            </label>
            <label> Password
                <input type="password" name="password" id="password" required>
            </label>

            <button type="submit">Log In</button>
        </form>
    </body>
</html>