<?php
    require_once 'clases/conexion/conexion.php';

    $conexion = new Conexion();

    $query = "select * from pacientes";

    // print_r( $conexion->obtenerDatos( $query ) );


    //var_dump($conexion);

?>