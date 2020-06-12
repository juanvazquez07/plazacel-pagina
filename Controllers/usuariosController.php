<?php
  require_once('../Models/DB.php');
  require_once('../Models/Response.php');

try {
    $connection = DB::getConnection();
}
catch (PDOException $e){
    error_log("Error de conexión - " . $e);

    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("Error en conexión a Base de datos");
    $response->send();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Método no permitido");
    $response->send();
    exit();
}

if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Encabezado Content Type no es JSON");
    $response->send();
    exit();
}

$postData = file_get_contents('php://input');

if (!$json_data = json_decode($postData)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("El cuerpo de la solicitud no es un JSON válido");
    $response->send();
    exit();
}

if (!isset($json_data->nombre) || !isset($json_data->apellido) || !isset($json_data->password)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    (!isset($json_data->nombre) ? $response->addMessage("El nombre completo es obligatorio") : false);
    (!isset($json_data->apellido) ? $response->addMessage("El apellido es obligatorio") : false);
    (!isset($json_data->password) ? $response->addMessage("La contraseña es obligatoria") : false);
    $response->send();
    exit();
}

//Validación de longitud....

$nombre = trim($json_data->nombre);
$apellido = trim($json_data->apellido);
$tipo = trim($json_data->tipo);
$direccion = trim($json_data->direccion);
$fecha_nacimiento = trim($json_data->fecha_nacimiento);
$telefono = trim($json_data->telefono);
$email = trim($json_data->email);
$password = $json_data->password;

try {
  /* $query = $connection->prepare('SELECT id FROM usuarios WHERE apellido = :apellido');
    $query->bindParam(':apellido', $apellido, PDO::PARAM_STR);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount !== 0) {
        $response = new Response();
        $response->setHttpStatusCode(409);
        $response->setSuccess(false);
        $response->addMessage("El nombre de usuario ya existe");
        $response->send();
        exit();
    }*/

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
   // $query = $connection->prepare('INSERT INTO usuarios(nombre, apellido, password, tipo, direccion, fecha_nacimiento, telefono, email) VALUES(:nombre, :apellido, :password, :tipo, :direccion, :fecha_nacimiento, :telefono, :email)');
    $query = $connection->prepare('INSERT INTO usuarios(nombre, apellido, tipo, direccion, fecha_nacimiento, telefono, email, password) VALUES(:nombre, :apellido, :tipo, :direccion, :fecha_nacimiento, :telefono, :email, :password)');
    $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $query->bindParam(':apellido', $apellido, PDO::PARAM_STR);
    $query->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $query->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $query->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
    $query->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password_hash, PDO::PARAM_STR);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Error al crear usuario - inténtelo de nuevo");
        $response->send();
        exit();
    }

    $ultimoID = $connection->lastInsertId();

    $returnData = array();
    $returnData['id_usuario'] = $ultimoID;
    $returnData['nombre'] = $nombre;
    $returnData['apellido'] = $apellido;
    $returnData['tipo'] = $tipo;
    $returnData['direccion'] = $direccion;
    $returnData['fecha_nacimiento'] = $fecha_nacimiento;
    $returnData['telefono'] = $telefono;
    $returnData['email'] = $email;

    $response = new Response();
    $response->setHttpStatusCode(201);
    $response->setSuccess(true);
    $response->addMessage("Usuario creado");
    $response->setData($returnData);
    $response->send();
    exit();
}
catch(PDOException $e) {
    error_log('Error en BD - ' . $e);

    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("Error al crear usuario");
    $response->send();
    exit();
}
?>