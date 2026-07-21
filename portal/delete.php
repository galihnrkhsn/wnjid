<?php
    session_start();
    include 'koneksi.php';
    if (!isset($_SESSION['logistik'])) {
        echo "
            <script>
                alert('Login terlebih dahulu')
                location='login.php'
            </script>
        ";
        exit();
    }

    $iduser         = $_GET['id'];
    $sql            = $koneksi->query("SELECT * FROM t_user INNER JOIN logistik3 ON t_user.idlogistik = logistik3.idlogistik WHERE id_user = '$iduser'");
    $data           = $sql->fetch_assoc();
    $idlogistik     = $data['idlogistik'];

    $koneksi->begin_transaction();
    try {
        $sql1 = "DELETE FROM t_user WHERE id_user = ?";
        $stmt1 = $koneksi->prepare($sql1);
        $stmt1->bind_param('i', $iduser);
        $stmt1->execute();

        $sql2 = "DELETE FROM logistik3 WHERE idlogistik = ?";
        $stmt2 = $koneksi->prepare($sql2);
        $stmt2->bind_param('i', $idlogistik);
        $stmt2->execute();

        $koneksi->commit();

        echo "
            <script>
                alert('Data berhasil dihapus!')
                location='invoice.php'
            </script>
        ";
    } catch(Exception $e) {
        $koneksi->rollback();
        echo "Error: " . $e->getMessage();
    }

    $koneksi->close();
?>