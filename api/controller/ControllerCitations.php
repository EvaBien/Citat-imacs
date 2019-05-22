<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../model/ModelCitations.php';
require_once '../model/ModelTagCitation.php';
require_once '../model/ModelTypesAuteur.php';

////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////


function apiCreateCitation($query){

    // Creation du nouvel objet//
    $citation = new Citation($query['contenuCitation'],$query['dateCitation'],$query['auteurCitation'],$query['idTypeAuteur']);

    ////// ADD TO DB //////
    $queryStmt = "INSERT INTO s2_citations (contenuCitation, dateCitation, auteurCitation, idTypeAuteur) VALUES (?, ?, ?, ?);";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);

    $stmt->bindValue(1, $citation->getContenu());
    $stmt->bindValue(2, $citation->getDate());
    $stmt->bindValue(3, $citation->getAuteur());
    $stmt->bindValue(4, $citation->getTypeAuteur());

    $queryStatus = $stmt->execute();

    if ($queryStatus === false) {
      throwAnErrorCitation();
    }
    else {
      $citation->setIdCitation(MyPDO::getInstance()->lastInsertId());
    }


    foreach ($query['tagsCitation'] as $idTag) { // On va chercher les tags et le typeAuteur
      $association = new tagCitation($idTag,$citation->getIdCitation());

      $queryStmt = "INSERT INTO s2_tagcitation (idCitation, idTag) VALUES (?, ?,);";

      $stmt = MyPDO::getInstance()->prepare($queryStmt);

      $stmt->bindValue(1, $association->getIdCitation());
      $stmt->bindValue(2, $association->getIdTag());

    }

  }

////////////////////////////////////////////////////////////////
///////////////////////////// READ ////////////////////////////
//////////////////////////////////////////////////////////////


////////////////////// GET ALL CITATIONS ///////////////////
 function apiGetAllCitations($query){

  // response status
  http_response_code(200);

  ////SEARCH CITATION IN DB ////
  $citations = array();
  $queryStmt = "SELECT * FROM s2_citations ORDER BY dateCitation;";
  $stmt = MyPDO::getInstance()->prepare($queryStmt);
  $stmt->execute();

  while (($row = $stmt->fetch()) !== false) {

    $typeAuteur='';
    $tags = array();


  ////SEARCH TYPEAUTEUR IN DB ////
  	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
  		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
  		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
  		WHERE s2_citations.idCitation = :idcitation;
SQL
  	);
  	$stmt2->execute(['idcitation'=>$row['idCitation']]);
  	while (($row2 = $stmt2->fetch()) !== false) {
  		$typeAuteur=$row2['nomTypeAuteur'];
  	}

  ////SEARCH TAGS IN DB ////
    $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
  		SELECT s2_tags.nomTag FROM `s2_tags`
  		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
  		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
  		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
  	);
  	$stmt3->execute(['idcitation'=>$row['idCitation']]);
  	while (($row3 = $stmt3->fetch()) !== false) {
  		array_push($tags, $row3);
  	}

    // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
    $row['typeAuteur'] = $typeAuteur;
    $row['tags'] = $tags;

    array_push($citations, $row); // Ajoute chaque citation au tableau citations
  }

  echo json_encode($citations);

  exit();
  }

////////////////////// GET CITATION BY ID ///////////////////

 function apiGetCitationById($query){

$queryStmt = "SELECT * FROM s2_citations WHERE s2_citations.idCitation = :idcitation LIMIT 1;";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['idcitation' => $query['idCitation']]);

while (($row = $stmt->fetch()) !== false) {

      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;
	array_push($citations, $row);
}


echo json_encode($citation);
exit();


}

////////////////////// GET CITATION BY TAGS ///////////////////
 function apiGetCitationByTags($query){


$tagsList = implode(',', array_fill(0, count($query['tags']), '?'));


$queryStmt = "SELECT * FROM s2_citations
  INNER JOIN s2_tagcitation ON s2_tagcitation.idCitation = s2_tags.idCitation
  INNER JOIN s2_tags ON s2_tags.idTags = s2_tagcitation.idTag
  WHERE s2_tags.nomTag IN ($tagsList);";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute();

while (($row = $stmt->fetch()) !== false) {

      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;

      array_push($citations, $row); // Ajoute chaque citation au tableau citations
}

echo json_encode($citation);
exit();
}


//////////////////// GET CITATION BY KEYWORD /////////////////
 function apiGetCitationByKeyword($query){

$queryStmt = "SELECT * FROM s2_citations WHERE contenuCitation LIKE %:keyword% ORDER BY dateCitation;";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query['keyWord']]);

