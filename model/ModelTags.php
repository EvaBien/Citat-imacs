<?php
require 'MyPDO.citatimac.include.php';

class tags {
    private $id;
    private $nomTag;
    private $likesTag;
    public function __construct( $nomTag, $likesTag){
                                  // Id auto
      $this->nomTag=$nomTag ;
      $this->likesTag=$likesTag;
    }


    ////////////////////////////////////////////////////////////////
    //////////////////////////// GETTERS //////////////////////////
    //////////////////////////////////////////////////////////////

  function getTags(){
    $formatted = array(
      'idTag'=> $this->id,
      'nomTag'=> $this->nomTag,
      'likesTag'=> $this->likesTag;
    );
    return json_encode($formatted);
  }

  function getIdTag(){
    return $this->id;
  }

  function getNomTag(){
    return $this->nomTag;
  }

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
