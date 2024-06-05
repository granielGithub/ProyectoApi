<?php
// Allow from any origin
header("Access-Control-Allow-Origin: *");

// Allow methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow headers
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Resto de tu código PHP...

require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';
//se instancian para poder usar el objeto de la clase respuestas
$_respuestas = new respuestas;
$_pacientes = new pacientes;

//se solicita una respuesta aplica los metodos de pacientes y verifica algunos terminos
if($_SERVER['REQUEST_METHOD'] == "GET"){
    //si existe pagina va haciendo le ecuasion y va mostrando cada pagina
    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        //sera la variable que guarde la lista
        $listaPacientes = $_pacientes->listaPacientes($pagina);
        //que tipo de dato de aplicasion json
        header("Content-Type: application/json");
        //que haga el eco de la lista
        echo json_encode($listaPacientes);
        //simepre mostrara los pasientes
        http_response_code(200);
        //si existe una variable en el get que sea ID
    }else if(isset($_GET['id'])){
        //paciente  con el id igual al id
        $pacienteid = $_GET['id'];
        //los datos del paciente sera igual a la clase que optiene sus datos pacienteID
        $datosPaciente = $_pacientes->obtenerPaciente($pacienteid);
        //tipo de json
        header("Content-Type: application/json");
        //se imprime los datos
        echo json_encode($datosPaciente);
        http_response_code(200);
    }
    // Si la URL es incorrecta o está incompleta
    else {
        // Se crea un mensaje de error
        $error = array("mensaje" => "URL incorrecta o incompleta");
        // Se establece el código de respuesta HTTP 400 (Bad Request)
        http_response_code(400);
        // Se devuelve el mensaje de error en formato JSON
        echo json_encode($error);
    }

    //guarda
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_pacientes->post($postBody);
    // Establece la cabecera de la respuesta HTTP como 'Content-Type: application/json'
// Esto indica que el contenido de la respuesta será en formato JSONa
    header('Content-Type: application/json');

// Verifica si existe la clave 'error_id' dentro de la estructura $datosArray["result"]
// La función isset() comprueba si una variable está definida y no es nula.
    if(isset($datosArray["result"]["error_id"])){
        // Si 'error_id' está presente, asigna su valor a la variable $responseCode
        $responseCode = $datosArray["result"]["error_id"];
        // Establece el código de respuesta HTTP con el valor de $responseCode
        http_response_code($responseCode);
    }else{
        // Si 'error_id' no está presente, establece el código de respueAsta HTTP a 200 (OK)
        http_response_code(200);
    }
    // Convierte el array $datosArray a formato JSON y lo imprime
    // Esto envía la representación JSON de $datosArray como parte de la respuesta HTTP
    echo json_encode($datosArray);




    //--------------------valida los METODOS----------------------------------------------------------------------------
// Si el método de la solicitud HTTP es PUT, se ejecuta este bloque de código
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
    // Leemos el cuerpo de la solicitud PUT.
    // file_get_contents("php://input") obtiene el contenido completo del cuerpo de la solicitud
    $postBody = file_get_contents("php://input");

    // Llamamos al método put del objeto $_pacientes con los datos del cuerpo de la solicitud.
    // Se asume que $_pacientes es una instancia de una clase que maneja operaciones relacionadas con pacientes
    // $datosArray contiene la respuesta de esta operación
    $datosArray = $_pacientes->put($postBody);

    // Establecemos la cabecera de la respuesta HTTP como 'Content-Type: application/json'
    // Esto indica que el contenido de la respuesta será en formato JSON
    header('Content-Type: application/json');

    // Verificamos si existe la clave 'error_id' dentro de la estructura $datosArray["result"]
    // La función isset() comprueba si una variable está definida y no es nula
    if(isset($datosArray["result"]["error_id"])){
        // Si 'error_id' está presente, asignamos su valor a la variable $responseCode
        $responseCode = $datosArray["result"]["error_id"];
        // Establecemos el código de respuesta HTTP con el valor de $responseCode
        http_response_code($responseCode);
    }else{
        // Si 'error_id' no está presente, establecemos el código de respuesta HTTP a 200 (OK
        http_response_code(200);
    }
    // Convertimos el array $datosArray a formato JSON y lo imprimimos
    // Esto envía la representación JSON de $datosArray como parte de la respuesta HTTP
    echo json_encode($datosArray);






//eliminar mediante  header al ingresar no tiene problemas
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    // esta variable optiene todos los header ingresados
    $headers = getallheaders();
    //si si el token y el id del paciente existen en headers entocnes
    if(isset($headers["token"]) && isset($headers["idPaciente"])){
        //recibimos los datos enviados por el header
        //se crea este array send y se crea el token y el id
        //estos tendran el valor cada uno del header correspondiente
        $send = [
            "token" => $headers["token"],
            "idPaciente" =>$headers["idPaciente"]
        ];
        //queremos que sea un json con postbody igual a jsonENCODE y se le pasa el areglo send
        //convertira los datos de send PHP a una representación en formato JSON
        $postBody = json_encode($send);
    }else{
        //entonces recibimos los datos enviados
        $postBody = file_get_contents("php://input");
    }

    //enviamos datos al manejador
    $datosArray = $_pacientes->delete($postBody);
    //delvovemos una respuesta
    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
?>