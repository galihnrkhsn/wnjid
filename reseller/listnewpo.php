<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';

    $idmitrareseller=$_SESSION["idmitrareseller"];
    $ambil=$koneksi->query("SELECT mitrareseller.idmitrareseller, mitrareseller.namaagen, mitrareseller.mode FROM mitrareseller where idmitrareseller='$idmitrareseller' ");
    $mode=$ambil->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseller | WNJ.ID</title>
    <style>
        .aText {
            color: red;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
<div class="container mt-5" align="center">
<?php
  $dataproduk=$koneksi->query("SELECT bukapo.idbpo,
                                    bukapo.jenis_mitra,
                                    bukapo.jenis_po,
                                    bukapo.idpoproduk,
                                    bukapo.tgl,
                                    bukapo.tgl_dropship,
                                    bukapo.status,
                                    poproduk.namapo 
                                FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                                WHERE bukapo.status = 'PUBLISH' 
                                and (bukapo.jenis_mitra = 'Semua Mitra') 
                                order by bukapo.tgl desc
                              ");
  while($tampilkan=$dataproduk->fetch_assoc()){
?>

      <button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>" style="white-space: normal;width: auto;">
        <?php if ($tampilkan['jenis_po']=="PO dengan Stok"): ?>
            <a  style="color:white" href="formpostok?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO tanpa Stok" && $tampilkan['idpoproduk'] != '299'): ?>
            <a  style="color:white" href="formpoku?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Custom Tab"): ?>
            <a  style="color:white" href="formpo_tab?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Custom Tab Stok"): ?>
            <a  style="color:white" href="formpo_tabstok?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>      
        <?php if ($tampilkan['jenis_po']=="PO Custom Tab Stok Max"): ?>
            <a  style="color:white" href="formpo_tabstokmax?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>      

        <?php if ($tampilkan['jenis_po']=="PO Konin"): ?>
            <a  style="color:white" href="pokonin.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Kolibri"): ?>
            <a  style="color:white" href="pokolibri?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Miki Custom"): ?>
            <a  style="color:white" href="formpomikicustomstock?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Miki Polos"): ?>
            <a  style="color:white" href="formpomikipolosstock?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?> (Polos)</a>
        <?php endif ?>


        <?php if ($tampilkan['jenis_po']=="PO Brooch Custom"): ?>
            <a  style="color:white" href="formpobrooch_custom?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>  
        <?php if ($tampilkan['jenis_po']=="PO Bagi Rata"): ?>
            <a  style="color:white" href="formbagirata?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>    
        <?php if ($tampilkan['jenis_po']=="PO Hampers"): ?>
            <a  style="color:white" href="formpo_thr?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>  

        <?php if ($tampilkan['jenis_po']=="PO Karakter Stok"): ?>
            <a  style="color:white" href="formpo_karakterstok?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
        <?php endif ?>                          
        
        <?php if ($tampilkan['jenis_po']=="PO custom Rocela"): ?>
            <a  style="color:white" href="formporocela?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitrareseller=<?= $idmitrareseller ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
        <?php endif ?>                          
        
        <?php if ($tampilkan['jenis_po']=="PO custom Goura"): ?>
            <a  style="color:white" href="formpogoura?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitrareseller=<?= $idmitrareseller ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
        <?php endif ?>                          
        
        <?php if ($tampilkan['jenis_po']=="PO custom Bundling"): ?>
            <a  style="color:white" href="formpocustomgabungan?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitrareseller=<?= $idmitrareseller ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
        <?php endif ?>
        
        <?php if ($tampilkan['jenis_po']=="PO Custom Inner"): ?>
            <a  style="color:white" href="formpoinner.php?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitrareseller=<?= $idmitrareseller ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        
        <?php if ($tampilkan['idpoproduk'] == 260 ): ?>
            <!--nama file nya formpocustomlegging.php?id=260-->
            <a  style="color:white" href="formpocustomlegging?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitrareseller=<?= $idmitrareseller ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>
        
        <?php if ($tampilkan['idpoproduk'] == 261 ): ?>
            <!--nama file nya formpocustomlegging2.php?id=261-->
            <a  style="color:white" href="formpocustomlegging2?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitrareseller=<?= $idmitrareseller ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>
        
        <?php if ($tampilkan['idpoproduk'] == 269 ): ?>
            <!--nama file nya formpocustomlegging2.php?id=261-->
            <a  style="color:white" href="formpomatari.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>
        
        <?php if ($tampilkan['jenis_po'] == "PO Ducula Stok" ): ?>
            <!--nama file nya formpocustomlegging2.php?id=261-->
            <a  style="color:white" href="formpoducula.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>

        <?php if ($tampilkan['jenis_po'] == "PO Tazmahal" ): ?>
            <!--nama file nya formpocustomlegging2.php?id=261-->
            <a  style="color:white" href="formpotazmahal.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>

        <?php if ($tampilkan['jenis_po'] == "PO Bundling 2"): ?>
            <a  style="color:white" href="formpobundling2?id=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $idmitra ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        
        <?php if ($tampilkan['idpoproduk'] == "289" ): ?>
            <a  style="color:white" href="formposongkok.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>

        <?php if ($tampilkan['idpoproduk'] == "331" ): ?>
            <a  style="color:white" href="pocustom.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>

        <?php if ($tampilkan['jenis_po']=="PO Bundling Custom"): ?>
            <a  style="color:white" href="formpobundling.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>
        
        <?php if ($tampilkan['idpoproduk'] == "299" or $tampilkan['idpoproduk'] == "301"): ?>
            <a  style="color:white" href="formpovoal_custom.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>


      </button>
      <p id="demomiki<?= $tampilkan['idbpo']; ?>" class="countdown" data-countdown-target="<?= htmlspecialchars($tampilkan['tgl']); ?> 23:59:00" data-link="linkmiki<?= (int) $tampilkan['idbpo']; ?>"></p>
      <br>

