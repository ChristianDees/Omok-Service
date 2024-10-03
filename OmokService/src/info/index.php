<?php

// Authors: Christian Dees & Aitiana Mondragon

define('SIZE',15);
$strategies = array('Smart' => 'SmartStrategy', 'Random' => 'RandomStrategy');
// create a structure: a class or an array (of key-value pairs)
$info = new GameInfo(SIZE, array_keys($strategies));
echo json_encode($info); 

class GameInfo {
   public $size;
   public $strategies;
   function __construct($size, $strategies) {
      $this->size= $size;
      $this->strategies = $strategies;
}}
?>
