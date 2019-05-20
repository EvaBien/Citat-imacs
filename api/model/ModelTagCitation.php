<?php
require_once 'MyPDO.citatimac.include.php';
require_once 'ModelCitations.php';
require_once 'ModelTags.php';

/**
* Class tagCitation
* Gère les tags d'une citation
*/
class tagCitation {
    /**
    * @var int id de la citation
    */
    private $idCitation;
    /**
    * @var int id du tag
    */
    private $idTag;

    /**
    * @param int $idTag Id du tag
    * @param int $idCitation Id de la citation
    */
    public function __construct($idTag, $idCitation){
                                  // Id auto
      $this->idTag=$idTag; //Lier à la table Tags
      $this->idCitation=$idCitation; // Lier à la table Citation
    }


    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  /**
  * @return json encode
  */
  function getTagCitation(){
      $formatted = array(
        'tag'=> $this->idTag,
        'citation'=> $this->idCitation
      );

      return json_encode($formatted);
  }

  /**
  * @return int
  */
  function getIdCitation(){
    return $this->idCitation;
  }

  /**
  * @return int
  */
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
