<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelCitations.php';
require '../model/ModelTagCitation.php';
require '../model/ModelTypesAuteur.php';



////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////

public function apiCreateCitation(HTTPRequest $request)
  {
    ////// VERIF/////
if (isset($_POST['contenu'])) {
    $query["contenuCitation"] = $_POST['contenu'];
}
if (isset($_POST["date"])) {
    $date = new DateTime($_POST["date"]);
    $query["dateCitation"] = $date->format("d-m-Y");
}

if (isset($_POST['auteur'])) {
    $query["auteurCitation"] = $_POST['auteur'];
}

if (isset($_POST['typeAuteur'])) {
    $query["idTypeAuteur"] = $_POST['typeAuteur'];
}
    // Creation du nouvel objet//
    $citation = new Citation($query['contenuCitation'],$query['dateCitation'],$query['auteurCitation'],$query['idTypeAuteur']);

    ////// ADD TO DB //////
    $queryStmt = "INSERT INTO Citations (contenuCitation, dateCitation, auteurCitation, idTypeAuteur) VALUES (?, ?, ?, ?);"

    $stmt = MyPDO::getInstance()->prepare($queryStmt);

    $stmt->bindValue(1, $citation->contenu);
    $stmt->bindValue(2, $citation->date);
    $stmt->bindValue(3, $citation->auteur);
    $stmt->bindValue(4, $citation->typeAuteur);

    $queryStatus = $stmt->execute();

    if ($queryStatus === false) {
      self::throwAnError();
    }
    else {
      $citation->id = MyPDO::getInstance()->lastInsertId();
    }
}


////////////////////////////////////////////////////////////////
///////////////////////////// READ ////////////////////////////
//////////////////////////////////////////////////////////////


////////////////////// GET ALL CITATIONS ///////////////////
public function apiGetAllCitations(HTTPRequest $request){
  // check HTTP method //
  $method = strtolower($_SERVER['REQUEST_METHOD']);

  if ($method !== 'get') {
  	http_response_code(405);
  	echo json_encode(array('message' => 'This method is not allowed.'));
  	exit();
  }
  // response status
  http_response_code(200);

  ////SEARCH CITATION IN DB ////
  $citations = array();
  $queryStmt = "SELECT * FROM S2_Citations ORDER BY dateCitation;"
  $stmt = MyPDO::getInstance()->prepare($queryStmt);
  $stmt->execute();

  while (($row = $stmt->fetch()) !== false) {
  	array_push($citations, $row); // Ajoute chaque citation au tableau citations
  }

  foreach ($citations as $key => $citation) { // On va chercher les tags et le typeAuteur
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

public function apiGetCitationById(HttpRequest $request){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit();
}

// VERIF//
if (isset($_GET['idCitation'])) {
    $request['idcitation'] = $_GET['idCitation'];
}
else {
	http_response_code(404);
  echo json_encode("No ID provided.");
  exit();
}

$queryStmt = "SELECT * FROM S2_Citations WHERE S2_Citation.idCitation = :idcitation LIMIT 1;"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['idcitation' => $query['idCitation']]);

while (($row = $stmt->fetch()) !== false) {
	array_push($citations, $row);
}

foreach ($citations as $key => $citation) { // On va chercher les tags et le typeAuteur
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
  $citation = "Cannot found movie with id {$query['idCitation']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();


}

////////////////////// GET CITATION BY TAGS ///////////////////

////////////////////// GET CITATION BY KEYWORD ///////////////////
public function apiGetCitationByKeyword(HttpRequest $request){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit();
}

$queryStmt = "SELECT * FROM S2_Citations WHERE contenuCitation LIKE %:keyword% ORDER BY dateCitation;"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query['keyWord']]);

while (($row = $stmt->fetch()) !== false) {
	array_push($citations, $row);
}

foreach ($citations as $key => $citation) { // On va chercher les tags et le typeAuteur
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
  $citation = "Cannot found movie with keyword {$query['keyWord']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}

////////////////////// GET CITATION BY TYPEAUTEUR ///////////////////


//////////////////// CLEAN TAB FOR UNIQUE CITATION /////////////////
//if (array_key_exists('title', $query))

////////////////////////////////////////////////////////////////
///////////////////////////// UPDATE //////////////////////////
//////////////////////////////////////////////////////////////

public static function apiUpdateCitation(HTTPRequest $request)
  {

    if (isset($_POST['contenu'])) {
        $query["contenuCitation"] = $_POST['contenu'];
    }
    if (isset($_POST["date"])) {
        $date = new DateTime($_POST["date"]);
        $query["dateCitation"] = $date->format("d-m-Y");
    }

    if (isset($_POST['auteur'])) {
        $query["auteurCitation"] = $_POST['auteur'];
    }

    if (isset($_POST['typeAuteur'])) {
        $query["idTypeAuteur"] = $_POST['typeAuteur'];
    }
    // Ajouter les else --> même valeur

    $queryStmt = "UPDATE S2_Citations SET contenuCitation = :contenu, auteurCitation = :auteur, idTypeAuteur = :typeauteur WHERE idCitation = :id";

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
  }


////////////////////////////////////////////////////////////////
///////////////////////////// DELETE //////////////////////////
//////////////////////////////////////////////////////////////


public static function apiDeleteCitation(HTTPRequest $request)
  {
    $queryStmt = "DELETE FROM Citation WHERE id = $request[id]";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(
      array(
        $id
      )
    );

    if ($stmt->rowCount() == 0) {
      return NULL;
    }
  }


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// ERROR //////////////////////////
  //////////////////////////////////////////////////////////////

  public static function throwAnError()
   {
     echo json_encode("An error occured.");
     http_response_code(500);
     exit();
   }


?>
