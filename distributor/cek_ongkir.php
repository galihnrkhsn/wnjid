<?php
//     $data = [];
// 	$asal = 23;
// 	$id_kabupaten	= $_POST['kab_id'];
// 	$id_kecamatan	= $_POST['kec_id'];
// 	$berat 			= $_POST['berat'];
// 	// $kurir = $_POST['kurir'];

// 	$ekspedisinya	= $_POST["kurir"];
//     $result_explode = explode(separator: '|', string: $ekspedisinya);
//     $kurir 			= $result_explode[1];
//     echo $result_explode[0];
// 	$curl 			= curl_init();

// 	if ($result_explode[0] !== 'OM') {
// 	   	curl_setopt_array($curl, options: array(
// 			CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
// 			CURLOPT_RETURNTRANSFER => true,
// 			CURLOPT_ENCODING => "",
// 			CURLOPT_MAXREDIRS => 10,
// 			CURLOPT_TIMEOUT => 30,
// 			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 			CURLOPT_CUSTOMREQUEST => "POST",
// 			CURLOPT_POSTFIELDS => "origin=".$asal."&originType=city&destination=".$id_kecamatan."&destinationType=subdistrict&weight=".$berat."&courier=".$kurir."",
// 			//blm di ubah
// 			//origin=501&originType=city&destination=574&destinationType=subdistrict&weight=1700&courier=jne
// 			CURLOPT_HTTPHEADER => array(
// 				"content-type: application/x-www-form-urlencoded",
// 				"key:d1e0da7453eb42f959f5b3072a94a21c"
// 			),
//     	));
//     	$response = curl_exec($curl);
//     	$err = curl_error($curl);
//     	curl_close($curl);
//     	if ($err) {
//     	  echo "cURL Error #:" . $err;
//     	} else {
//     	  $data = json_decode($response, true);

//   // Debug jika struktur tidak sesuai
//   if (json_last_error() !== JSON_ERROR_NONE) {
//       echo "JSON Error: " . json_last_error_msg() . "<br>";
//       echo "Raw Response: <pre>" . htmlspecialchars($response) . "</pre>";
//       exit;
//   }

//   // Debug isi array hasil decode
//   echo "<pre>";
//   print_r($data);
//   echo "</pre>";
//     	}    
// 	}
// ?>
    
// 	<?php
// 	var_dump($data['rajaongkir'] === null);
// 	error_reporting(0);
// 	if($data['rajaongkir'] !== null){
// 	    for ($k=0; $k < count($data['rajaongkir']['results']); $k++) {
// 				 for ($l=0; $l < count($data['rajaongkir']['results'][$k]['costs']); $l++) {
// 			    echo "<option value='".$data['rajaongkir']['results'][$k]['costs'][$l]['service']."|".$data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']."'><b>".$data['rajaongkir']['results'][$k]['costs'][$l]['service']."</b> | Rp. ".$data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']." | Estimasi ".$data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['etd']." Hari</option>";
			
// 				 }
// 	 }
	 
// 	} else {
// 	    echo "<option>Layanan Ongkir Manual</option>";
// 	} 
?>
  

<?php
	$data 			= [];
	$asal 			= 434;
	list($id_kabupaten) = explode('|', $_POST['kab_id']);
	list($id_kecamatan) = explode('|', $_POST['kec_id']);
	$berat 			= $_POST['berat'];
	$ekspedisinya 	= $_POST["kurir"];

	$result_explode = explode('|', $ekspedisinya);
	$kurir 			= strtolower(trim($result_explode[0]));
	$ekspedisi 		= strtolower(trim($result_explode[1]));
	echo $result_explode[0];

	$api_keys = [
		"8yIERwHk01946583021350495uanfBzo",
		"u8kdNsWE9fb73dba289f96efE9vnoNTy"
	];

	// Jangan lanjut jika kurir manual
	if ($kurir !== 'om') {
		foreach ($api_keys as $apikey) {

			$curl = curl_init();
			// POST field ke API Komerce (rajaongkir v2)
			$post_fields = http_build_query([
				'origin' => $asal,
				'originType' => 'district',
				'destination' => $id_kecamatan,
				'destinationType' => 'district',
				'weight' => $berat,
				'courier' => $ekspedisi
			]);
	
			curl_setopt_array($curl, [
				CURLOPT_URL => "https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $post_fields,
				CURLOPT_HTTPHEADER => [
					"content-type: application/x-www-form-urlencoded",
					"key: " . $apikey
				],
			]);
	
			$response = curl_exec($curl);
			$err = curl_error($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);
	
			if ($err) {
				echo "cURL Error #:" . $err;
				continue;
			}

			$data = json_decode($response, true);

			if (json_last_error() !== JSON_ERROR_NONE) {
				echo "JSON Error: " . json_last_error_msg() . "<br>";
				echo "Raw Response: <pre>" . htmlspecialchars($response) . "</pre>";
				exit;
			}

			// jika berhasil ambil data ongkir
			if ($http_code == 200 && !empty($data['data'])) {
				break;
			}

			// jika limit 429, lanjut ke API key berikutnya
			if ($http_code == 429) {
				continue;
			}
		}
	}

	// Tampilkan hasil ongkir atau fallback
	if (!empty($data['data'])) {
		foreach ($data['data'] as $ongkir) {
			$service = $ongkir['service'];
			$value = $ongkir['cost'];
			$etd = $ongkir['etd'];

			echo "<option value='{$service}|{$value}'><b>{$service}</b> | Rp. {$value} | Estimasi {$etd}</option>";
		}
	} else {
		echo "<option value='OM|manual'>Layanan Ongkir Manual</option>";
	}
?>
