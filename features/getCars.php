<?php
$cars = [];

$carsFile = __DIR__ . '/../assets/data/cars.json';

if (file_exists($carsFile)) {
    $json = file_get_contents($carsFile);
    $data = json_decode($json, true);
    if (isset($data['cars'])) {
        $cars = $data['cars'];
    }
}
?>
