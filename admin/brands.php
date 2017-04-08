<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
    if(!is_loogin_in()) {
        login_error_redirect();
    }
    include 'includes/head.php';
    include 'includes/navigation.php';

    //get brands from database
    $sql = "SELECT * FROM brand ORDER BY brand";
    $results = $db->query($sql);
    $error = array();

    //Edit Brand
    if(isset($_GET['edit']) && !empty($_GET['edit'])) {
        $edit_id = (int)$_GET['edit'];
        $edit_id = sanitize($edit_id);
        $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
        $edit_result = $db->query($sql2);
        $eBrand = mysqli_fetch_assoc($edit_result);

    }

    //Delete Brand
    if(isset($_GET['delete']) && !empty($_GET['delete'])) {
        $delete_id = (int)$_GET['delete'];
        $delete_id = sanitize($delete_id);
        $sql = "DELETE FROM brand WHERE id = '$delete_id'";
        $db->query($sql);
        $_SESSION['success_flash'] = 'Brand has been deleted.';
        header('Location: brands.php');

    }


    //If add form is submitted
    if(isset($_POST['add_submit'])) {
        $brand = sanitize($_POST['brand']);
        if($_POST['brand'] == '') {
            $error[] .= " You must enter a brand";
        }

        $sql = "SELECT * FROM brand WHERE brand = '$brand' ";

        if(isset($_GET['edit'])){
            $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
        }
        $result = $db->query($sql);
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            $error[] .= $brand.' already exist, please choose another brand name';
        }

        if(!empty($error)) {
            echo display_error($error);
        } else {
            $sql = "INSERT INTO brand (brand) VALUES ('$brand')";
            $_SESSION['success_flash'] = 'Brand has been added.';

            if(isset($_GET['edit'])){

                $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id' ";
                $_SESSION['success_flash'] = 'Brand has been edited.';
            }
            $db->query($sql);
            header('Location: brands.php');
        }
    }
?>
<h2 class="text-center">Brands</h2>


<div class="text-center">
    <form action="brands.php<?=((isset($_GET['edit'])))?'?edit='.$edit_id:'' ;?>" method="post" class="form-inline">
        <div class="form-group">
            <?php
            $brand_value = '';
            if(isset($_GET['edit'])){
                $brand_value = $eBrand['brand'];
            }else {
                if(isset($_POST['brand'])) {
                    $brand_value = sanitize($_POST['brand']);
                }
            }
            ?>
            <label for="brand"><?=((isset($_GET['edit'])))?'Edit':'Add A' ;?> Brand:</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value; ?>" >
            <?php if(isset($_GET['edit'])) : ?>
                <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit'])))?'Edit':'Add' ;?> Brand" class="btn btn-success">
        </div>
    </form>
</div>
<table class="table table-bordered table-stripped table-auto table-condensed">
    <thead>
        <th></th>
        <th>Bramd</th>
        <th></th>
    </thead>

    <tbody>
    <?php while ($brand = mysqli_fetch_assoc($results)) : ?>
        <tr>
            <td><a href="brands.php?edit=<?= $brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><?= $brand['brand'] ;?></td>
            <td><a href="brands.php?delete=<?= $brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>



<?php
include 'includes/footer.php';
?>

