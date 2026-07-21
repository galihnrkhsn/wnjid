<form method="post">

<label>Nama Artikel</label><br>
    <select name="idharga">
        <?php $ambil=$koneksi->query("SELECT * FROM pricelist");
        while($data=$ambil->fetch_assoc()){
        ?>
        <option value="<?php echo $data['idharga']; ?>"><?php echo $data['namaartikel']; ?></option>
        <?php } ?>
    </select><br>
    <button class="btn btn-primary" name="cari">Pilih</button><br><br>
</form>



Update List Harga Artikel<br>

<?php
  $idharga=$_POST["idharga"];    
  $query = "SELECT * FROM pricelist WHERE idharga='$idharga'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql); 
  ?>

<form method="post">
    <input type="hidden" name="idharga" value=<?php echo $data['idharga']; ?>>
    <label>Nama Produk</label><br>
    <input type="text" name="namaartikel" value=<?php echo $data['namaartikel']; ?>><br>
      <label>Harga Konsumen</label><br>
    <input type="text" name="harga_ecer_d" value=<?php echo $data['harga_ecer_d']; ?>><br>
      <label>Harga Distributor</label><br>
    <input type="text" name="harga_d" value=<?php echo $data['harga_d']; ?>><br>
    <label>Harga Agen</label><br>
    <input type="text" name="harga_a" value=<?php echo $data['harga_a']; ?>><br>
    <label>Harga Reseller</label><br>
   <input type="text" name="harga_ecer_a" value=<?php echo $data['harga_ecer_a']; ?>>
   	<button class="btn btn-primary" name="save">Ubah</button>
</form>
<?php

if(isset($_POST["save"])){
$idharga= $_POST['idharga'];    
$namaartikel = $_POST['namaartikel'];
$harga_ecer_d = $_POST['harga_ecer_d'];
$harga_d = $_POST['harga_d'];
$harga_a = $_POST['harga_a'];
$harga_ecer_a = $_POST['harga_ecer_a'];

    // Proses ubah data ke Database
    $koneksi->query( "UPDATE pricelist SET namaartikel='".$namaartikel."', harga_ecer_d='".$harga_ecer_d."', harga_d='".$harga_d."', harga_a='".$harga_a."', harga_ecer_a='".$harga_ecer_a."' 
                    where idharga='$idharga'");
		
		echo "<script>alert('hapus data berhasil');</script>";
		echo "<script>location='index.php';</script>";

}


?>