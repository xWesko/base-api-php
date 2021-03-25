<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";

    class auth extends conexion {

        public function login( $json ){
            $_respuestas = new respuestas;
            $datos = json_decode( $json, true );

            if( !isset($datos["usuario"]) || !isset($datos["passwd"]) ) {
                // error con os campos
                return $_respuestas->error_400();
            } else {
                // todo esta bien
                $usuario  = $datos["usuario"];
                $password = $datos["passwd"];
                $password = parent::encriptar( $password );
                $datos = $this->obtenerDatosUsuario($usuario);
                if( $datos ) {
                    // verificar si la contraseña es correcta
                    if( $password == $datos[0]['passwd'] ) {

                        if($datos[0]['estado'] == 'Activo' ) {
                            //crear token
                            $verificar = $this->insertarToken($datos[0]['id_usuario']);
                            if($verificar){
                                //si se guardo
                                $result = $_respuestas->response;
                                $result["result"] = array(
                                    "token" => $verificar
                                );
                                return $result;
                            }else {
                                //error al guardar
                                return $_respuestas->error_500("Error interno, no hemos podido guardar");
                            }
                        } else {
                            // usuario inactivo
                            return $_respuestas->error_200("El usuario esta inactivo");
                        }

                    } else {
                        return $_respuestas->error_200("El password es invalido");
                    }

                } else {
                    // no existe el usuario
                    return $_respuestas->error_200("El usuario $usuario no existe");
                }
            }
        }

        private function obtenerDatosUsuario( $correo ) {
            $query = "SELECT id_usuario, passwd, estado FROM usuarios WHERE usuario = '$correo'";
            $datos = parent::obtenerDatos( $query );
            if( isset( $datos[0]["id_usuario"] ) ){
                return $datos;
            } else {
                return 0;
            }
        }

        private function insertarToken( $idUsuario ) {
            $val = true;
            $token = bin2hex( openssl_random_pseudo_bytes(16, $val) );
            $date = date("Y-m-d H:i");
            $estado = "Activo";
            $query = "INSERT INTO usuarios_token (id_usuario, token, estado, fecha) VALUES('$idUsuario', '$token', '$estado', '$date')";
            $verificar = parent::nonQuery($query);
            if( $verificar ) {
                return $token;
            }else {
                return 0;
            }


        }

    }