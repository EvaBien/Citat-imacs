<?php
require_once 'MyPDO.citatimac.include.php';

/**
* Class typesAuteur
* Gère les types d'auteurs
*/
class typesAuteur {
    /**
    * @var int id du type d'auteur
    */
    private $id;
    /**
    * @var string nom du type d'auteur
    */
    private $nomTypeAuteur;
    /**
    * @var int nombre de like pour un type d'auteur
    */
    private $likesTypeAuteur;

    /**
    * @param string $nomTypeAuteur Nom du type d'auteur
    * @param int $likesTypeAuteur Nombre de likes pour un type d'auteur
    */
    public function __construct($nomTypeAuteur, $likesTypeAuteur){
                                  // Id auto
      $this->nomTypeAuteur=$nomTypeAuteur;
      $this->likesTypeAuteur=$likesTypeAuteur;
    }


    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  /**
  * @return json encode
  */
  function getTypesAuteur(){
    $formatted = array(
      'idTypeAuteur'=> $this->id,
      'nomTypeAuteur'=> $this->nomTypeAuteur,
      'likesTypeAuteur'=> $this->likesTypeAuteur
    );
    return json_encode($formatted);
  }

  /**
  * @return int
  */
  function getIdTypeAuteur(){
    return $this->id;
  }

  /**
  * @return string
  */
  function getNomTypeAuteur(){
    return $this->nomTypeAuteur;
  }

  /**
  * @return int
  */
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
