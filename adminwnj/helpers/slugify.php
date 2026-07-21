<?php

function slugify($text)
{
    $text = strtolower($text);

    // ganti spasi dengan -
    $text = str_replace(' ', '-', $text);

    // hapus - di awal/akhir
    $text = trim($text, '-');

    return $text;
}