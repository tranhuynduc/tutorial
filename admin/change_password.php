<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
if(!is_loogin_in()) {
    login_error_redirect();
}
include 'includes/head.php';

$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password,PASSWORD_DEFAULT);
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
            if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])) {
                $errors[] = 'You must fill out all fileds.';
            }

            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            }

            if($password != $confirm) {
                $error[] = 'The new password and confirm new password does not match.';
            }

            if (!password_verify($old_password, $hashed)) {
                $errors[] = 'The old password does not match. Please try again.';
            }


            if(!empty($errors)) {
                echo display_error($errors);
            } else {
                //Chnage password
                $user_id = $user_data['id'];
                $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
                $_SESSION['success_flash'] = 'Your password has been updated!';
                header('Location: index.php');
            }
        }
        ?>

    </div>
    <h2 class="text-center">Change Password</h2>
    <hr>
    <form action="change_password.php" method="post">
        <div class="form-group">
            <label for="old_password">Old Password</label>
            <input type="text" name="old_password" id="old_password" class="form-control" value="<?= ((isset($_POST['old_password']))?$old_password:'');?>">
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" class="form-control" value="<?= ((isset($_POST['password']))?$password:'');?>">
        </div>
        <div class="form-group">
            <label for="confirm">Confirm New Password</label>
            <input type="password" name="confirm" id="confirm" class="form-control" value="<?= ((isset($_POST['confirm']))?$confirm:'');?>">
        </div>
        <div class="form-group">
            <a href="index.php" class="btn btn-default">Cancel</a>
            <input type="submit" value="Change Password" class="btn btn-primary">

        </div>
    </form>
    <p class="text-right"><a href="../index.php" alt="Visit Site">Visit Site</a></p>
</div>

<?php
include 'includes/footer.php';
?>
