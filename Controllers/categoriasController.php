<?php
   
   require_once('../models/DB.php');
   require_once('../models/categorie.php');
   require_once('../models/Response.php');

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
      
   // GET host/categorias
   if(empty($_GET)){
      if($_SERVER['REQUEST_METHOD'] == 'GET'){
         $query = $connection->prepare('SELECT * FROM categorias');
         $query->execute();
         $categorias = array();
     
         while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $categoria = new Categoria($row['id'], $row['nombre']);
     
            $categorias[] = $categoria->getArray();
         }
         
         $response = new Response();
         $response->setHttpStatusCode(200);
         $response->setSuccess(true);
         $response->setData($categorias);
         $response->send();
         exit();
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
   else{
      $response = new Response();
      $response->setHttpStatusCode(404);
      $response->setSuccess(false);
      $response->addMessage("No se encontro la ruta");
      $response->send();
      exit();  
     }
      

?>