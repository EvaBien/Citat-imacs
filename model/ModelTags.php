<?php
require 'MyPDO.citatimac.include.php';

class tags {
    private $id;
    private $nomTag;
    private $likesTag;
    public function __construct($tags, $nomTag, $likesTag){
                                  // Id auto
      $this->tags=$tags;
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
