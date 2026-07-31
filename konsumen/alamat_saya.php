<?php
    // Halaman ini sudah digabung ke profile.php (section Alamat Saya) supaya kelola alamat
    // tidak perlu buka halaman baru. Redirect ditinggal di sini untuk bookmark/link lama.
    header('Location: profile.php?section=alamat');
    exit;
