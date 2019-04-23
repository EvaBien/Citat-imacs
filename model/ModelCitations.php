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

  }

  function getIdCitation(){

  }

  function getContenuCitation(){

  }

  function getDateCitation(){

  }

  function getAuteurCitation(){

  }

  function getLikesCitation(){

  }

  function getTypeAuteurCitation(){

  }


  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////

    function setCitation(){

    }

    function setIdCitation(){

    }

    function setContenuCitation(){

    }

    function setDateCitation(){

    }

    function setAuteurCitation(){

    }

    function setLikesCitation(){

    }

    function setTypeAuteurCitation(){

    }


}

?>
