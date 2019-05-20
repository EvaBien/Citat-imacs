<?php
require_once 'MyPDO.citatimac.include.php';

/**
* Class typesSignalement
* Gère les types de signalements
*/
class typeSignalement {
    /**
    * @var int id du type de signalement
    */
    private $id;
    /**
    * @var string nom du type de signalement
    */
    private $nomTypeSignalement;

    /**
    * @param string $nomTypeSignalement Nom du type de signalement
    */
    public function __construct( $nomTypeSignalement){
                                  // Id auto
      $this->nomTypeSignalement=$nomTypeSignalement;
    }

    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  /**
  * @return json encode
  */
  function getTypeSignalement(){
    $formatted = array(
      'idTypeSignal'=> $this->id,
      'nomTypeSignal'=> $this->nomTypeSignalement
    );

    return json_encode($formatted);
  }

  /**
  * @return int
  */
  function getIdTypeSignalement(){
    return $this->id;
  }

  /**
  * @return string
  */
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
