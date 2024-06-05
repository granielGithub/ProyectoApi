<?php
// Incluye los archivos necesarios
require_once "clases/respuestas.class.php";
require_once "clases/usuario.class.php";
require_once "clases/conexion/conexion.php";

// Crea instancias de las clases necesarias
$_respuestas = new respuestas; // Instancia de la clase de respuestas
$_usuario = new CrearUsuario; // Instancia de la clase para crear usuarios

// Verifica si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD']== 'POST'){
    // Recibe los datos enviados en formato JSON
    $postBody = file_get_contents("php://input");
    // Envía los datos al método para recibir un usuario de la clase CrearUsuario
    $resp= $_usuario->recibirUsuario($postBody);


    // Devuelve una respuesta en formato JSON
    header('Content-Type: application/json');
    $datosArray = $resp; // Asigna la respuesta a una variable
    // Verifica si hay un código de error en la respuesta y establece el código de respuesta HTTP correspondiente
    if (isset($datosArray["result"]["error_id"] )){
        $responseCode = $datosArray ["result"]["error_id"];
        http_response_code($responseCode); // Establece el código de respuesta HTTP
    }else{
        http_response_code(200); // Establece el código de respuesta HTTP 200 (OK)
    }
    echo json_encode($datosArray);
}
    // Devuelve la respuesta en formato JSON