<?php } ?>
<script>
(function () {
    var items = Array.prototype.map.call(document.querySelectorAll('.countdown[data-countdown-target]'), function (el) {
        return {
            el: el,
            target: new Date(el.dataset.countdownTarget).getTime(),
            link: document.getElementById(el.dataset.link)
        };
    });
    if (!items.length) return;
    var timer;
    function tick() {
        var now = Date.now();
        items = items.filter(function (item) {
            var distance = item.target - now;
            if (distance < 0) {
                item.el.innerHTML = "Link PO tidak tersedia";
                if (item.link) item.link.style.display = "none";
                return false;
            }
            var days = Math.floor(distance / 86400000);
            var hours = Math.floor((distance % 86400000) / 3600000);
            var minutes = Math.floor((distance % 3600000) / 60000);
            var seconds = Math.floor((distance % 60000) / 1000);
            item.el.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
            return true;
        });
        if (!items.length) clearInterval(timer);
    }
    tick();
    timer = setInterval(tick, 1000);
})();
</script>

      
      <div class="text-center mt-5 mb-5" style="color: var(--color1)">
        <h3>PO Regular</h3>
      </div> 

      <div class="table-responsive">
      <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="col-4 text-center">Tanggal</th>
                    <th class="col-6 text-center">Nama PO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmtRegular = $koneksi->prepare("SELECT
                        pomitra.tgl,
                        pomitra.status AS pomitra_status,
                        pomitra.invoice,
                        poproduk.namapo,
                        poproduk.idpoproduk,
                        poproduk.status AS poproduk_status,
                        poproduk.jenis
                    FROM
                        pomitra
                    INNER JOIN
                        poproduk ON pomitra.idpoproduk = poproduk.idpoproduk
                    WHERE
                        pomitra.idmitrareseller = ?
                        AND poproduk.status = 'Open'
                        AND poproduk.tipe = 'Normal'
                        AND poproduk.idpoproduk > 233
                    GROUP BY
                        poproduk.idpoproduk, poproduk.namapo, poproduk.status
                    ORDER BY
                        pomitra.tgl DESC
                ");
                $stmtRegular->bind_param('s', $idmitrareseller);
                $stmtRegular->execute();
                $sql = $stmtRegular->get_result();

                while($data = mysqli_fetch_array($sql)){
                    $idpoproduk = $data['idpoproduk'];
                ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($data['tgl']); ?></td>
                    <td class="text-center">
                        <?php if ($data['jenis'] == 'Kolibri'): ?>
                            <a href="detailkolibri?id=<?php echo $data['idpoproduk']; ?>"><?php echo htmlspecialchars($data['namapo']); ?></a>
                        <?php elseif($data['idpoproduk'] == '259' || $data['idpoproduk'] == '267'): ?>
                            <a href="detailkonin?id=<?php echo $data['idpoproduk']; ?>"><?php echo htmlspecialchars($data['namapo']); ?></a>
                        <?php elseif($data['idpoproduk'] == '343'): ?>
                            <a href="detailpokonin.php?id=<?= $data['idpoproduk']; ?>" class="aText"><?= htmlspecialchars($data['namapo']); ?></a>
                        <?php else: ?>
                            <a href="detailpo?id=<?php echo $data['idpoproduk']; ?>" class="aText"><?php echo htmlspecialchars($data['namapo']); ?></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
      </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>