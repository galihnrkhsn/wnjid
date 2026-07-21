<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require 'koneksi.php';
    $product_id = $_GET['product_id'];
    
    $q = $koneksi->query("
        SELECT mf.name
        FROM products p
        JOIN master_folder mf 
            ON mf.name = p.slug
        WHERE p.id = '$product_id'
    ");
   
    $d = $q->fetch_assoc();

    if(!$d || empty($d['name'])){
        echo "Tidak ada foto";
        exit;
    }

    $folder = $d['name'];
    $path   = "../distributor/foto/produk/".$folder."/";
    
    if(!is_dir($path)){
        echo "Tidak ada foto";
        exit;
    }

    $files  = glob($path."*.{jpg,jpeg,png,webp}",GLOB_BRACE);

    if(!$files){
        echo "Tidak ada foto";
        exit;
    }
    foreach($files as $file){
        $nama = basename($file);

        echo "<img 
            src='../distributor/foto/produk/$folder/$nama'
            class='foto-item'
            data-nama='$nama'
        >";
    }