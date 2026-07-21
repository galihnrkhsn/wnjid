<?php
    session_start();
    error_reporting (0);
    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $query      = $koneksi->query("SELECT * FROM poproduk INNER JOIN bukapo ON bukapo.idpoproduk = poproduk.idpoproduk WHERE poproduk.idpoproduk = '$idpoproduk'");
    $datapo     = $query->fetch_assoc();

    $namapo     = $datapo['namapo'];
    $idadmin    = $_SESSION['idadmin'];
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Summary <?= $namapo ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <style>
            * {
                font-family: "Inter", sans-serif;
            }

            .custom-width {
                width: 60%; /* Default width untuk layar kecil */
            }

            @media (min-width: 768px) { /* Mulai dari layar ukuran medium (tablet) */
                .custom-width {
                    width: 40%;
                }
            }

            @media (min-width: 992px) { /* Mulai dari layar ukuran besar (desktop) */
                .custom-width {
                    width: 20%;
                }
            }
        </style>
    </head>
    <body>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center">
                <a href="detailpokonin.php?id=<?= $idpoproduk ?>" class="text-muted"><i class="bi bi-chevron-left"></i></a>
                <p class="navbar-brand text-uppercase fw-bold mb-0">Data Summary <?= $namapo ?></p>
                <i class="opacity-0 bi bi-chevron-right"></i>
            </div>
        </nav>

        <div class="container mt-3">
            <div class="col-sm-12 text-center">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive mt-2">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        SUM(pomitra.jumlah) AS jumlah
                                                                    FROM
                                                                        pomitra
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                    WHERE
                                                                        pomitra.idpoproduk = '$idpoproduk'
                                                                            AND pomitra.idmitra = '$idadmin'
                                                                            AND pomitra.jumlah > 0
                                                                    GROUP BY pomitra.idpodetail
                                                                ");
                                        if ($sql->num_rows > 0) {
                                            $no = 1;
                                            while($datapo = $sql->fetch_assoc()) {
                                                $total_harga = $datapo['jumlah'] * $datapo['harga'];
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $datapo['variant'] ?></td>
                                            <td><?= $datapo['jumlah'] ?></td>
                                            <td>Rp. <?= number_format($total_harga) ?></td>
                                        </tr>
                                    <?php
                                                $jumlah_harga += $total_harga;
                                                $jumlah_qty += $datapo['jumlah'];
                                            }
                                        } else {
                                    ?>
                                        <td colspan="5" class="text-center">Tidak ada barang</td>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th><?= $jumlah_qty ?></th>
                                        <th>Rp. <?= number_format($jumlah_harga) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex mt-5 justify-content-center">
            <button id="exportPdfBtn" class="btn btn-sm btn-danger d-flex align-items-center">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </button>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const exportPdfBtn = document.getElementById('exportPdfBtn');
                const elementToExport = document.querySelector('.container.mt-3');                
                const poNameElement = document.querySelector('.navbar-brand');
                const poName = poNameElement ? poNameElement.textContent.replace('Data Summary ', '').trim() : 'DataSummary';
                
                exportPdfBtn.addEventListener('click', function() {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('p', 'mm', 'a4');
                    
                    html2canvas(elementToExport, {
                        scale: 2,
                        logging: false,
                        useCORS: true
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const imgWidth = 210;
                        const pageHeight = 295;
                        const imgHeight = canvas.height * imgWidth / canvas.width;
                        let heightLeft = imgHeight;
                        let position = 0;
                        
                        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                        
                        while (heightLeft >= 0) {
                            position = heightLeft - imgHeight;
                            doc.addPage();
                            doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                            heightLeft -= pageHeight;
                        }
                        
                        doc.save(`${poName}_Summary.pdf`);
                    });
                });
            });
        </script>
    </body>
</html>