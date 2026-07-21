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
