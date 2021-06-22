<?php
    require_once "clases/respuestas.class.php";
    require_once "clases/pacientes.class.php";


    $_respuestas = new respuestas;
    $_pacientes = new pacientes;


    if( $_SERVER["REQUEST_METHOD"] == "GET" ) {
        
        if( isset( $_GET["page"] ) ) {
            $pagina = $_GET["page"];
            $listaPacientes = $_pacientes->listaPacientes($pagina);
            header('Content-Type: application/json');
            echo json_encode( $listaPacientes );
            http_response_code(200);
        } else if ( isset( $_GET["id"] ) ) {
            $pacienteId = $_GET["id"];
            $datosPaciente = $_pacientes->obtenerPaciente($pacienteId);
            header('Content-Type: application/json');
            echo json_encode( $datosPaciente );
            http_response_code(200);
        }


    } else if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
        
        //Recibimos los dato enviados
        $postBody = file_get_contents("php://input");
        
        //Enviamos al manejador
        $datosArray = $_pacientes->post($postBody);
        
        //devolvemos una respuesta
        header('Content-Type: application/json');
        if( isset( $datosArray["result"]["error_id"] ) ){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code( $responseCode );
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);

        

    } else if ( $_SERVER["REQUEST_METHOD"] == "PUT" ) {
        echo "hola put";
    } else if ( $_SERVER["REQUEST_METHOD"] == "DELETE" ) {
        echo "hola delete";
    } else {
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }

