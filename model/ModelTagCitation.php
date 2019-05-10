<?php
require 'MyPDO.citatimac.include.php';
require 'ModelCitations.php';
require 'ModelTags.php';




class tagCitation {
    private $idCitation;
    private $idTag;
    public function __construct($idTag, $idCitation){
                                  // Id auto
      $this->idTag=$idTag; //Lier à la table Tags
      $this->idCitation=$idCitation; // Lier à la table Citation
    }


    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  function getTagCitation(){
      $formatted = array(
        'tag'=> $this->idTag,
        'citation'=> $this->idCitation;
      );

      return json_encode($formatted);
  }

  function getIdCitation(){
    return $this->idCitation;
  }

  function getIdTag(){
    return $this->idTag;
  }

  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////

  function setTagCitation($citation, $tag){
    $this->idCitation=$citation;
    $this->idTag=$tag;
  }

  function setIdCitation(){
    $this->idCitation=$citation;
  }

  function setIdTag(){
    $this->idTag=$tag;
  }

}

?>
