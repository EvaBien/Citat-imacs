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
}

///////////////////////FUNCTIONS GETTER////////////////////////

  function getTags(){

  }

  function getNomTag(){

  }

  function getLikesTag(){

  }

  ///////////////////////FUNCTIONS SETTER///////////////////////
  
    function setTags(){

    }

    function setNomTag(){
      
    }

    function setLikesTag(){
      
    }

?>
