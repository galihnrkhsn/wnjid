<form method="post" enctype="multipart/form-data">
    
    <label>No Invoice</label>
    <select name="invoice">
        <?php $ambil=$koneksi->query("SELECT * FROM list_po_mitra");
        while($data=$ambil->fetch_assoc()){
        ?>
        <option value="<?php echo $data['inv']; ?>"><?php echo $data['inv']; ?></option>
        <?php } ?>
    </select><br><br>
    
     <label>File PDF</label>
 <input type="file" name="nama_file" required><br><br>
   	<button class="btn btn-primary" name="save">Update</button>
</form>

<?php

if(isset($_POST["save"])){

//pengecekan tipe harus pdf
$tipe_file = $_FILES['nama_file']['type']; //mendapatkan mime type
if ($tipe_file == "application/pdf") //mengecek apakah file tersebu pdf atau bukan
{
 $invoice=$_POST["invoice"];    
 $nama_file = trim($_FILES['nama_file']['name']);
 //mengganti nama pdf
 $file_temp = $_FILES['nama_file']['tmp_name']; //data temp yang di upload
 $folder    = "file"; //folder tujuan

 move_uploaded_file($file_temp, "$folder/$nama_file"); //fungsi upload
 //update nama file di database
 	$koneksi->query("UPDATE list_po_mitra SET file='$nama_file' WHERE inv='$invoice' ");


        }
}
?>