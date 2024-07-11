<?php
    session_start();
    if(!isset($_SESSION["user"]))
    {
        header("Location: login.php");
    }
    require_once "database.php";
    $email = $_SESSION["user"];
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn,$sql);
    $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2> Change credentials </h2>
        <?php
            if (isset($_POST["change"]))
            {
                $id = $_POST["id"];
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["repeat_password"];

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $errors = array();

                if(empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat))
                {
                    array_push($errors,"All fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                {
                    array_push($errors, "Email is not valid");
                }
                if (strlen($password)<8)
                {
                    array_push($errors,"Password must be at least 8 characters long");
                }
                if ($password!==$passwordRepeat) 
                {
                    array_push($errors,"Password does not match");
                }
                
                if(count($errors)>0)
                {
                    foreach ($errors as $error)
                    {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }
                else
                {
                    $sql = "UPDATE users SET full_name = '$fullName', email= '$email', password = '$passwordHash' WHERE id=$id";
                    $result = mysqli_query($conn,$sql);
                    if($result)
                    {
                        
                        header("Location: login.php");
                    }
                    else
                    {
                        echo "<div class='alert alert-success'>no!</div>";
                    }
                }
            }
        ?>
        <form action="index.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" value="<?php echo $user["full_name"]; ?>" placeholder="Full Name:" >
            </div> 
            <div class="form-group">
                <input type="text" class="form-control" name="email" value="<?php echo $user["email"]; ?>" placeholder="Email:">
            </div> 
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password: ">
            </div> 
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Confirm Password: ">
            </div> 
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" class="form-control" value="Change" name="change">
            </div> 
        </form>
        <div class="logout-btn">
            <a href="logout.php" class="btn btn-warning">Logout</a>
        </div> 
        
    </div>
</body>
</html>