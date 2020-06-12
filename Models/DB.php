<?php
   class DB{
       private static $connection;

       public static function getConnection(){
           if(self::$connection === null){
            $dns = 'mysql:host=localhost;dbname=sitio_plazacell;charset=utf8';
            $username = 'root';
            $password = '';

            /*$dns = 'mysql:host=localhost;dbname=id13885296_lista_tareas;charset=utf8';
            $username = 'id13885296_root';
            $password = 'Hypervenom&100997';*/
    
            self::$connection = new PDO($dns, $username, $password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
           }

           return self::$connection;
       }
   }
?>