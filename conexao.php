<?php

$conn = pg_connect("
    host=dpg-d85qee3tqb8s73fe6lfg-a.ohio-postgres.render.com
    port=5432
    dbname=sistema_os_p356
    user=admin
    password=JAyzptEp830EHSGyCoKIhMBwOih7LKOQ
    sslmode=require
");

if (!$conn) {
    die("Erro na conexão");
}
?>
