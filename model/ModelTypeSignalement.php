<?php
require 'MyPDO.citatimac.include.php';

class typeSignalement {
    private $id;
    private $nomTypeSignalement;
    public function __construct($typeSignalement, $nomTypeSignalement){
                                  // Id auto
      $this->typeSignalement=$typeSignalement;
      $this->nomTypeSignalement=$nomTypeSignalement ;
    }
}

///////////////////////FUNCTIONS GETTER////////////////////////

  function getTypeSignalement(){

  }
  function getNomTypeSignalement(){

  }

  ///////////////////////FUNCTIONS SETTER///////////////////////
  
  function setTypeSignalement(){

  }
  function setNomTypeSignalement(){

  }

?>
