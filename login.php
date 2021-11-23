<?php

$pageTitle = "Giriş Yap | Brand";

require_once("includes/header.php");

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect user to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: anasayfa");
    exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Lütfen bir kullanıcı adı girin.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Lüften şifrenizi girin.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: anasayfa");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Kullanıcı adı veya şifre hatalı.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Kullanıcı adı veya şifre hatalı.";
                }
            } else{
                echo "Bir şeyler ters gitti. Lütfen daha sonra tekrar deneyin.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>

    <div class="container">
        <h2 class="margin-top-15">Giriş Yap</h2>
        <p>Lüften giriş yapmak için aşağıdaki formu doldurun.</p>

        <?php
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group margin-top-15">
                <label for="username">Kullanıcı Adı</label>
                <input id="username" type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group margin-top-15">
                <label for="password">Şifre</label>
                <input id="password" type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group margin-top-15">
                <input type="submit" class="btn btn-primary" value="Giriş Yap">
            </div>
            <p class="margin-top-10">Bir hesabınız yok mu? <a href="uye-ol">Şimdi üye olun</a>.</p>
        </form>
    </div>

<?php require_once("includes/footer.php"); ?>
