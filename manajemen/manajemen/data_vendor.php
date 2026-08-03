<?php 
    session_start();
    include '../koneksi.php'; 
    include '../assets/components/Sessions/sesManage.php';
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }
    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $role ?> | Wanoja</title>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Bill</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</head>

</head> 
<body>
    <!-- NAVBAR -->
    <?php include "../assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <a href="../index" class="btn btn-info float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
        <h2 class="mb-3">Tabel Data Vendor</h2>
        <table class="table table-striped table-bordered" id="vendortable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Vendor</th>
                    <th class="text-center">
                        <select name="skill_dropdown" id="skill_dropdown">
                            <option value="View">View</option>
                            <option value="Edit">Edit</option>
                            <option value="Delete">Delete</option>
                        </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no             = 1;
                    $queryData      = $koneksi->query("SELECT * FROM vendor_bill");
                    while($rowData  = $queryData->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $rowData['nama_vendor']; ?></td>
                    <td>
                        <div class="View skill">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modalView<?= $row['idbill'];?>"><i class="fa fa-eye"></i></button>
                        </div>
                        <div class="Edit skill" style="display: none">
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalEdit<?= $row['idbill']; ?>"><i class="fa fa-edit"></i></button>
                        </div>
                        <form method="POST">
                            <div class="Delete skill" style="display: none">
                                <input type="hidden" value="<?= $row['idbill']; ?>" name="idbill" id="idbill" readonly>
                                <button class="btn btn-danger" type="submit" name="hapus" onclick="return confirm('Yakin Akan Hapus Data?');"><i class="fa fa-trash"></i></button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- MAIN CONTENT END -->
    <!-- FOOTER -->
    <?php include "../assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {
            $('#vendortable').DataTable({
                ordering: false
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#skill_dropdown").change(function () {
                var inputVal = $(this).val();
                var eleBox = $("." + inputVal);
                $(".skill").hide();
                $(eleBox).show();
            });
        });
    </script>
    <!-- END SCRIPT -->
</body>
</html>