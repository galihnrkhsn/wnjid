<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
?>

<h3><strong>Input List PO Mitra</strong></h3>

<form method="post" enctype="multipart/form-data">
    <label>Produk PO</label><br>
 <input type="text" name="produkpo"><br>
    
      <label>Status PO</label><br>
   <select name="status">
       <option></option>
       <option value="proses">proses</option>
       <option value="selesai">selesai</option>
   </select><br>
   
      <label>No INV</label><br>
    <input type="text" name="inv"><br>
    
    <label>Nama Mitra</label><br>
    <select name="idadmin">
        <?php $ambil=$koneksi->query("SELECT * FROM admin_mitra");
        while($data=$ambil->fetch_assoc()){
        ?>
        <option value="<?php echo $data['idadmin']; ?>"><?php echo $data['namamitra']; ?></option>
        <?php } ?>
    </select><br>
    
    <label>Keterangan</label><br>
    <textarea name="keterangan"></textarea><br>
    
     <label>Tanggal</label><br>
    <input type="date" name="tgl"><br><br>
    
    <label>Invoice PDF</label>
    <input type="file" name="nama_file" required><br><br>
   	<button class="btn btn-primary" name="save">tambah</button>
</form>
<?php
if(isset($_POST["save"])){
	
	$produkpo = $_POST["produkpo"];
	$status = $_POST["status"];
	$inv = $_POST["inv"];
    $idadmin = $_POST["idadmin"];
    $keterangan = $_POST["keterangan"];
    $tgl=$_POST["tgl"];
    
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
    
		$koneksi->query("INSERT INTO list_po_mitra (produkpo,status,inv,idadmin,keterangan,tgl,file)
			VALUES ('$produkpo','$status','$inv','$idadmin','$keterangan','$tgl','$nama_file')");


			echo "<script>alert('data berhasil ditambah');</script>";
		echo "<script>location='index.php?page=inputlistpomitra';</script>";

	}
}

?>