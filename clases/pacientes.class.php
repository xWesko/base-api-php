<?php
    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";

    class pacientes extends conexion {
        
        private $table = "pacientes";
        private $paciente_id = "";
        private $ine = "";
        private $nombre = "";
        private $direccion = "";
        private $cp = "";
        private $telefono = "";
        private $genero = "";
        private $fecha_nacimiento = "0000-00-00";
        private $email = "";
        private $token = "";
        // b234152da448ec46c59ac2f1103d53dc

        public function listaPacientes( $pagina = 1 ){

            $inicio = 0;
            $cantidad = 100;
            if( $pagina > 1 ){
                $inicio = ( $cantidad * ( $pagina - 1 )) + 1;
                $cantidad = $cantidad * $pagina;
            }
            $query = "SELECT paciente_id, nombre, direccion, cp, telefono, genero, email FROM ". $this->table . " limit $inicio, $cantidad";
            $datos = parent::obtenerDatos($query);
            return $datos;

        }

        public function obtenerPaciente( $id ) {
            $query = "SELECT * FROM " . $this->table . " WHERE paciente_id = '$id'";
            return parent::obtenerDatos($query);
        }

        public function post( $json ){
            
            $_respuestas = new respuestas;
            $datos = json_decode( $json, true );

            //Verificamos token
            if( !isset($datos['token']) ){
                return $_respuestas->error_401();
            } else {
                $this->token = $datos['token'];
                $arrayToken = $this->buscarToken();

                if( $arrayToken ) {
                    if( !isset($datos['nombre']) || !isset($datos['ine']) || !isset($datos['email']) ) {
                        return $_respuestas->error_400();
                    } else {

                        $this->nombre = $datos['nombre'];
                        $this->ine = $datos['ine'];
                        $this->email = $datos['email'];
                        if( isset($datos['telefono']) )         { $this->telefono = $datos['telefono'];  }
                        if( isset($datos['direccion']) )        { $this->direccion = $datos['direccion'];  }
                        if( isset($datos['cp']) )               { $this->cp = $datos['cp'];  }
                        if( isset($datos['genero']) )           { $this->genero = $datos['genero'];  }
                        if( isset($datos['fecha_nacimiento']) ) { $this->fecha_nacimiento = $datos['fecha_nacimiento'];  }

                        $resp = $this->insertarPaciente();

                        if( $resp ) {
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "paciente_id" =>  $resp
                            );
                            return $respuesta;
                        } else {
                                return $_respuestas->error_500();
                        }
                    }
                } else {
                    return $_respuestas->error_401("El token que envio es invalido o ha caducado");
                }
            }

            

        }

        private function insertarPaciente() {
            $query = "INSERT INTO " . $this->table . " (ine, nombre, direccion, cp, telefono, genero, fecha_nacimiento, email)
            values
            ('".$this->ine."', '".$this->nombre."', '".$this->direccion."', '".$this->cp."',  '".$this->telefono."', '".$this->genero."', '".$this->fecha_nacimiento."', '".$this->email."')";
            
           $resp = parent::nonQueryId($query);

            if( $resp ){
               return $resp;
            } else {
               return 0;
            }
        }

        public function put( $json ){

            $_respuestas = new respuestas;
            $datos = json_decode( $json, true );

            //Verificamos token
            if( !isset($datos['token']) ){
                return $_respuestas->error_401();
            } else {
                $this->token = $datos['token'];
                $arrayToken = $this->buscarToken(); 
                if( $arrayToken ) {

                    if( !isset($datos['paciente_id']) ) {
                        return $_respuestas->error_400();
                    } else {
                        $this->paciente_id = $datos['paciente_id'];
                        if( isset($datos['nombre']) )           { $this->nombre = $datos['nombre'];  }
                        if( isset($datos['ine']) )              { $this->ine = $datos['ine'];  }
                        if( isset($datos['email']) )            { $this->email = $datos['email'];  }
                        if( isset($datos['telefono']) )         { $this->telefono = $datos['telefono'];  }
                        if( isset($datos['direccion']) )        { $this->direccion = $datos['direccion'];  }
                        if( isset($datos['cp']) )               { $this->cp = $datos['cp'];  }
                        if( isset($datos['genero']) )           { $this->genero = $datos['genero'];  }
                        if( isset($datos['fecha_nacimiento']) ) { $this->fecha_nacimiento = $datos['fecha_nacimiento'];  }
        
                        $resp = $this->modificarPaciente();            
                        if( $resp ) {
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "paciente_id" =>  $this->paciente_id
                            );
                            return $respuesta;
                        } else {
                            return $_respuestas->error_500();
                        }
                    }

                } else {
                    return $_respuestas->error_401("El token que envio es invalido o ha caducado");
                }
            }
           

        }

        private function modificarPaciente() {
            $query = "UPDATE " . $this->table . " SET nombre='".$this->nombre."', ine='".$this->ine."', email='".$this->email."', telefono='".$this->telefono."', direccion='".$this->direccion."', cp='".$this->cp."', genero='".$this->genero."', fecha_nacimiento='".$this->fecha_nacimiento."' WHERE paciente_id='".$this->paciente_id."' ";
    
            $resp = parent::nonQuery($query);

            if( $resp >= 1 ){
               return $resp;
            } else {
               return 0;
            }
        }

        public function delete( $json ) {

            $_respuestas = new respuestas;
            $datos = json_decode( $json, true );

            //Verificamos token
            if( !isset($datos['token']) ){
                return $_respuestas->error_401();
            } else {
                $this->token = $datos['token'];
                $arrayToken = $this->buscarToken(); 
                if( $arrayToken ) {

                    if( !isset($datos['paciente_id']) ) {
                        return $_respuestas->error_400();
                    } else {
                        $this->paciente_id = $datos['paciente_id'];
        
                        $resp = $this->eliminarPaciente();            
                        if( $resp ) {
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "paciente_id" =>  $this->paciente_id
                            );
                            return $respuesta;
                        } else {
                            return $_respuestas->error_500();
                        }
                    }

                } else {
                    return $_respuestas->error_401("El token que envio es invalido o ha caducado");
                }
            }            
        }

        private function eliminarPaciente() {
            $query = "DELETE FROM ". $this->table . " WHERE paciente_id =  '" .$this->paciente_id."'";
            $resp = parent::nonQuery($query);

            if( $resp >= 1 ){
                return $resp;
            } else {
                return 0;
            }
        }

        private function buscarToken(){
            $query = "SELECT id_token, id_usuario, estado FROM usuarios_token WHERE token = '" . $this->token . "' AND estado =  'Activo' ";
            $resp = parent::obtenerDatos($query);
            if( $resp ){
                return $resp;
            } else {
                return 0;
            }
        }

        private function actualizarToken( $id_token ){
            $date = date("Y-m-d H:i");
            $query = "UPDATE usuarios_token SET fecha = '$date' WHERE id_token = '$id_token' ";
            $resp = parent::nonQuery($query);

            if( $resp >= 1 ){
                return $resp;
            }else {
                return 0;
            }
        }


    }