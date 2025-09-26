<?php
function convert_data($value) {
    $value = isset($value) ? trim( $value ) :"";
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}