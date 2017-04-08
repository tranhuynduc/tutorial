<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
include 'includes/head.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();

?>

<style type="text/css">
    body {
        background-image: url('../images/headerlogo/background.png');
        background-size: 100vw 100vh;
        background-attachment: fixed;
    }
</style>
<div id="login-form">
    <div>
        <?php
        if($_POST) {
            //form validation
            if(empty($_POST['email']) || empty($_POST['password'])) {
                $errors[] = 'You must provide email and password.';
            }

            // validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'You must enter a valid email.';
            }


            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            }


            //check if email is eixst
            $query = $db->query("SELECT * FROM users WHERE email ='$email'");
            $user = mysqli_fetch_assoc($query);
            $userCount = mysqli_num_rows($query);

            if ($userCount < 1) {
                $errors[] = 'That email does not exist.';
            }

            if (!password_verify($password, $user['password'])) {
                $errors[] = 'The password does not match. Please try again.';
            }


            if(!empty($errors)) {
                echo display_error($errors);
            } else {

                $user_id = $user['id'];

                login($user_id);

            }
        }
        ?>

    </div>
    <h2 class="text-center">Login</h2>
    <hr>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= $email;?> ">
        </div>
        <div class="form-group">
            <label for="password">Email</label>
            <input type="password" name="password" id="password" class="form-control" value="">
        </div>
        <div class="form-group">
            <input type="submit" value="login" class="btn btn-primary">
        </div>
    </form>
    <p class="text-right"><a href="../index.php" alt="Visit Site">Visit Site</a></p>
</div>

<?php
include 'includes/footer.php';
?>
