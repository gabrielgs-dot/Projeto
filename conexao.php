
<?php
$host = "dpg-d85qee3tqb8s73fe6lfg-a.ohio-postgres.render.com";
$db = "sistema_os_p356";
$user = "admin";
$pass = "JAyzptEp830EHSGyCoKIhMBwOih7LKOQ";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
 
