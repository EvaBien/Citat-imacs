<?php
require 'MyPDO.citatimac.include.php';

class typeSignalement {
    private $id;
    private $nomTypeSignalement;
    public function __construct( $nomTypeSignalement){
                                  // Id auto
      $this->nomTypeSignalement=$nomTypeSignalement ;
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

}

?>
