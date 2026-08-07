<?php

SESSION_START();

include 'koneksi.php';

if(!isset($_SESSION['administrator'])) {
    echo "<script>alert('Anda Harus Login Terlebih Dahulu'); </script>";
    echo "<script>location='login.php'</script>";
    header('location:login.php');
    exit();
}

?>

<!DOCTYPE html>
<HTML lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>WNJ.ID</title>
    
    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    
    <style type="text/css">
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        
        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        
        input:checked + .slider {
            background-color: #2196F3;
        }
        
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        
        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
        
        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }
        
        .slider.round:before {
            border-radius: 50%;
        }
    </style>

</head>
    
    <body id="page-top" class="sidebar-toggled">
    
        <!-- Bootstrap core JavaScript-->
        <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
        <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
        
        <!-- Core plugin JavaScript-->
        <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>
        
        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
        
        <!-- Page level plugins -->
        <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>
        
        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>
    
        <?php include "settingdatatables.php"; ?>
    
    </body>

</HTML>