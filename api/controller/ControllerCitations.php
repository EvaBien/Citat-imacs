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
    $queryStmt = "INSERT INTO Citations (contenuCitation, dateCitation, auteurCitation, idTypeAuteur) VALUES (?, ?, ?, ?);";

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
      $citation->id = MyPDO::getInstance()->lastInsertId();
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
  $queryStmt = "SELECT * FROM S2_Citations ORDER BY dateCitation;";
  $stmt = MyPDO::getInstance()->prepare($queryStmt);
  $stmt->execute();

  while (($row = $stmt->fetch()) !== false) {
  	array_push($citations, $row); // Ajoute chaque citation au tableau citations
  }

  foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
  $typeAuteur='';
  $tags = array();


////SEARCH TYPEAUTEUR IN DB ////
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
		INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
		WHERE S2_Citations.idCitation = :idcitation;
SQL
	);
	$stmt->execute(['idcitation'=>$citation['idCitation']]);
	while (($row = $stmt->fetch()) !== false) {
		$typeAuteur=$row['nomTypeAuteur'];
	}

////SEARCH TAGS IN DB ////
  $stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT S2_Tags.nomTag FROM `S2_Tags`
		INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
		INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
		WHERE S2_TagCitation.idCitation = :idcitation;
SQL
	);
	$stmt->execute(['idcitation'=>$citation['idCitation']]);
	while (($row = $stmt->fetch()) !== false) {
		array_push($tags, $row['nomTag']);
	}

  // RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
  $citations[$key]['typeAuteur'] = $typeAuteur;
  $citations[$key]['tags'] = $tags;
}

  echo json_encode($citations);

  exit();
  }

////////////////////// GET CITATION BY ID ///////////////////

 function apiGetCitationById($query){

$queryStmt = "SELECT * FROM S2_Citations WHERE S2_Citations.idCitation = :idcitation LIMIT 1;";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['idcitation' => $query['idCitation']]);

while (($row = $stmt->fetch()) !== false) {
	array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
  $citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();


}

////////////////////// GET CITATION BY TAGS ///////////////////
 function apiGetCitationByTags($query){
  // check HTTP method //
$method = strtolower($query['method']);
if ($method!=="post") {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit();
}


$tagsList = implode(',', array_fill(0, count($query['tags']), '?'));


$queryStmt = "SELECT * FROM S2_Citations
  INNER JOIN S2_TagCitations ON S2_TagCitation.idCitation = S2_Tags.idCitation
  INNER JOIN S2_Tags ON S2_Tags.idTags = S2_TagCitation.idTag
  WHERE S2_Tags.nomTag IN ($tagsList);";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute();

while (($row = $stmt->fetch()) !== false) {
	array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
$citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}


//////////////////// GET CITATION BY KEYWORD /////////////////
 function apiGetCitationByKeyword($query){

$queryStmt = "SELECT * FROM S2_Citations WHERE contenuCitation LIKE %:keyword% ORDER BY dateCitation;";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query['keyWord']]);

while (($row = $stmt->fetch()) !== false) {
	array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
$citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}

////////////////////// GET CITATION BY TYPEAUTEUR ///////////////////
 function apiGetCitationByTypeAuteur($query){

$typesList = implode(',', array_fill(0, count($query['typesauteur']), '?'));

$queryStmt = "SELECT * FROM S2_Citations
  WHERE S2_Citations.idTypeAuteur IN ($typesList);";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute();

while (($row = $stmt->fetch()) !== false) {
	array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
$citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
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
  FROM S2_Citations
  JOIN S2_TagCitation ON S2_Citations.idCitation=S2_TagCitation.idCitation
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN ($tagslist) AND S2_Citations.idTypeAuteur IN ($typesList) AND S2_Citation.contenuCitation LIKE %:keyword%;";

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword'=>$query["keyWord"]]);
while (($row = $stmt->fetch()) !== false) {
  array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
$citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}

//////////////////// GET BY KEYWORD & TAGS ////////////////////
 function apiGetCitationByTagsAndKeyword($query){


$tagsList = implode(',', array_fill(0, count($query['tags']), '?'));

$queryStmt = "SELECT *
  FROM S2_Citations
  JOIN S2_TagCitation ON S2_Citations.idCitation=S2_TagCitation.idCitation
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN ($tagslist) AND S2_Citation.contenuCitation LIKE %:keyword%;";


$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query["keyword"]]);

while (($row = $stmt->fetch()) !== false) {
  array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
$citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}


////////////////// GET BY KEYWORD & TYPEAUTEUR ///////////////
 function apiGetCitationByTypeAuteurAndKeyword($query){

$typesList = implode(',', array_fill(0, count($query['typesauteur']), '?'));

$queryStmt = "SELECT *
  FROM S2_Citations
  WHERE S2_Citations.idTypeAuteur IN ($typesList) AND S2_Citation.contenuCitation LIKE %:keyword%;";


$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query["keyword"]]);


