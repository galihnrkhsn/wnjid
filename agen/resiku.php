<?php 


include 'koneksi.php'; 
//include 'floatingbutton.php';



?>
<html lang="en">
<head>
<title>Resi</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
 


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


  <!--DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" />
  <!--Daterangepicker -->
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>


<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"></div>
  <div class="col-8" ><p>INFORESI</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 12px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru i {
  margin-top: 15px;
  padding: 2px 0;
  font-size: 23px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<!--================ NAVBARU END =================-->
<div class="container">

   <center><h6 style="font-family:verdana;" class="outset">Menu informasi resi pengiriman semua ekspedisi wanoja dan zizazu</h6></center>

<?php
            error_reporting(0);
            $konek = new mysqli("localhost","wnj218","Padasuk@218","db_portalwnj");
            
            $tgl=$_GET['id'];
            
            
            ?>
 <br>

<div class="panel-body">               
 
<div class="table-responsive">                                       
<table class="table table-striped table-bordered table-hover" id="example">
    <thead>
    <tr>
        <th >No</th>
        <th >Penerima</th>
        <th >Tanggal</th>
        <th >Ekpedisi</th>
        <th >No Resi</th>
        <th >Biaya Kirim</th>
        <th>Keterangan</th>
        
    </tr>
    </thead>
    <tbody>
    <?php
     
        $ambil=$konek->query("SELECT * FROM logistik WHERE idadmin='Zizazu' or jenis_mitra='Zizazu' order by tgl desc LIMIT 1500"); 
        $no=1;
          while($tampil=$ambil->fetch_assoc()){
  ?>
    <tr>
       <td><?php echo $no++;; ?></td> 
       <td><?php echo $tampil["penerima"]; ?></td> 
       <td><?php echo date('d/m/Y', strtotime($tampil["tgl"])); ?></td> 
       <td><?php echo $tampil['ekspedisi']; ?></td> 
       <td><?php echo $tampil["noresi"]; ?></td> 
       <td>Rp.<?php echo number_format($tampil["biayakirim"]); ?></td> 
       <td><?php echo $tampil["keterangan"]; ?></td>
      
    </tr>
      <?php
      } ?>

</tbody>
</table>

</div>
<div id="cekresicom_id" align="center"></div>
      <script type="text/javascript" src="https://cekresi.com/widget/widgetcekresicom_v1.js"></script>
      <script type="text/javascript">
      init_widget_cekresicom('w1',380,110)
      </script>
    </div>
      

<!--Jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!--Boostrap -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   <!--DataTables -->
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
  <!--DateRangePicker -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  
  <script type="text/javascript"> 
   //fungsi untuk filtering data berdasarkan tanggal 
   var start_date;
   var end_date;
   var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
      var dateStart = parseDateValue(start_date);
      var dateEnd = parseDateValue(end_date);
      //Kolom tanggal yang akan kita gunakan berada dalam urutan 2, karena dihitung mulai dari 0
      //nama depan = 0
      //nama belakang = 1
      //tanggal terdaftar =2
      var evalDate= parseDateValue(aData[2]);
        if ( ( isNaN( dateStart ) && isNaN( dateEnd ) ) ||
             ( isNaN( dateStart ) && evalDate <= dateEnd ) ||
             ( dateStart <= evalDate && isNaN( dateEnd ) ) ||
             ( dateStart <= evalDate && evalDate <= dateEnd ) )
        {
            return true;
        }
        return false;
  });

  // fungsi untuk converting format tanggal dd/mm/yyyy menjadi format tanggal javascript menggunakan zona aktubrowser
  function parseDateValue(rawDate) {
      var dateArray= rawDate.split("/");
      var parsedDate= new Date(dateArray[2], parseInt(dateArray[1])-1, dateArray[0]);  // -1 because months are from 0 to 11   
      return parsedDate;
  }    

  $( document ).ready(function() {
  //konfigurasi DataTable pada tabel dengan id example dan menambahkan  div class dateseacrhbox dengan dom untuk meletakkan inputan daterangepicker
   var $dTable = $('#example').DataTable({
    "pageLength": 25,
         "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "language": {
    "decimal":        "",
    "emptyTable":     "Tidak ada data di dalam tabel",
    "info":           "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
    "infoEmpty":      "Ditampilkan 0 sampai 0 dari 0 data",
    "infoFiltered":   "(Disaring dari _MAX_ total data)",
    "infoPostFix":    "",
    "thousands":      ",",
    "lengthMenu":     "Tampilkan _MENU_ Data",
    "loadingRecords": "Memuat...",
    "processing":     "Pemrosesan...",
    "search":         "Cari Data:",
    "zeroRecords":    "Data yang dicari tidak ditemukan",
    "paginate": {
        "first":      "Awal",
        "last":       "Akhir",
        "next":       "&#10095;",
        "previous":   "&#10094;"
    }

    },
    "dom": "<'row'<'col-sm-4'l> <'col-sm-5' <'datesearchbox'>><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>"
   });

   //menambahkan daterangepicker di dalam datatables
   $("div.datesearchbox").html('<div class="input-group" style="margin-right:10%;"> <div class="input-group-addon"> <i class="glyphicon glyphicon-calendar"></i> </div><input type="text" class="form-control pull-right" id="datesearch" placeholder="Cari per Range Tanggal.."> </div>');

   document.getElementsByClassName("datesearchbox")[0].style.textAlign = "right";

   //konfigurasi daterangepicker pada input dengan id datesearch
   $('#datesearch').daterangepicker({
      autoUpdateInput: false
    });

   //menangani proses saat apply date range
    $('#datesearch').on('apply.daterangepicker', function(ev, picker) {
       $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
       start_date=picker.startDate.format('DD/MM/YYYY');
       end_date=picker.endDate.format('DD/MM/YYYY');
       $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
       $dTable.draw();
    });

    $('#datesearch').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
      start_date='';
      end_date='';
      $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
      $dTable.draw();
    });
  });
</script>


</body>
