<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "koneksi.php";

    $product_id = $_GET['product_id'];

    $q = $koneksi->query("SELECT * FROM variants WHERE idproducts = '$product_id' GROUP BY variant");

    while($d = $q->fetch_assoc()){
?>

<tr>
<td>
<input type="checkbox" class="variant-check"
       data-id="<?= $d['id'] ?>"
       data-nama="<?= $d['variant'] ?>">
</td>
<td><?= $d['variant'] ?></td>

</tr>

<?php } ?>