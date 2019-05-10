<?php
require 'MyPDO.citatimac.include.php';

class typeSignalement {
    private $id;
    private $nomTypeSignalement;
    public function __construct( $nomTypeSignalement){
                                  // Id auto
      $this->nomTypeSignalement=$nomTypeSignalement;
    }

    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

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

  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////

  function setNomTypeSignalement($newName){
    $this->nomTypeSignalement=$newName;
  }

}

?>
