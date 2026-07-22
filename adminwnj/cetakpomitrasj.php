<?php
include "koneksi.php";
$invoice=$_GET["invoice"];
$idpoproduk=$_GET["idpoproduk"];
$sum=0;

  $datamitra=$koneksi->query("SELECT pomitra.idpoproduk,admin_mitra.namamitra,
                              admin_mitra.idadmin,
                              admin_mitra_cs.namacs,
                              mitraagen.namaagen as agen, 
                              mitraagen.idmitraagen as idagen, 
                              mitrareseller.namaagen as reseller,
                              mitrareseller.idmitrareseller as idreseller, 
                              mitramarketer.namaagen as marketer, 
                              mitramarketer.idmitramarketer as idmarketer
                            FROM pomitra
                            LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
                              LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
                              LEFT JOIN admin_mitra on admin_mitra.idadmin=pomitra.idmitra or mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin 
                              LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin
                            where pomitra.invoice='$invoice'");
                            $tampilnama=$datamitra->fetch_assoc();
?>


  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

<style type="text/css">
  table, th, td, tr {
  border: 2px solid;
   border-collapse: collapse;
}
body{
  color: black;
}
</style>


<center><p style="font-size:60;margin-bottom:0;"><strong>WNJ.ID</strong></p></center>
<center><p style="font-size:40;margin-top:0;"><strong>Inv. <?= $invoice?></strong></p></center>

  <div class="row align-items-start">
    <div class="col">
       <h5><strong style="float:left">Mitra : <?php echo $tampilnama['idadmin']; ?> 
       <?php if ($tampilnama['idagen'] or $tampilnama['idreseller'] or $tampilnama['idmarketer']): ?>
       / <?= $tampilnama['idagen']; ?><?= $tampilnama['idreseller']; ?><?= $tampilnama['idmarketer']; ?>         
       <?php endif ?>
       </strong></h5>
    </div>
    <div class="col">
    </div>
    <div class="col">
      <h5><strong style="float:rigth">Nama CS : <?php echo $tampilnama['namacs']; ?> </strong></h5>
    </div>
  </div>
    <table style="width:100%;font-size: 28px">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <?php if(substr($invoice,0,1) == "K") : ?>
                    <th>Custom</th>
                    <th>Progres</th>
                <?php endif; ?>

<?php if(substr($invoice,0,2)=="MH" and substr($invoice,2,1)<>"P") : ?>            
              <th>Custom</th>
              <th>Font</th>
<?php endif; ?>                  
                <th>Checker</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
<?php 
    $datapodropship=$koneksi->query("SELECT podetail.harga,
                                            podetail.variant, 
                                            pomitra.jumlah,
                                            pomitra.custom, 
                                            pomitra.font,  
                                            pomitra.invoice, 
                                            pomitra.idpodetail
                                    FROM pomitra
                                    JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.invoice= '$invoice'
                                    AND pomitra.jumlah>0
                                    ");
$no=1;
        while($tampilkan=$datapodropship->fetch_assoc()){
?>
            <tr>
                <td><?php echo $no++; ?></td>    
                <td><?= $tampilkan['variant'];?></td>
                <td><center><?= $tampilkan['jumlah'];?></center></td>
                <?php if(substr($tampilkan['invoice'],0,1) == "K") : ?>
                    <td><?= $tampilkan['custom']; ?></td>
                    <td style="text-align: center"><input type="checkbox"></td>
                <?php endif; ?>
                
<?php if(substr($tampilkan['invoice'],0,2)=="MH" and substr($tampilkan['invoice'],2,1)<>"P") : ?>             
                <td><?= $tampilkan['custom']; ?></td>  
                <td><?= $tampilkan['font']; ?></td>
<?php endif; ?>                     
                <td></td>
                <td></td>
            </tr>
<?php 
$qty += $tampilkan['jumlah'];
$totalbayar +=$total;
?>                       
<?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total QTY</td>
                <td colspan="3"><?= $qty ?></td>
            </tr>
        </tfoot>
    </table>
<br>
<br>
<center>
<table style="font-size: 25px;border-color: white;">
  <tr>
    <td style="border-color: white;">
      <center>Gudang</center>
      <br>
        <br>
        <br>
        <br>      
    </td>
    <td width="5%" style="border-color: white;">
      
    </td>
    <td style="border-color: white;">
      <center>Checker</center>
      <br>
        <br>
        <br>
        <br>      
    </td>
    <td width="5%" style="border-color: white;">
      
    </td>    
    <td style="border-color: white;">
      <center>Penerima</center>
      <br>
        <br>
        <br>
        <br>
    </td>
    </tr>
    <tr>
      <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>      
    </td >
    <td style="border-color: white;"></td>
      <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>     
    </td>   
    <td style="border-color: white;"></td> 
    <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>
    </td>
  </tr>
</table>
    </center>

            <div>
                <p style="font-size: 22px"> 
                  Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
<br>
<br>

</body>

<script>
window.print();
</script>

</html>

                                                          

                    