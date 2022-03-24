<?php

function randomSeq($length){
    $seq = '';
    $randomSeqSymb = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for($i=0; $i<$length; $i++) {
        $seq .= $randomSeqSymb{mt_rand(0, strlen($randomSeqSymb)-1)};
    }
    return $seq;
}
