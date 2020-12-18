<?php

function vardump($value) {
    echo '<pre>';
    echo var_dump($value);
    echo '</pre>';
    die;
}