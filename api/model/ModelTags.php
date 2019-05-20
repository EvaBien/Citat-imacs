<?php
require_once 'MyPDO.citatimac.include.php';

/**
* Class Tags
* Gère les tags
*/
class tags {
    /**
    * @var int id du tag
    */
    private $id;
    /**
    * @var string nom du tag
    */
    private $nomTag;
    /**
    * @var int nombre de like du tag
    */
    private $likesTag;

    /**
    * @param string $nomTag Nom du tag
    * @param int $likesTag Nombre de likes du tag
    */
    public function __construct( $nomTag, $likesTag){
                                  // Id auto
      $this->nomTag=$nomTag ;
      $this->likesTag=$likesTag;
    }


    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  /**
  * @return json encode
  */
  function getTags(){
    $formatted = array(
      'idTag'=> $this->id,
      'nomTag'=> $this->nomTag,
      'likesTag'=> $this->likesTag
    );
    return json_encode($formatted);
  }

  /**
  * @return int
  */
  function getIdTag(){
    return $this->id;
  }

  /**
  * @return string
  */
  function getNomTag(){
    return $this->nomTag;
  }

  /**
  * @return int
  */
  function getLikesTag(){
    return $this->likesTag;
  }

  ////////////////////////////////////////////////////////////////
  //////////////////////////// SETTERS //////////////////////////
  //////////////////////////////////////////////////////////////

    function setTags($nom, $likes){
      $this->nomTag=$nom;
      $this->likesTag=$likes;
    }

    function setNomTag(){
      $this->nomTag=$nom;
    }

    function setLikesTag(){
      $this->likesTag=$likes;
    }

}

?>