while (($row = $stmt->fetch()) !== false) {

      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;

      array_push($citations, $row); // Ajoute chaque citation au tableau citations
}

echo json_encode($citation);
exit();
}

////////////////////// GET CITATION BY TYPEAUTEUR ///////////////////
 function apiGetCitationByTypeAuteur($query){

$typesList = implode(',', array_fill(0, count($query['typesauteur']), '?'));

$queryStmt = "SELECT * FROM s2_citations
  WHERE s2_citations.idTypeAuteur IN ($typesList);";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute();

while (($row = $stmt->fetch()) !== false) {

      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;

      array_push($citations, $row); // Ajoute chaque citation au tableau citations
}

echo json_encode($citation);
exit();
}

///////////////////////////////////////////////////////////////////
//////////////////// REQUETES CRITERES MULTIPLES /////////////////

///////////// GET BY KEYWORD & TAGS & TYPEAUTEUR ///////////////
 function apiGetCitationByAll($query){


$tagsList = implode(',', array_fill(0, count($query['tags']), '?'));
$typesList = implode(',', array_fill(0, count($query['typesauteur']), '?'));

$queryStmt = "SELECT *
  FROM s2_citations
  JOIN s2_tagcitation ON s2_citations.idCitation=s2_tagcitation.idCitation
  JOIN s2_tags ON s2_tagcitation.idTag = s2_tags.idTag
  WHERE s2_tags.nomTags IN ($tagslist) AND s2_citations.idTypeAuteur IN ($typesList) AND s2_citation.contenuCitation LIKE %:keyword%;";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword'=>$query["keyWord"]]);
while (($row = $stmt->fetch()) !== false) {

      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;

      array_push($citations, $row); // Ajoute chaque citation au tableau citations
}

echo json_encode($citation);
exit();
}

//////////////////// GET BY KEYWORD & TAGS ////////////////////
 function apiGetCitationByTagsAndKeyword($query){


$tagsList = implode(',', array_fill(0, count($query['tags']), '?'));

$queryStmt = "SELECT *
  FROM s2_citations
  JOIN s2_tagcitation ON s2_citations.idCitation=s2_tagcitation.idCitation
  JOIN s2_tags ON s2_tagcitation.idTag = s2_tags.idTag
  WHERE s2_tags.nomTags IN ($tagslist) AND s2_citation.contenuCitation LIKE %:keyword%;";


$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query["keyword"]]);

while (($row = $stmt->fetch()) !== false) {

      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;

      array_push($citations, $row); // Ajoute chaque citation au tableau citations
}

echo json_encode($citation);
exit();
}


////////////////// GET BY KEYWORD & TYPEAUTEUR ///////////////
 function apiGetCitationByTypeAuteurAndKeyword($query){

$typesList = implode(',', array_fill(0, count($query['typesauteur']), '?'));

$queryStmt = "SELECT *
  FROM s2_citations
  WHERE s2_citations.idTypeAuteur IN ($typesList) AND s2_citation.contenuCitation LIKE %:keyword%;";


$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query["keyword"]]);


while (($row = $stmt->fetch()) !== false) {

      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;

      array_push($citations, $row); // Ajoute chaque citation au tableau citations
}

echo json_encode($citation);
exit();
}



////////////////// GET BY  TAGS & TYPEAUTEUR ///////////////

 function apiGetCitationByTypeAuteurAndTags($req){

$query=json_decode($req, true);

$tagsList = join(",",$query['tags']);
$typesList = join(",",$query['typesAuteur']);

print_r($tagsList);
$citations = array();

$queryStmt = "SELECT *
FROM s2_citations
JOIN s2_tagcitation ON s2_citations.idCitation=s2_tagcitation.idCitation
JOIN s2_tags ON s2_tagcitation.idTag = s2_tags.idTag
JOIN s2_typesauteur ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
WHERE s2_tags.nomTag IN (:tagslist) AND s2_typesauteur.nomTypeAuteur IN (:typeslist);";

$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(array(
  'tagslist' =>$tagsList,
  'typeslist'=>$typesList
  )
);

while (($row = $stmt->fetch()) !== false) {

      print_r($row);
      $typeAuteur='';
      $tags = array();


    ////SEARCH TYPEAUTEUR IN DB ////
    	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_typesauteur.nomTypeAuteur FROM `s2_typesauteur`
    		INNER JOIN s2_citations ON s2_citations.idTypeAuteur = s2_typesauteur.idTypeAuteur
    		WHERE s2_citations.idCitation = :idcitation;
SQL
    	);
    	$stmt2->execute(['idcitation'=>$row['idCitation']]);
    	while (($row2 = $stmt2->fetch()) !== false) {
    		$typeAuteur=$row2['nomTypeAuteur'];
    	}

    ////SEARCH TAGS IN DB ////
      $stmt3 = MyPDO::getInstance()->prepare(<<<SQL
    		SELECT s2_tags.nomTag FROM `s2_tags`
    		INNER JOIN s2_tagcitation ON s2_tagcitation.idTag = s2_tags.idTag
    		INNER JOIN s2_citations ON s2_citations.idCitation = s2_tagcitation.idCitation
    		WHERE s2_tagcitation.idCitation = :idcitation;
SQL
    	);
    	$stmt3->execute(['idcitation'=>$row['idCitation']]);
    	while (($row3 = $stmt3->fetch()) !== false) {
    		array_push($tags, $row3);
    	}

      // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
      $row['typeAuteur'] = $typeAuteur;
      $row['tags'] = $tags;

      array_push($citations, $row); // Ajoute chaque citation au tableau citations
}
echo json_encode($citations);
exit();
}
////////////////////////////////////////////////////////////////
///////////////////////////// UPDATE //////////////////////////
//////////////////////////////////////////////////////////////

