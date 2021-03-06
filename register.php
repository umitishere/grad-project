<?php

$pageTitle = "Üye Ol | Grad Project";

require_once("includes/header.php");

$queryUniversities = $pdo->prepare("SELECT * FROM universiteler ORDER BY uni_id ASC");
$queryUniversities->execute();

// Define variables and initialize with empty values
$username = $email = $password = $university = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $university = trim($_POST["university"]);

    // Validate username
    if(empty(trim($_POST["username"]))) {
        $username_err = "Lütfen bir kullanıcı adı girin.";
    } else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Kullanıcı adı sadece harf, sayı ve alt çizgiden oluşmalıdır ve Türkçe karakter içermemelidir.";
    } else if(strlen(trim($_POST["username"])) < 6 || strlen(trim($_POST["username"])) > 16) {
        $username_err = "Kullanıcı adı en az 6, en fazla 16 karakterden oluşmalıdır.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = "Bu kullanıcı adı daha önce alınmış.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Bir şeyler ters gitti. Lütfen daha sonra tekrar deneyin.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Validate email
    if(empty(trim($_POST["email"]))) {
        $username_err = "Lütfen bir e-posta girin.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = :email";

        if($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()) {
                if($stmt->rowCount() == 1) {
                    $email_err = "Bu e-postaya sahip bir hesap mevcut.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Bir şeyler ters gitti. Lütfen daha sonra tekrar deneyin.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Lütfen bir şifre girin.";
    } else if(strlen(trim($_POST["password"])) < 8) {
        $password_err = "Şifreniz en az 8 karakterden oluşmalıdır.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Lütfen şifrenizi doğrulayın.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);

        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Şifreler eşleşmiyor.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, university, password) VALUES (:username, :email, :university, :password)";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":university", $param_university, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_university = $university;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: giris-yap");
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
        <h2 class="margin-top-15">Üye Ol</h2>
        <p>Lütfen üye olmak için aşağıdaki formu doldurun.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group margin-top-15">
                <label for="username">Kullanıcı Adı</label>
                <input id="username" type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" maxlength="16"/>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

            <div class="form-group margin-top-15">
                <label for="email">E-posta</label>
                <input id="email" type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" maxlength="90" />
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group margin-top-15">
                <label for="university">Üniversite</label>
                <select name="university" class="form-control" id="university" required>
                <?php while ($getUniversities = $queryUniversities->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?php echo $getUniversities['uni_name']; ?>"><?php echo $getUniversities['uni_name']; ?></option>
                <?php } ?>
                </select>
            </div>

            <div class="form-group margin-top-15">
                <label for="password">Şifre</label>
                <input id="password" type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" maxlength="256"/>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group margin-top-15">
                <label for="confirm_password">Şifre (Doğrulama)</label>
                <input id="confirm_password" type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" maxlength="256">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group margin-top-15">
                <input type="submit" class="btn btn-primary" value="Üye Ol">
            </div>

            <p class="margin-top-15">Zaten bir hesabınız var mı? <a href="giris-yap">Giriş yapın</a>.</p>

        </form>
    </div>



<?php require_once("includes/footer.php"); ?>
