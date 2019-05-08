<?php
require 'MyPDO.citatimac.include.php';

class typeSignalement {
    private $id;
    private $nomTypeSignalement;
    public function __construct( $nomTypeSignalement){
                                  // Id auto
      $this->nomTypeSignalement=$nomTypeSignalement;
    }

///////////////////////FUNCTIONS GETTER////////////////////////

  function getTypeSignalement(){
    $formatted = array(
      'idTypeSignal'=> $this->id,
      'nomTypeSignal'=> $this->nomTypeSignalement;
    );

    return json_encode($formatted);
  }

  function getIdTypeSignalement(){
    return $this->id;
  }

  function getNomTypeSignalement(){
    return $this->nomTypeSignalement;
  }

  ///////////////////////FUNCTIONS SETTER///////////////////////

  function setNomTypeSignalement($newName){
    $this->nomTypeSignalement=$newName;
  }

}

?>
