<?php
include 'koneksi.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daftar_mitra.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<thead>
        <tr>
            <th>Nama</th>
            <th>Nomor WhatsApp</th>
            <th>Mitra</th>
        </tr>
      </thead><tbody>";

// admin_mitra
$q1 = $koneksi->query("SELECT namamitra AS nama, whatsapp FROM admin_mitra");
while($row = $q1->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama']}</td>
            <td style=\"mso-number-format:'\@'\">". htmlspecialchars($row['whatsapp']) ."</td>
            <td>Distributor</td>
          </tr>";
}

// mitraagen
$q2 = $koneksi->query("SELECT namaagen AS nama, whatsapp FROM mitraagen");
while($row = $q2->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama']}</td>
            <td style=\"mso-number-format:'\@'\">". htmlspecialchars($row['whatsapp']) ."</td>
            <td>Agen</td>
          </tr>";
}

// mitrareseller
$q3 = $koneksi->query("SELECT namaagen AS nama, whatsapp FROM mitrareseller");
while($row = $q3->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama']}</td>
            <td style=\"mso-number-format:'\@'\">". htmlspecialchars($row['whatsapp']) ."</td>
            <td>Reseller</td>
          </tr>";
}

// mitramarketer
$q4 = $koneksi->query("SELECT namaagen AS nama, whatsapp FROM mitramarketer");
while($row = $q4->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama']}</td>
            <td style=\"mso-number-format:'\@'\">". htmlspecialchars($row['whatsapp']) ."</td>
            <td>Marketer</td>
          </tr>";
}

echo "</tbody></table>";
exit;
?>
