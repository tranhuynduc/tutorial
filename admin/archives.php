<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "SELECT * FROM products WHERE deleted = 1";
$presults = $db->query($sql);


if (isset($_GET['refresh'])) {
    $id = sanitize($_GET['refresh']);
    $db->query("UPDATE products SET deleted = 0 WHERE id = '$id'");
    $_SESSION['success_flash'] = 'Product has been refresh.'.
        header('Location: archives.php');
}

if (isset($_GET['featured'])) {
    $id = (int)$_GET['id'];
    $featured = (int)($_GET['featured']);
    $featuredSql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
    $db->query($featuredSql);
    header('Location: archives.php');
}

?>
<h2 class="text-center">Archives</h2>

<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead><th></th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th></thead>
    <tbody>

    <?php while($product = mysqli_fetch_assoc($presults)) : ?>
        <?php
        $childID = $product['categories'];
        $catSql = "SELECT * FROM categories WHERE id ='$childID'";
        $result = $db->query($catSql);
        $cat = mysqli_fetch_assoc($result);
        $parentID = $cat['parent'];
        $pSql = "SELECT * FROM categories where id = '$parentID'";
        $presult = $db->query($pSql);
        $parent = mysqli_fetch_assoc($presult);
        $category2 = $parent['category'].'-'.$cat['category'];
        ?>

        <tr>
            <td>
                <a href="archives.php?refresh=<?= $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
            </td>

            <td><?= $product['title'];?></td>
            <td><?= money($product['price']);?></td>
            <td><?= $category2;?></td>

            <td><a href="archives.php?featured=<?= (($product['featured'] == 0)? '1' : '0');?>&id=<?= $product['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-<?= (($product['featured'] == 1)? 'minus' : 'plus');?>"></span></a>
                <a>&nbsp <?= (($product['featured'] == 1)? 'Featured Product' : '');?></a>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>


<?php
include 'includes/footer.php';
?>