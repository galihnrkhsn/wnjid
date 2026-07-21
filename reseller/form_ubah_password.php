<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';

	$idmitrareseller = $_SESSION['idmitrareseller'];

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Reseller | Wanoja</title>
	</head>
	<style>
        .form-container {
            margin-top: 50px;
            padding: 30px;
            background: #f7f7f7;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar2.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h3 class="text-center">Ubah Password</h3>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="passwordlama">Password Lama</label>
                            <input class="form-control" type="password" name="passwordlama" id="passwordlama" value="">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="checkpasslama">
                            <label class="form-check-label" for="checkpasslama">Tampilkan Password</label>
                        </div>
                        <div class="form-group">
                            <label for="passwordbaru">Password Baru</label>
                            <input class="form-control" type="password" name="passwordbaru" id="passwordbaru" value="">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="checkpassbaru">
                            <label class="form-check-label" for="checkpassbaru">Tampilkan Password</label>
                        </div>
                        <hr>
                        <div class="form-group">
                            <button type="submit" name="ubah" class="btn btn-primary btn-block">Ubah</button>
                            <a class="btn btn-secondary btn-block" href='profile.php'>Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

	<!-- PHP -->
	<?php
		if (isset($_POST['ubah'])) {
			$passwordlama = $_POST["passwordlama"];
			$passwordbaru = $_POST["passwordbaru"];

			// Ambil data pengguna dari database
			$sql = $koneksi->query("SELECT * FROM mitrareseller WHERE idmitrareseller = '$idmitrareseller'");

			if ($sql && $sql->num_rows > 0) {
				$hasil 	= $sql->fetch_assoc();
				$idUser = $hasil['iduser'];

				$sqlUser = $koneksi->query("SELECT * FROM users WHERE id = '$idUser'");				
				
				if ($sqlUser && $sqlUser->num_rows > 0) {
					$hasilUser = $sqlUser->fetch_assoc();
					if (password_verify($passwordlama, $hasilUser['password'])) {
						$passwordbaruHash = password_hash($passwordbaru, PASSWORD_DEFAULT);
					
						$updatePass = "UPDATE users SET password = '$passwordbaruHash' WHERE id = '$idUser'";
						if ($koneksi->query($updatePass) === true) {
							echo "<script>
                            alert('Password Berhasil Diubah!');
                            document.location.href = 'setting.php';
                          </script>";
						} else {
							echo "<script>
									alert('Terjadi kesalahan saat mengubah password!');
									document.location.href = 'setting.php';
								</script>";
						}
					} else {
						echo "<script>
								alert('Password Lama tidak sama!');
								document.location.href = 'setting.php';
							</script>";
					}
				} else {
					echo "<script>
							alert('Pengguna tidak ditemukan di tabel users!');
							document.location.href = 'setting.php';
						</script>";
				}
			} else {
				echo "<script>
						alert('Pengguna tidak ditemukan di tabel admin_mitra!');
						document.location.href = 'setting.php';
					</script>";
			}
		}
	?>
	<!-- PHP END -->

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
	<script>
        $(document).ready(function(){
            $('#checkpasslama').on('click', function(){
                var passwordField = $('#passwordlama');
                var passwordFieldType = passwordField.attr('type');
                if(passwordFieldType == 'password') {
                    passwordField.attr('type', 'text');
                } else {
                    passwordField.attr('type', 'password');
                }
            });
            $('#checkpassbaru').on('click', function(){
                var passwordField = $('#passwordbaru');
                var passwordFieldType = passwordField.attr('type');
                if(passwordFieldType == 'password') {
                    passwordField.attr('type', 'text');
                } else {
                    passwordField.attr('type', 'password');
                }
            });
        });
    </script>
    <!-- SCRIPT END -->

</body>
</html>