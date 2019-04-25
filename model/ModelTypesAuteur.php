<?php
require 'MyPDO.citatimac.include.php';

class typesAuteur {
    private $id;
    private $nomTypeAuteur;
    private $likesTypeAuteur;
    public function __construct($typesAuteur, $nomTypeAuteur, $likesTypeAuteur){
                                  // Id auto
      $this->typesAuteur=$typesAuteur;
      $this->nomTypeAuteur=$nomTypeAuteur; 
      $this->likesTypeAuteur=$likesTypeAuteur;
    }
}

///////////////////////FUNCTIONS GETTER////////////////////////

  function getTypesAuteur(){

  }

  function getNomTypeAuteur(){

  }

  function getLikesTypeAuteur(){

  }

  ///////////////////////FUNCTIONS SETTER///////////////////////
  
    function setTypesAuteur(){

    }

    function setNomTypesAuteur(){
      
    }

    function setLikesTypesAuteur(){
      
    }

?>
