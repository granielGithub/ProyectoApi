<?php 
require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

// metodo que valora lo ingresado por body oheader
//y aplica las respuestas correspoindietnes  y la accion necesaria
//se instancia la clase
$_auth = new auth;
$_respuestas = new respuestas;

// METODO POST
//se le pregunta al servidor si el metodo igual
if($_SERVER['REQUEST_METHOD'] == "POST"){

    //recibe los datos
    //esta variable es igual aget  del contenido
    $postBody = file_get_contents("php://input");

    //enviamos los datos al manejador
    //de la respuesta en array con el metodo login con el metodo json (postbody)
    $datosArray = $_auth->login($postBody);

    //delvolvemos una respuesta
    //se devuelve el el tipo contenido en headers
    header('Content-Type: application/json');
    //si existe en datos array un array de result que se llama errorID
    if(isset($datosArray["result"]["error_id"])){
        //la variable RESPONCECODE sera igual al datoarray que esta arriba
        $responseCode = $datosArray["result"]["error_id"];
        //iguak se envia en estado de respuesta http la variable responcecode
        http_response_code($responseCode);
    }else{
        //si le mandas el 200
        http_response_code(200);
    }
    //si es array pero se manda un echo ya que se cambia a json se hace STRING
    echo json_encode($datosArray);


}else{
    //se devuelve el el tipo contenido en headers
    header('Content-Type: application/json');
    //esta variable sera igual a error 405
    //y se ara un echo json con estado de error
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);


    //si_todo esta mal te manda un error y en que linea
    // y sino te manda el token

}
