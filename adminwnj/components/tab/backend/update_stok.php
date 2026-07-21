<?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
    
            $invoice    = $_POST['invoice'];
            $qty        = $_POST['qty'];
            $bundling   = $_POST['custom'];
    
            if (empty($invoice) || !is_array($qty) || !is_array($bundling)) {
                die("Data tidak lengkap: " . $koneksi->error);
            }
    
            $count      = count($bundling);
            $stmt       = $koneksi->prepare("UPDATE pomitra SET jumlah = ? WHERE invoice = ? AND TRIM(custom) = ?");
    
            if (!$stmt) {
                die("Prepare statement gagal: " . $koneksi->error);
            }
            
            if (!$stmt->bind_param("iss", $qty_item, $invoice, $bundling_item)) {
                die("Bind param gagal: " . $koneksi->error);
            }
    
            for ($i = 0; $i < $count; $i++) {
                $qty_item       = intval($qty[$i]);
                $bundling_item  = trim($bundling[$i]);
    
    
                if (!$stmt->execute()) {
                    die("Gagal Execute data: " . $koneksi->error);
                }
            }
    
            $stmt->close();
    
            echo "
                <script>
                    alert('Stok berhasil diubah')
                    location='ubahpo.php?invoice=$invoice'
                </script>
            ";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>