<?php

require_once '../clases/token.class.php';

$_token = new token;
$fecha = date('Y-m-s H:i');
echo $_token->actualizarToken($fecha);
