<?php

class TareaException extends Exception {}

   class Tarea {
    public $_id;
    public $_nombre;
    public $_descripcion;
    public $_precio;
    public $_imagen;
    public $_stock;
    public $_categoria_id;

    public function __construct($id, $nombre, $descripcion, $precio, $imagen, $stock, $categoria_id){
           
        $this->setID($id);
        $this->setnombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setPrecio($precio);
        $this->setImagen($imagen);
        $this->setStock($stock);
        $this->setCategoriaID($categoria_id);
    }

    public function getID(){
           return $this->_id;
    }

    public function getnombre(){
        return $this->_nombre;
    }

    public function getDescripcion(){
        return $this->_descripcion;
    }

    public function getFechaLimite(){
           return $this->_fecha_limite;
    }

    public function getCompletada(){
        return $this->_completada;
    }

    public function getCategoriaID(){
        return $this->_categoria_id;
    }

    public function getPrecio(){
       return $this->_precio;
    }

    public function getImagen(){
       return $this->_imagen;
    }

    public function getStock(){
        return $this->_stock;
    }

    public function setID($id){
           if($id !== null && (!is_numeric($id) || $id <= 0 || $id >= 2147483647 || $this->_id !== null)){
               throw new TareaException("Error en ID de tarea");
           }
           $this->_id = $id;
    }

    public function setnombre($nombre){
       if($nombre === null || strlen($nombre) > 50 || strlen($nombre) < 1){
              throw new TareaException("Error en nombre de tarea");
       }
       $this->_nombre = $nombre;
    }

    public function setDescripcion($descripcion){
       if($descripcion !== null && strlen($descripcion) > 150){
              throw new TareaException("Error en descripcion de tarea");
       }
       $this->_descripcion = $descripcion;;
    }

    public function setCategoriaID($categoria_id){
       if(!is_numeric($categoria_id) || $categoria_id <= 0 || $categoria_id >= 2147483647){
              throw new TareaException("Error de ID de categoria de tarea");
       }
       $this->_categoria_id = $categoria_id;
    }

    public function setPrecio($precio){
       if($precio === null){
              throw new TareaException("Error en el precio de el producto");
       }
       $this->_precio = $precio;
    }

    public function setImagen($imagen){
       if($imagen === null){
              throw new TareaException("Error en la imagen del producto");
       }
       
       $this->_imagen = $imagen;
    }

    public function setStock($stock){
       if($stock === null){
              throw new TareaException("Error en stock de producto");
       }
        $this->_stock = $stock;
    }

    public function getArray(){
           $tarea = array();

           $tarea['ide'] = $this->getID();
           $tarea['nombre'] = $this->getnombre();
           $tarea['descripcion'] = $this->getDescripcion();
           $tarea['precio'] = $this->getPrecio();
           $tarea['imagen'] = $this->getImagen();
           $tarea['stock'] = $this->getStock();
           $tarea['categoria_id'] = $this->getCategoriaID();

           return $tarea;
    }
   }
?>