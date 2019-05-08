<?php
require 'MyPDO.citatimac.include.php';

class typesAuteur {
    private $id;
    private $nomTypeAuteur;
    private $likesTypeAuteur;
    public function __construct($nomTypeAuteur, $likesTypeAuteur){
                                  // Id auto
      $this->nomTypeAuteur=$nomTypeAuteur;
      $this->likesTypeAuteur=$likesTypeAuteur;
    }


    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  function getTypesAuteur(){
    $formatted = array(
      'idTypeAuteur'=> $this->id,
      'nomTypeAuteur'=> $this->nomTypeAuteur,
      'likesTypeAuteur'=> $this->likesTypeAuteur;
    );
    return json_encode($formatted);
  }

function getIdTypeAuteur(){
  return $this->id;
}

  function getNomTypeAuteur(){
    return $this->nomTypeAuteur;
  }

  function getLikesTypeAuteur(){
    return $this->likesTypeAuteur;
  }

  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////

    function setTypesAuteur($nom, $likes){
      $this->nomTypeAuteur=$nom;
      $this->likesTypeAuteur=$likes;
    }

    function setNomTypesAuteur(){
      $this->nomTypeAuteur=$nom;
    }

    function setLikesTypesAuteur(){
      $this->likesTypeAuteur=$likes;
    }

}

?>
