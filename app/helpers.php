<?php
function convertToEnNumber($string) {
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    $num = range(0, 9);
    $englishNumbersOnly = str_replace($persian, $num, $string);

    return $englishNumbersOnly;
}

