<?php
require_once 'MyPDO.citatimac.include.php';
date_default_timezone_set('UTC');

class Citation {
    /**
    * @var int id de la citation
    */
    private $id;
    /**
    * @var string contenu de la citation
    */
    private $contenu;
    /**
    * @var date date d'ajout de la citation
    */
    private $date;
    /**
    * @var string auteur de la citation
    */
    private $auteur;
    /**
    * @var int nombre de likes de la citation
    */
    private $likes;
    /**
    * @var int id du type d'auteur de la citation
    */
    private $typeAuteur;

    /**
    * @param string $contenu Contenu de la citation
    * @param date $date Date d'ajout de la citation
    * @param string $auteur Auteur de la citation
    * @param int $typeAuteur Id du type d'auteur de la citation
    */
    public function __construct($contenu, $date, $auteur, $typeAuteur){
                                  // Id auto
      $this->contenu=$contenu;
      $this->date=$date; // A verifier
      $this->auteur=$auteur;
      $this->likes=0;
      $this->typeAuteur=$typeAuteur; // Lier à la table typeAuteur

    }


////////////////////////////////////////////////////////////////
//////////////////////////// GETTERS //////////////////////////
//////////////////////////////////////////////////////////////

  /**
  * @return json encode
  */
  function getCitation(){
    $formatted = array(
      'idCita'=> $this->id,
      'contenu'=> $this->contenu,
      'date'=> $this->date,
      'auteur'=> $this->auteur,
      'likes'=> $this->likes,
      'typeAuteur'=> $this->typeAuteur
    );

    return json_encode($formatted);
  }

  /**
  * @return int
  */
  function getIdCitation(){
    return $this->id;
  }

  /**
  * @return string
  */
  function getContenuCitation(){
    return $this->contenu;
  }

  /**
  * @return date
  */
  function getDateCitation(){
    return $this->date;
  }

  /**
  * @return string
  */
  function getAuteurCitation(){
    return $this->auteur;
  }

  /**
  * @return int
  */
  function getLikesCitation(){
    return $this->likes;
  }

  /**
  * @return int
  */
  function getTypeAuteurCitation(){
    return $this->typeAuteur;
  }


  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////

    function setCitation($content, $date, $auteur, $likes, $typeAuteur){
      $this->contenu=$contenu;
      $this->date=$date;
      $this->auteur=$auteur;
      $this->likes=$likes;
      $this->typeAuteur=$typeAuteur;
    }

    function setIdCitation($id){
      $this->id=$id;
    }

    function setContenuCitation($contenu){
      $this->contenu=$contenu;
    }

    function setDateCitation($date){
      $this->date=$date;
    }

    function setAuteurCitation($auteur){
      $this->auteur=$auteur;
    }

    function setLikesCitation($likes){
      $this->likes=$likes;
    }

    function setTypeAuteurCitation($typeAuteur){
      $this->typeAuteur=$typeAuteur;
    }


}

?>
