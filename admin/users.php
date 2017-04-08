<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
if(!is_loogin_in()) {
    login_error_redirect();
}

if(!has_permission('admin')) {
    permission_error_redirect('index.php');
}

include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['delete'])) {
    $delete_id = sanitize($_GET['delete']);
    $db->query("DELETE FROM users WHERE id = '$delete_id'");
    $_SESSION['success_flash'] = 'User has been delete';
    header('Location: users.php');
}

if(isset($_GET['add'])) {
    $name = ((isset($_POST['name']))?sanitize(($_POST['name'])):'');
    $email = ((isset($_POST['email']))?sanitize(($_POST['email'])):'');
    $password = ((isset($_POST['password']))?sanitize(($_POST['password'])):'');

    $confirm = ((isset($_POST['confirm']))?sanitize(($_POST['confirm'])):'');
    $permissions = ((isset($_POST['permissions']))?sanitize(($_POST['permissions'])):'');
    $errors = array();

    if($_POST) {
        $emailQuery = $db->query("SELECT * FROM users WHERE email='$email'");
        $emailCount = mysqli_num_rows($emailQuery);

        if ($emailCount != 0) {
            $errors[] = $email.' email already exist.';
        }
        $required = array('name','email','password','confirm','permissions');
        foreach ($required as $require) {
            if(empty($_POST[$require])) {
                $errors[] =' You must fill out all fields.'.$_POST[$require];
                break;
            }
        }

        if(strlen($password) < 6) {
            $errors[] = 'Your password must be at least 6 character.';
        }

        if($password != $confirm) {
            $errors[] = 'Password and Confirm password does not match.';
        }

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'You must enter a valid email.';
        }



        if(!empty($errors)) {
            echo display_error($errors);
        }else {
            //Add user to database
            $hashed = password_hash($password,PASSWORD_DEFAULT);
            $db->query("INSERT INTO users (full_name,email,password,permissions) VALUES ('$name','$email','$hashed','$permissions')");
            $_SESSION['success_flash'] = 'User has been added.';
            header('Location: users.php');

        }

    }


?>
  <h2 class="text-center">Add A New Users</h2>
    <form action="users.php?add=1" method="post">
        <div class="form-group col-md-6">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" name="name" id="name" value="<?= $name; ?>">
        </div>

        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= $email; ?>">
        </div>

        <div class="form-group col-md-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" value="<?= $password; ?>">
        </div>

        <div class="form-group col-md-6">
            <label for="confirm">Confirm Password</label>
            <input type="password" class="form-control" name="confirm" id="confirm" value="<?= $confirm; ?>">
        </div>
        
        <div class="form-group col-md-6">
            <label for="name">Permissions</label>
            <select class="form-control" name="permissions">
                <option value="" <?= (($permissions == '')? ' selected=selected' : '');?>></option>
                <option value="editor" <?= (($permissions == 'editor')? ' selected=selected' : '');?>>Editor</option>
                <option value="admin" <?= (($permissions == 'admin')? ' selected=selected' : '');?>>Admin</option>
                <option value="admin,editor" <?= (($permissions == 'admin,editor')? ' selected=selected' : '');?>>Admin & Editor</option>
            </select>
        </div>
        <div class="form-group pull-right" style="margin-top: 25px;">
            <a href="users.php" class="btn btn-default">Cancel</a>
            <input type="submit" value="Add New User" class="btn btn-primary">
        </div>

    </form>
<?php
} else {



$userQuery = $db->query("SELECT * FROM users ORDER BY full_name");


?>
<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add New User</a>
<hr>
<table class="table table-bordered table-striped table-condensed">
    <thead>
        <th></th><th>Name</th><th>Email</th><th>Join Date</th><th>Last Login</th><th>Permission</th>
    </thead>
    <tbody>
        <?php while($user = mysqli_fetch_assoc($userQuery)) : ?>
            <tr>
                <td>
                    <?php if($user['id'] != $user_data['id']) : ?>
                        <a href="users.php?delete=<?= $user['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
                    <?php endif;?>
                </td>
                <td><?= $user['full_name']; ?></td>
                <td><?= $user['email']; ?></td>
                <td><?= pretty_date($user['join_date']); ?></td>
                <td><?= (($user['last_login'] != '0000-00-00 00:00:00')?pretty_date($user['last_login']):'Never'); ?></td>
                <td><?= $user['permissions']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php }
include 'includes/footer.php';
?>
