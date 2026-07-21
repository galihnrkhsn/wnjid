<?php
	session_start();

	//check if product is already in the cart
	if(!in_array($_GET['id'], $_SESSION['cart'])){
		array_push($_SESSION['cart'], $_GET['id']);
		$_SESSION['message'] = 'Produk telah dimasukan ke keranjang';
	}
	else{
		$_SESSION['message'] = 'Produk sudah ada di keranjang';
	}

	header('location: view_cart.php');
?>