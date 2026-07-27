<?php
    session_start();
    require 'vendor/autoload.php';

    use Gregwar\Captcha\CaptchaBuilder;

    $builder = new CaptchaBuilder();
    $builder->build();

    $_SESSION['captcha_phrase'] = $builder->getPhrase();

    header('Content-Type: image/jpeg');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    $builder->output();
