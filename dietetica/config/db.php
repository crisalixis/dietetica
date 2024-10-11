<?php

    $host = 'localhost';
    $user = 'root'; 
    $pass = '';
    $db = 'factura-dietetica';

    $conexion = @mysqli_connect($host, $user, $pass, $db);
    
    if(!$conexion){
        echo "Error de conexion";
    }

?>