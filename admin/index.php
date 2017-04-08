<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
if(!is_loogin_in()) {
    header('Location: login.php');
}

if(!has_permission()) {
    permission_error_redirect('brands.php');
}

include 'includes/head.php';
include 'includes/navigation.php';
$item = array();
$item[] = array(
    'id'        => 1,
    'size'      => 2,
    'quantity'  => 3,
);
echo $items_json = json_encode($item);

?>
<h4>Admin</h4>


<?php
    include 'includes/footer.php';
?>