function apiUpdateCitation($query)
  {

    if (verifMdp($query['password']) == true){
   // Si vide doit remettre les mêmes valeurs !

    $queryStmt = "UPDATE s2_citations SET contenuCitation = :contenu, auteurCitation = :auteur, idTypeAuteur = :typeauteur WHERE idCitation = :id;";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(
      array(
        ':contenu' => $query["contenuCitation"],
        ':auteur' => $query["auteurCitation"],
        ':typeauteur'=> $query["idTypeAuteur"],
        ':id'=>$query["idCitation"]
      )
    );

    if ($stmt->rowCount() == 0) {
      return NULL;
    }
     } else {
      echo "Error Update Citation - Password incorrect";
  }
}



////////////////////////////////////////////////////////////////
///////////////////////////// DELETE //////////////////////////
//////////////////////////////////////////////////////////////

function apiDeleteCitation($query){

  if (verifMdp($query['password']) == true){
    {

      if (verifMdp($query['password']) == true){

        $queryStmt1 = "DELETE FROM s2_tagcitation WHERE idCitation = :idcitation;";

        $stmt1 = MyPDO::getInstance()->prepare($queryStmt1);
        $stmt1->execute(['idcitation' => $query['idCitation']]);

        $queryStmt2 = "DELETE FROM s2_citations WHERE idCitation = :idcitation;";

        $stmt2 = MyPDO::getInstance()->prepare($queryStmt2);
        $stmt2->execute(['idcitation' => $query['idCitation']]);

        if ($stmt2->rowCount() == 0) {
          return NULL;
        }
      } else {
        echo "Error Delete Citation - Password incorrect";
      }
    }
  }
}

    ////////////////////////////////////////////////////////////////
    ///////////////////////////// OTHER //////////////////////////
    //////////////////////////////////////////////////////////////


    //Verif mot de passe
function verifMdp($string){
      $mdp = "BDBwait2see";
      $mdp_sel = $mdp."20182021";
      $mdp_hash = sha1($mdp_sel);

      $string_sel = $string."20182021";
      $string_hash = sha1($string_sel);

      if ($mdp_hash==$string_hash){
        return true;
      } else {
        echo " \n Mot de passe incorrect ! \n";
        return false;
      }
}



function getCitationLikes($query)
  {

    $req=json_decode($query, true);

    $queryStmt = "SELECT likesCitation FROM s2_citations WHERE idCitation = :idcitation;";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(['idcitation'=>$req['idCitation']]);
    $row = $stmt->fetch();

    echo json_encode($row);
    exit();
    }


// Update likes citations
    function updateCitationLikes($query)
      {

        $req=json_decode($query, true);
        $queryStmt = "UPDATE s2_citations SET likesCitation= :newlikes WHERE idCitation = :id;";

        $stmt = MyPDO::getInstance()->prepare($queryStmt);
        $stmt->execute(
          array(
            'newlikes' =>$req['likes'],
            'id'=>$req['idCitation']
          )
        );

        if ($stmt->rowCount() == 0) {
          return NULL;
        }
        echo json_encode("citation likes updated \n");
        exit();
      }

  ////////////////////////////////////////////////////////////////
  ///////////////////////////// ERROR //////////////////////////
  //////////////////////////////////////////////////////////////

  function throwAnErrorCitation($query)
   {
     echo json_encode("An error occured. \n", $query);
     http_response_code(500);
     exit();
   }


?>
