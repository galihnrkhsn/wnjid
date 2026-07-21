<?php
	$asal = 23;
	$id_kabupaten = $_POST['kab_id'];
	$id_kecamatan= $_POST['kec_id'];
	// $kurir = $_POST['kurir'];
	$berat = $_POST['berat'];

	 $ekspedisinya=$_POST["kurir"];
    $result_explode = explode('|', $ekspedisinya);
    $kurir=$result_explode[1];

	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "origin=".$asal."&originType=city&destination=".$id_kecamatan."&destinationType=subdistrict&weight=".$berat."&courier=".$kurir."",
	//blm di ubah
	  //origin=501&originType=city&destination=574&destinationType=subdistrict&weight=1700&courier=jne
	  CURLOPT_HTTPHEADER => array(
	    "content-type: application/x-www-form-urlencoded",
	    "key:d1e0da7453eb42f959f5b3072a94a21c"
	  ),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  $data = json_decode($response, true);
	}
	?>

	<?php
	error_reporting(0);
	 for ($k=0; $k < count($data['rajaongkir']['results']); $k++) {
				 for ($l=0; $l < count($data['rajaongkir']['results'][$k]['costs']); $l++) {
			    echo "<option value='".$data['rajaongkir']['results'][$k]['costs'][$l]['service']."|".$data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']."'><b>".$data['rajaongkir']['results'][$k]['costs'][$l]['service']."</b> | Rp. ".$data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']." | Estimasi ".$data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['etd']." Hari</option>";
			
				 }
				 ?>	
									
								
											
<!--		 <div title="<?php echo strtoupper($data['rajaongkir']['results'][$k]['name']);?>" style="padding:10px">
			 <table class="table table-striped">
				 <tr>
					 <th>No.</th>
					 <th>Jenis Layanan</th>
					 <th>ETD</th>
					 <th>Tarif</th>
					 <th>Pilih</th>
				 </tr>
			
				 <tr>
					 <td><?php echo $l+1;?></td>
					 <td>
						 <div style="font:bold 16px Arial"><?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['service'];?></div>
						 <div style="font:normal 11px Arial"><?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['description'];?></div>
					 </td>
					 <td align="left"><?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['etd'];?> days</td>
					 <td align="left"><?php echo number_format($data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']);?></td>
					 <td><input type="hidden" value="<?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['service'];?>" name="layanan">
					     <input type="radio" name="ongkir" value="<?php echo number_format($data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']);?>"></td>
					 
				 </tr>

			 </table> -->
		 </div>
	 <?php
	 }
	 ?>
  