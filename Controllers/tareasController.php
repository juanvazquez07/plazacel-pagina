<?php
   require_once('../Models/DB.php');
   require_once('../Models/tarea.php');
   require_once('../Models/Response.php');


  try{
    $connection = DB::getConnection();
  }
  catch (PDOException $e){
      error_log("Error de conexion - " . $e);
 
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage("Database connection error");
      $response->send();
      exit();
  }

  //Verificación token


/*
  if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) < 1) {
  $response = new Response();
  $response->setHttpStatusCode(401);
  $response->setSuccess(false);
  $response->addMessage("No se encontró el token de acceso");
  $response->send();
  exit();
}

$accesstoken = $_SERVER['HTTP_AUTHORIZATION']; 


try {
  $query = $connection->prepare('SELECT id_usuario, caducidad_token_acceso activo FROM sesiones, usuarios WHERE sesiones.id_usuario = usuarios.id AND token_acceso = :token_acceso');
  $query->bindParam(':token_acceso', $accesstoken, PDO::PARAM_STR);
  $query->execute();
  

  $rowCount = $query->rowCount();
 
  
  if ($rowCount === 0) {
      $response = new Response();
      $response->setHttpStatusCode(401);
      $response->setSuccess(false);
      $response->addMessage("Token de acceso no válido");
      $response->send();
      exit();
  }

  $row = $query->fetch(PDO::FETCH_ASSOC);
  $consulta_idUsuario = $row['id_usuario'];
  
  //$consulta_cadTokenAcceso = $row['caducidad_token_acceso'];

  $consulta_activo = $row['activo'];
 
  /*
  if($consulta_activo !== 'SI') {
      $response = new Response();
      $response->setHttpStatusCode(401);
      $response->setSuccess(false);
      $response->addMessage("Cuenta de usuario no activa");
      $response->send();
      exit();
  }*/


  /*if (strtotime($consulta_cadTokenAcceso) > time()) {
      $response = new Response();
      $response->setHttpStatusCode(401);
      $response->setSuccess(false);
      $response->addMessage("Token de acceso ha caducado");
      $response->send();
      exit();
  }
} 
catch (PDOException $e) {
  error_log('Error en DB - ' . $e);

  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage("Error al autenticar usuario");
  $response->send();
  exit();
}*/
//


  if(array_key_exists("categoria_id", $_GET)){

     if($_SERVER['REQUEST_METHOD'] == 'GET'){

        $categoria_id = $_GET['categoria_id'];
        
        if($categoria_id == '' || !is_numeric($categoria_id)){
          $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("El id de categoria no puede estar vacio y debe ser numerico");
            $response->send();
            exit();
        }
        try{
            $query = $connection->prepare('SELECT id, nombre, descripcion, categoria_id, precio, imagen, stock FROM productos WHERE categoria_id = :categoria_id');
            $query->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            $productos = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $tarea = new Tarea($row['id'], $row['nombre'], $row['descripcion'], $row['precio'], $row['imagen'], $row['stock'], $row['categoria_id']);

                $productos[] = $tarea->getArray();
            }

            $returnData = array();
            $returnData['total_registros'] = $rowCount;
            $returnData['productos'] = $productos;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setToCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        }
        catch(TareaException $e){
          $response = new Response();
          $response->setHttpStatusCode(500);
          $response->setSuccess(false);
          $response->addMessage($e->getMessage());
          $response->send();
          exit();
        }
        catch(PDOException $e){
          error_log("Error den la BD - " . $e);
          
          $response = new Response();
          $response->setHttpStatusCode(500);
          $response->setSuccess(false);
          $response->addMessage("Error en consulta de productos");
          $response->send();
          exit();
        }
    }
    else{
      $response = new Response();
      $response->setHttpStatusCode(405);
      $response->setSuccess(false);
      $response->addMessage("Metodo no permitido");
      $response->send();
      exit();  
    }
  }
  else if(array_key_exists("id_tarea", $_GET)){
    $id_tarea = $_GET['id_tarea'];

    if ($id_tarea == '' || !is_numeric($id_tarea)) {
      $response = new Response();
      $response->setHttpStatusCode(400);
      $response->setSuccess(false);
      $response->addMessage("El id de la tarea no puede estar vacio y debe ser numerico");
      $response->send();
      exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'PATCH'){
      try {
        if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
          $response = new Response();
          $response->setHttpStatusCode(400);
          $response->setSuccess(false);
          $response->addMessage('Encabezado "Content type" no es JSON');
          $response->send();
          exit();
        }

        $postData = file_get_contents('php://input');

        if (!$json_data = json_decode($postData)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('El cuerpo de la solicitud no es un JSON valido');
            $response->send();
            exit();
        }

         $actualiza_nombre = false;
         $actualiza_descripcion = false;
         $actualiza_fechaLimite = false;
         $actualiza_completada = false;
         $actualiza_categoriaId = false;
         
         $campos_query = "";

         if (isset($json_data->nombre)) {
           $actualiza_nombre = true;
           $campos_query .= "nombre = :nombre, ";
         }

         if (isset($json_data->descripcion)) {
          $actualiza_descripcion = true;
          $campos_query .= "descripcion = :descripcion, ";
        }

        if (isset($json_data->precio)) {
          $actualiza_precio = true;
          $campos_query .= "precio = :precio, ";
        }

        if (isset($json_data->imagen)) {
          $actualiza_imagen = true;
          $campos_query .= "imagen = :imagen, ";
        }

        if (isset($json_data->stock)) {
          $actualiza_stock = true;
          $campos_query .= "stock = :stock, ";
        }


        if (isset($json_data->catgoria_id)) {
          $actualiza_categoria_id = true;
          $campos_query .= "categoria_id = :categoria_id, ";
        }

        $campos_query = rtrim($campos_query, ", ");

        if ($actualiza_nombre === false && $actualiza_descripcion === false && $actualiza_precio === false && $actualiza_imagen === false && $actualiza_stock === false && $actualiza_categoriaId === false) {
          $response = new Response();
          $response->setHttpStatusCode(400);
          $response->setSuccess(false);
          $response->addMessage("No hay campos para actualizar");
          $response->send();
          exit();  
        }

        $query = $connection->prepare('SELECT id, nombre, descripcion, categoria_id, precio, imagen, stock FROM productos WHERE id = :id');
        $query->bindParam(':id', $id_tarea, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();

        if($rowCount === 0){
          $response = new Response();
          $response->setHttpStatusCode(404);
          $response->setSuccess(false);
          $response->addMessage("No se encontro la tarea");
          $response->send();
          exit();  
        }

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
          $tarea = new Tarea($row['id'], $row['nombre'], $row['descripcion'], $row['precio'], $row['imagen'], $row['stock'], $row['categoria_id']);
        }

        $cadena_query = 'UPDATE productos SET ' . $campos_query . ' WHERE id = :id';
        $query = $connection->prepare($cadena_query);

        if ($actualiza_nombre === true) {
          $tarea->setnombre($json_data->nombre);
          $up_nombre = $tarea->getnombre();
          $query->bindParam(':nombre', $up_nombre, PDO::PARAM_STR);
        }

        if ($actualiza_descripcion === true) {
          $tarea->setDescripcion($json_data->descripcion);
          $up_descripcion = $tarea->getDescripcion();
          $query->bindParam(':descripcion', $up_descripcion, PDO::PARAM_STR);
        }

        if ($actualiza_precio === true) {
          $tarea->setPrecio($json_data->precio);
          $up_precio = $tarea->getPrecio();
          $query->bindParam(':precio', $up_precio, PDO::PARAM_STR);
        }

        if ($actualiza_imagen === true) {
          $tarea->setImagen($json_data->imagen);
          $up_imagen = $tarea->getImagen();
          $query->bindParam(':imagen', $up_imagen, PDO::PARAM_STR);
        }

        if ($actualiza_stock === true) {
          $tarea->setStock($json_data->stock);
          $up_stock = $tarea->getStock();
          $query->bindParam(':stock', $up_stock, PDO::PARAM_STR);
        }

        if ($actualiza_categoriaId === true) {
          $tarea->setCategoriaID($json_data->categoria_id);
          $up_categoriaID = $tarea->getCategoriaID();
          $query->bindParam(':categoria_id', $up_categoriaID, PDO::PARAM_STR);
        }

        $query->bindParam(':id', $id_tarea, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();

        if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Error al actualizar la tarea");
            $response->send();
            exit();
        }

        $query = $connection->prepare('SELECT id, nombre, descripcion, categoria_id, precio, imagen, stock FROM productos WHERE id = :id');
        $query->bindParam(':id', $id_tarea, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();

        if($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("No se encontró la tarea después de actulizar");
            $response->send();
            exit();
        }

        $productos = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $tarea = new Tarea($row['id'], $row['nombre'], $row['descripcion'], $row['precio'], $row['imagen'], $row['stock'], $row['categoria_id']);

            $productos[] = $tarea->getArray();
        }

        $returnData = array();
        $returnData['total_registros'] = $rowCount;
        $returnData['productos'] = $productos;

        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->addMessage("Tarea actualizada");
        $response->setData($returnData);
        $response->send();
        exit();
        

      } catch (TareaException $e) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($e->getMessage());
        $response->send();
        exit(); 
      }
      catch(PDOException $e){
        error_log("Error en BD - " . $e);

        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Error al actualizar la tarea");
        $response->send();
        exit(); 
      }
    
    }
    else{
      $response = new Response();
      $response->setHttpStatusCode(405);
      $response->setSuccess(false);
      $response->addMessage("Metodo no permitido");
      $response->send();
      exit();  
    }

  }
  elseif(empty($_GET)){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        try{
            $query = $connection->prepare('SELECT id, nombre, descripcion, categoria_id, precio, imagen, stock FROM productos');
            $query->execute();
            
            $rowCount = $query->rowCount();

            $productos = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC))
            {
              $tarea = new Tarea($row['id'], $row['nombre'], $row['descripcion'], $row['precio'], $row['imagen'], $row['stock'], $row['categoria_id']);

              $productos[] = $tarea->getArray();
            }

            $returnData = array();
            $returnData['total_registros'] = $rowCount;
            $returnData['productos'] = $productos;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setToCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        }
        catch(TareaException $e){
          $response = new Response();
          $response->setHttpStatusCode(500);
          $response->setSuccess(false);
          $response->addMessage($e->getMessage());
          $response->send();
          exit();
        }
        catch(PDOException $e){
          error_log("Error den la BD - " . $e);
          $response = new Response();
          $response->setHttpStatusCode(500);
          $response->setSuccess(false);
          $response->addMessage("Error en consulta de productos");
          $response->send();
          exit();
        }
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
       try {
         if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
              $response = new Response();
              $response->setHttpStatusCode(400);
              $response->setSuccess(false);
              $response->addMessage('Encabezdo"Content type" no es JSON');
              $response->send();
              exit();
         }

          $postData = file_get_contents('php://input');

          if (!$json_data = json_decode($postData)) {
              $response = new Response();
              $response->setHttpStatusCode(400);
              $response->setSuccess(false);
              $response->addMessage('El cuerpo de la solicitud no es un JSON valido');
              $response->send();
              exit();
          }
           //|| !isset($json_data->categoria_id)
          if (!isset($json_data->nombre)) {
              $response = new Response();
              $response->setHttpStatusCode(400);
              $response->setSuccess(false);
              (!isset($json_data->nombre) ? $response->addMessage('El nombre es obligatorio') : false);
              (!isset($json_data->categoria_id) ? $response->addMessage('La categoria es obligatoria') : false);
              $response->send();
              exit();
          }

          

          $tarea = new Tarea(
            null,
            $json_data->nombre,
            (isset($json_data->descripcion) ? $json_data->descripcion: null),
            //$json_data->categoria_id,
            $json_data->precio,
            $json_data->imagen,
            $json_data->stock
          );

          $nombre = $tarea->getnombre();
          $descripcion = $tarea->getDescripcion();
          $precio = $tarea->getPrecio();
          $imagen = $tarea->getImagen();
          $stock = $tarea->getStock();
          //$categoria_id = $tarea->getCategoriaID();
          
          $query = $connection->prepare('INSERT INTO productos (nombre, descripcion, precio, imagen, stock, usuario_id) VALUES (:nombre, :descripcion, :precio, :imagen, :stock, :usuario_id)');
          
          $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
          $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
          $query->bindParam(':precio', $precio, PDO::PARAM_STR);
          $query->bindParam(':imagen', $imagen, PDO::PARAM_STR);
          $query->bindParam(':stock', $stock, PDO::PARAM_STR);
          $query->bindParam(':usuario_id', $consulta_idUsuario, PDO::PARAM_STR);
          //$query->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
          $query->execute();
          
          $rowCount = $query->rowCount();

          if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Error al cargar la tarea");
            $response->send();
            exit();
          }

          $ultimo_ID = $connection->lastInsertId();

          $query = $connection->prepare('SELECT id, nombre, descripcion, categoria_id, precio, imagen, stock FROM productos WHERE id = :id AND usuario_id = :usuario_id');
          $query->bindParam(':id', $ultimo_ID, PDO::PARAM_INT);
          $query->bindParam(':usuario_id', $consulta_idUsuario, PDO::PARAM_STR);
          $query->execute();

          $rowCount = $query->rowCount();

          if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Error al obtener tarea despues de crearla");
            $response->send();
            exit();
          }

          $productos = array();

          while($row = $query->fetch(PDO::FETCH_ASSOC))
          {
            $tarea = new Tarea($row['id'], $row['nombre'], $row['descripcion'], $row['precio'], $row['imagen'], $row['stock'], $row['categoria_id']);

            $productos[] = $tarea->getArray();
          }

          $returnData = array();
          $returnData['total_registros'] = $rowCount;
          $returnData['productos'] = $productos;

          $response = new Response();
          $response->setHttpStatusCode(201);
          $response->setSuccess(true);
          $response->addMessage("Producto creado");
          $response->setData($returnData);
          $response->send();
          exit();

       }
       catch (TareaException $e) {
         $response = new Response();
         $response->setHttpStatusCode(500);
         $response->setSuccess(false);
         $response->addMessage($e->getMessage());
         $response->send();
         
         exit();
       }
       catch (PDOException $e){
          error_log("Error den la BD - " . $e);
         
          $response = new Response();
          $response->setHttpStatusCode(500);
          $response->setSuccess(false);
          $response->addMessage("Error en creacion de productos");
          $response->send();
          exit();
       }
    }
    else{
      $response = new Response();
      $response->setHttpStatusCode(405);
      $response->setSuccess(false);
      $response->addMessage("Metodo no permitido");
      $response->send();
      exit();  
    }
}


?>

