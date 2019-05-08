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

  }

  function getNomTypeAuteur(){

  }

  function getLikesTypeAuteur(){

  }

  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////
  
    function setTypesAuteur(){

    }

    function setNomTypesAuteur(){

    }

    function setLikesTypesAuteur(){

    }

}

?>
