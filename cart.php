<?php
require_once  $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';

if ($cart_id != '') {
    echo $cart_id;
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'],true);
    $i = 1;
    $sub_total = 0;
    $item_count = 0;

}
?>

<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">My Shopping Cart</h2>
        <hr>
        <?php if ($cart_id == '') : ?>
            <div class="bg-danger">
                <p class="text-center text danger">Your shopping cart is empty.</p>
            </div>
        <?php else : ?>
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <th>#</th><th>Item</th><th>Price</th><th>Quantity</th><th>Size</th><th>Sub Total</th>
                </thead>
                <tbody>
                    <?php
                        foreach ($items as $item) {
                            $product_id = $item['id'];
                            $productQ = $db->query("SELECT * FROM products WHERE id = '$product_id'");
                            $product = mysqli_fetch_assoc($productQ);
                            $sArray = explode(',',$product['sizes']);
//                            var_dump($sArray);
                            foreach ($sArray as $sizeString) {
                                $s = explode(':',$sizeString);
                                if($s[0] == $item['size']) {
                                    $available = $s[1];
                                }
                            }
                    ?>
                            <tr>
                                <td><?= $i++ ;?></td>
                                <td><?= $product['title'] ;?></td>
                                <td><?= money($product['price']) ;?></td>
                                <td>
                                    <button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?= $product['id'];?>', '<?= $item['size'];?>')">-</button>
                                    <?= $item['quantity'] ;?>
                                    <?php if($item['quantity'] < $available) : ?>
                                    <button class="btn btn-xs btn-default" onclick="update_cart('addone','<?= $product['id'];?>', '<?= $item['size'];?>')">+</span></button>
                                    <?php else :?>
                                    <span class="text-danger"> Max</span>
                                    <?php endif;?>
                                </td>
                                <td><?= $item['size'] ;?></td>
                                <td><?= money($item['quantity'] * $product['price']) ;?></td>

                            </tr>

                    <?php
                            $item_count += $item['quantity'];
                            $sub_total += $item['quantity'] * $product['price'];
                    }

                    $tax = TAXRATE * $sub_total;
                    $tax = number_format($tax,2);
                    $grand_total = $tax + $sub_total;
                    ?>

                </tbody>
            </table>
            <table class="table table-bordered table-condensed text-right">
                <legend>Totals</legend>
                <thead class="totals_table_header"><th>Total Items</th><th>Sub Total</th><th>Tax</th><th>Grand Total</th></thead>
                <tbody>
                    <tr>
                        <td><?= $item_count; ?></td>
                        <td><?= money($sub_total); ?></td>
                        <td><?= money($tax); ?></td>
                        <td class="bg-success"><?= money($grand_total); ?></td>

                    </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-shopping-cart"></span> Check Out >>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Shopping Address</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form action="thankYou.php" method="post" id="payment-form">
                                    <div id="step1" style="display: block;">
                                        <div class="form-group col-md-6">
                                            <label for="full_name">Full Name</label>
                                            <input type="text" id="full_name" name="full_name" class="form-control">
                                        </div>
                                    </div>
                                    <div id="step1" style="display: block;">
                                        <div class="form-group col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div id="step1" style="display: block;">
                                        <div class="form-group col-md-6">
                                            <label for="full_name">Full Name</label>
                                            <input type="text" id="full_name" name="full_name" class="form-control">
                                        </div>
                                    </div>
                                    <div id="step1" style="display: block;">
                                        <div class="form-group col-md-6">
                                            <label for="full_name">Full Name</label>
                                            <input type="text" id="full_name" name="full_name" class="form-control">
                                        </div>
                                    </div>
                                    <div id="step1" style="display: block;">
                                        <div class="form-group col-md-6">
                                            <label for="full_name">Full Name</label>
                                            <input type="text" id="full_name" name="full_name" class="form-control">
                                        </div>
                                    </div>
                                    <div id="step2" style="display: none">


                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>




<?php
include 'includes/footer.php';
?>
