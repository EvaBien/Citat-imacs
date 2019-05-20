<?php

require_once 'MyPDO.citatimac.include.php';
require_once 'ModelTypesSignalement.php';
require_once 'ModelCitations.php';

/**
* Class Signalement
* Gère les signalement des citations
*/
class signalement {
    /**
    * @var int id du signalement
    */
    private $id;
    /**
    * @var int id du type de signalement
    */
    private $idTypeSignalement;
    /**
    * @var string message de la personne ayant signalé
    */
    private $messageSignalement;
    /**
    * @var string état du signalement (traité/pas traité)
    */
    private $statutSignalement;
    /**
    * @var int id de la citation signalée
    */
    private $idCitation;

    /**
    * @param int $typeSignalement Id du type de signalement
    * @param string $messageSignalement Message de la personne ayant signalé
    * @param string $statutSignalement État du signalement
    * @param int $idCitation Id de la citaion signalé
    */
    public function __construct($typeSignalement, $messageSignalement, $statutSignalement, $idCitation){
                                  // Id auto
      $this->idTypeSignalement=$typeSignalement;
      $this->messageSignalement=$messageSignalement ; // A récupérer dans ce qu'écrit l'utilisateur ?
      $this->statutSignalement=$statutSignalement;
      $this->Citation=$idCitation; // Lier à la table Citation
    }


    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  /**
  * @return json encode
  */
  function getSignalement(){
    $formatted = array(
      'idSignal'=> $this->id,
      'nomTypeSignal'=> $this->idTypeSignalement, // à modifier pour getTypeSignal by id
      'message'=> $this->messageSignalement,
      'statut'=> $this->statutSignalement,
      'citation'=> $this->idCitation // à modifier pour getCitation by id
    );

    return json_encode($formatted);
  }

  /**
  * @return int
  */
  function getIdSignalement(){
    return $this->id;
  }

  /**
  * @return int
  */
  function getTypeSignalement(){
    return $this->îdTypeSignalement;
  }

  /**
  * @return string
  */
  function getMessageSignalement(){
    return $this->messageSignalement;
  }

  /**
  * @return string
  */
  function getStatutSignalement(){
    return  $this->statutSignalement;
  }

  /**
  * @return int
  */
  function getIdCitation(){
    return $this->idCitation;
  }

  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////

    function setSignalement($typeSignal, $message, $statut, $citation){
      $this->idTypeSignalement=$typeSignal;
      $this->messageSignalement=$message;
      $this->statutSignalement=$statut;
      $this->idCitation=$citation;
    }

    function setTypeSignalement($typeSignal){
      $this->idTypeSignalement=$typeSignal;
    }

    function setMessageSignalement($message){
      $this->messageSignalement=$message;
    }

    function setStatutSignalement($statut){
      $this->statutSignalement=$statut;
    }

    function setIdCitation($citation){
      $this->idCitation=$citation;
    }
}


?>