while (($row = $stmt->fetch()) !== false) {
  array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
$citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}



////////////////// GET BY  TAGS & TYPEAUTEUR ///////////////

 function apiGetCitationByTypeAuteurAndTags($query){

$tagsList = implode(',', array_fill(0, count($query['tags']), '?'));
$typesList = implode(',', array_fill(0, count($query['typesauteur']), '?'));

$queryStmt = "SELECT *
  FROM S2_Citations
  JOIN S2_TagCitation ON S2_Citations.idCitation=S2_TagCitation.idCitation
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN ($tagslist) AND S2_Citations.idTypeAuteur IN ($typesList);";


$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute();


while (($row = $stmt->fetch()) !== false) {
  array_push($citations, $row);
}

foreach ($citations as $citation) { // On va chercher les tags et le typeAuteur
$typeAuteur='';
$tags = array();


////SEARCH TYPEAUTEUR IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
  INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
  WHERE S2_Citations.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  $typeAuteur=$row['nomTypeAuteur'];
}

////SEARCH TAGS IN DB ////
$stmt = MyPDO::getInstance()->prepare(<<<SQL
  SELECT S2_Tags.nomTag FROM `S2_Tags`
  INNER JOIN S2_TagCitations ON S2_TagCitation.idTag = S2_Tags.idTag
  INNER JOIN S2_Citations ON S2_Citations.idCitation = S2_TagCitation.idCitation
  WHERE S2_TagCitation.idCitation = :idcitation;
SQL
);
$stmt->execute(['idcitation'=>$citation['idCitation']]);
while (($row = $stmt->fetch()) !== false) {
  array_push($tags, $row['nomTag']);
}


// RANGER DANS LES CLES DE CITATION + ENCODER EN JSON//
$citations[$key]['typeAuteur'] = $typeAuteur;
$citations[$key]['tags'] = $tags;
}


// VERIFICATION QUE RESULTAT NON VIDE //
if (empty($citation)) {
  http_response_code(404);
$citation = "Cannot found citation with this factors";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}
////////////////////////////////////////////////////////////////
///////////////////////////// UPDATE //////////////////////////
//////////////////////////////////////////////////////////////

function apiUpdateCitation($query)
  {

    if (verifMdp($query['password']) == true){
   // Si vide doit remettre les mêmes valeurs !

    $queryStmt = "UPDATE S2_Citations SET contenuCitation = :contenu, auteurCitation = :auteur, idTypeAuteur = :typeauteur WHERE idCitation = :id;";

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
    } else {
      echo "Error Update Citation - Password incorrect";
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

    $queryStmt1 = "DELETE FROM S2_TagCitation WHERE idCitation = :idcitation;";

    $stmt1 = MyPDO::getInstance()->prepare($queryStmt1);
    $stmt1->execute(['idcitation' => $query['idCitation']]);

    $queryStmt2 = "DELETE FROM S2_Citations WHERE idCitation = :idcitation;";

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


    $queryStmt = "SELECT likesCitation FROM S2_Citations WHERE idCitation = :idCitation;";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(['idCitation'=>$query['idCitation']]);

    $likes = $stmt->fetch();
    echo $likes;
    exit();
    }

// Update likes citations
    function updateCitationLikes($query)
      {

        // check HTTP method //
      $method = strtolower($query['method']); // Je verifie si c'est bien un get
      if ($method !== 'put') {
          http_response_code(405);
          echo json_encode(array('message' => 'This method is not allowed.'));
          exit(); // SInon je sors
      }

        $queryStmt = "UPDATE S2_Citations SET likesCitation= :newlikes WHERE idCitation = :id;";

        $stmt = MyPDO::getInstance()->prepare($queryStmt);
        $stmt->execute(
          array(
            'newlikes' =>$query['likes'],
            'id'=>$query['idCitation']
          )
        );

        if ($stmt->rowCount() == 0) {
          return NULL;
        }
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
