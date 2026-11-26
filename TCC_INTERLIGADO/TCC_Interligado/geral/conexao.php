<?php
$servername = 'localhost';
$ndtbase = 'megastore';
$username = 'root';
$password = '';
$con = mysqli_connect($servername, $username, $password, $ndtbase);
if (!$con) {
    error_log('Falha ao conectar ao banco Mega Store: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados da loja.');
}
mysqli_set_charset($con, 'utf8mb4');