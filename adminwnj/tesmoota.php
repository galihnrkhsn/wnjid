<?php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://app.moota.co/api/v1/profile');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer MJCwtSWXoyy8KhuDMSN7dSFKtu7IZeYETDaOuLEB1Qj3ANgcsg'
]);
$response = curl_exec($curl);

echo $response;
?>