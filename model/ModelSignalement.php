<?php
require 'MyPDO.citatimac.include.php';
require 'ControllerTypeSignalement.php';
require 'ControllerCitation.php';


class signalement {
    private $id;
    private $idTypeSignalement;
    private $messageSignalement;
    private $statutSignalement;
    private $idCitation;

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

  function getSignalement(){
    $formatted = array(
      'idSignal'=> $this->id,
      'nomTypeSignal'=> $this->idTypeSignalement, // à modifier pour getTypeSignal by id
      'message'=> $this->messageSignalement,
      'statut'=> $this->statutSignalement,
      'citation'=> $this->idCitation; // à modifier pour getCitation by id
    );

    return json_encode($formatted);
  }

  function getIdSignalement(){
    return $this->id;
  }

  function getTypeSignalement(){
    return $this->îdTypeSignalement;
  }

  function getMessageSignalement(){
    return $this->messageSignalement;
  }

  function getStatutSignalement(){
    return  $this->statutSignalement;
  }

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
