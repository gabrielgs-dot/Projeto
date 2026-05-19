 
<?php

$host = "dpg-d85qee3tqb8s73fe6lfg-a.ohio-postgres.render.com";
$user = "admin";
$pass = "JAyzptEp830EHSGyCoKIhMBwOih7LKOQ";
$db   = "sistema_os_p356";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

?>
