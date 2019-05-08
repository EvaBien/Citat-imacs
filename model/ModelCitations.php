<?php
require 'MyPDO.citatimac.include.php';
date_default_timezone_set('UTC');



class Citation {

    private $id;
    private $contenu;
    private $date;
    private $auteur;
    private $likes;
    private $typeAuteur;

    public function __construct($contenu, $date, $auteur, $typeAuteur){
                                  // Id auto
      $this->contenu=$contenu;
      $this->date=new DateTime(); // A verifier
      $this->auteur=$auteur;
      $this->likes=0;
      $this->typeAuteur=$typeAuteur; // Lier à la table typeAuteur

    }


////////////////////////////////////////////////////////////////
//////////////////////////// GETTERS //////////////////////////
//////////////////////////////////////////////////////////////

  function getCitation(){
    $formatted = array(
      'id'=> $this->id,
      'contenu'=> $this->contenu,
      'date'=> $this->date,
      'auteur'=> $this->auteur,
      'likes'=> $this->likes,
      'typeAuteur'=> $this->typeAuteur;
    );

    return json_encode($formatted);
  }

  function getIdCitation(){
    return $this->id;
  }

  function getContenuCitation(){
    return $this->contenu=$contenu;
  }

  function getDateCitation(){
    return $this->date=$date;
  }

  function getAuteurCitation(){
    return $this->auteur=$auteur;
  }

  function getLikesCitation(){
    return $this->likes=$likes;
  }

  function getTypeAuteurCitation(){
    return $this->typeAuteur=$typeAuteur;
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
