<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelCitations.php';
require '../model/ModelTagCitation.php';
require '../model/ModelTypesAuteur.php';



////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////


public function apiCreateCitation(HttpRequest $query)
  {
    ////// VERIF/////

    // check HTTP method //
  $method = strtolower($query['method']); // Je verifie si c'est bien un get
  if ($method !== 'post') {
      http_response_code(405);
      echo json_encode(array('message' => 'This method is not allowed.'));
      exit(); // Sinon je sors
  }

// if (isset($_POST['contenu'])) {
//     $query["contenuCitation"] = $_POST['contenu'];
// }
// if (isset($_POST["date"])) {
//     $date = new DateTime($_POST["date"]);
//     $query["dateCitation"] = $date->format("d-m-Y");
// }
//
// if (isset($_POST['auteur'])) {
//     $query["auteurCitation"] = $_POST['auteur'];
// }
//
// if (isset($_POST['typeAuteur'])) {
//     $query["idTypeAuteur"] = $_POST['typeAuteur'];
// }
    // Creation du nouvel objet//
    $citation = new Citation($query['body']['contenuCitation'],$query['body']['dateCitation'],$query['body']['auteurCitation'],$query['body']['idTypeAuteur']);

    ////// ADD TO DB //////
    $queryStmt = "INSERT INTO Citations (contenuCitation, dateCitation, auteurCitation, idTypeAuteur) VALUES (?, ?, ?, ?);"

    $stmt = MyPDO::getInstance()->prepare($queryStmt);

    $stmt->bindValue(1, $citation->getContenu());
    $stmt->bindValue(2, $citation->getDate());
    $stmt->bindValue(3, $citation->getAuteur());
    $stmt->bindValue(4, $citation->getTypeAuteur());

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
public function apiGetAllCitations(HttpRequest $query){
  // check HTTP method //
  $method = strtolower($query['method']);

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

public function apiGetCitationById(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']);
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit();
}

// VERIF//
if (isset($_GET['idCitation'])) {
    $query['idcitation'] = $_GET['idCitation'];
}
else {
	http_response_code(404);
  echo json_encode("No ID provided.");
  exit();
}

$queryStmt = "SELECT * FROM S2_Citations WHERE S2_Citations.idCitation = :idcitation LIMIT 1;"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['idcitation' => $query['body']['idCitation']]);

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
public function apiGetCitationByTags(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']);
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit();
}


$tagsList = implode(',', array_fill(0, count($query['body']['tags']), '?'));


$queryStmt = "SELECT * FROM S2_Citations
  INNER JOIN S2_TagCitations ON S2_TagCitation.idCitation = S2_Tags.idCitation
  INNER JOIN S2_Tags ON S2_Tags.idTags = S2_TagCitation.idTag
  WHERE S2_Tags.nomTag IN ($tagsList);"

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
public function apiGetCitationByKeyword(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']);
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit();
}

$queryStmt = "SELECT * FROM S2_Citations WHERE contenuCitation LIKE %:keyword% ORDER BY dateCitation;"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(['keyword' => $query['body']['keyWord']]);

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
public function apiGetCitationByTypeAuteur(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}

$typesList = implode(',', array_fill(0, count($query['body']['typesauteur']), '?'));

$queryStmt = "SELECT * FROM S2_Citations
  WHERE S2_Citations.idTypeAuteur IN ($typesList);"

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
public function apiGetCitationByAll(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}


$tagsList = implode(',', array_fill(0, count($query['body']['tags']), '?'));
$typesList = implode(',', array_fill(0, count($query['body']['typesauteur']), '?'));

$queryStmt = "SELECT *
  FROM S2_Citations
  JOIN S2_TagCitation ON S2_Citations.idCitation=S2_TagCitation.idCitation
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN ($tagslist) AND S2_Citations.idTypeAuteur IN ($typesList) AND S2_Citation.contenuCitation LIKE %:keyword%;"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(':keyword' => $query['body']["keyWwrd"]);

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
public function apiGetCitationByTagsAndKeyword(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}


$tagsList = implode(',', array_fill(0, count($query['body']['tags']), '?'));

$queryStmt = "SELECT *
  FROM S2_Citations
  JOIN S2_TagCitation ON S2_Citations.idCitation=S2_TagCitation.idCitation
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN ($tagslist) AND S2_Citation.contenuCitation LIKE %:keyword%;"


$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(':keyword' => $query['body']["keyword"]);

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
public function apiGetCitationByTypeAuteurAndKeyword(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}

$typesList = implode(',', array_fill(0, count($query['body']['typesauteur']), '?'));

$queryStmt = "SELECT *
  FROM S2_Citations
  WHERE S2_Citations.idTypeAuteur IN ($typesList) AND S2_Citation.contenuCitation LIKE %:keyword%;"


$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(':keyword' => $query['body']["keyword"]);


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

public function apiGetCitationByTypeAuteurAndTags(HttpRequest $query){
  // check HTTP method //
$method = strtolower($query['method']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}

$tagsList = implode(',', array_fill(0, count($query['body']['tags']), '?'));
$typesList = implode(',', array_fill(0, count($query['body']['typesauteur']), '?'));

$queryStmt = "SELECT *
  FROM S2_Citations
  JOIN S2_TagCitation ON S2_Citations.idCitation=S2_TagCitation.idCitation
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN ($tagslist) AND S2_Citations.idTypeAuteur IN ($typesList);"


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

public static function apiUpdateCitation(HttpRequest $query)
  {

    // check HTTP method //
  $method = strtolower($query['method']); // Je verifie si c'est bien un get
  if ($method !== 'put') {
      http_response_code(405);
      echo json_encode(array('message' => 'This method is not allowed.'));
      exit(); // SInon je sors
  }

    if (isset($_PUT['contenu'])) {
      var_dump($_PUT['contenu']));
        // $query['body']["contenuCitation"] = $_PUT['contenu'];
    }

    if (isset($_PUT['auteur'])) {
      var_dump($_PUT['auteur']));
        // $query['body']["auteurCitation"] = $_PUT['auteur'];
    }

    if (isset($_PUT['typeAuteur'])) {
      var_dump($_PUT['typeAuteur']));
        // $query['body']["idTypeAuteur"] = $_PUT['typeAuteur'];
    }
    // Ajouter les else --> même valeur

    $queryStmt = "UPDATE S2_Citations SET contenuCitation = :contenu, auteurCitation = :auteur, idTypeAuteur = :typeauteur WHERE idCitation = :id";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(
      array(
        ':contenu' => $query['body']["contenuCitation"],
        ':auteur' => $query['body']["auteurCitation"],
        ':typeauteur'=> $query['body']["idTypeAuteur"],
        ':id'=>$query['body']["idCitation"]
      )
    );

    if ($stmt->rowCount() == 0) {
      return NULL;
    }
  }



////////////////////////////////////////////////////////////////
///////////////////////////// DELETE //////////////////////////
//////////////////////////////////////////////////////////////

public static function apiDeleteCitation(HttpRequest $query)
  {

    // check HTTP method //
  $method = strtolower($query['method']); // Je verifie si c'est bien un get
  if ($method !== 'delete') {
      http_response_code(405);
      echo json_encode(array('message' => 'This method is not allowed.'));
      exit(); // SInon je sors
  }

    $queryStmt1 = "DELETE FROM S2_TagCitation WHERE idCitation = :idcitation";

    $stmt1 = MyPDO::getInstance()->prepare($queryStmt1);
    $stmt1->execute(['idcitation' => $query['body']['idCitation']);

    $queryStmt2 = "DELETE FROM S2_Citations WHERE idCitation = :idcitation";

    $stmt2 = MyPDO::getInstance()->prepare($queryStmt2);
    $stmt2->execute(['idcitation' => $query['body']['idCitation']);

    if ($stmt2->rowCount() == 0) {
      return NULL;
    }
  }




    ////////////////////////////////////////////////////////////////
    ///////////////////////////// OTHER //////////////////////////
    //////////////////////////////////////////////////////////////


//Verif mot de passe
public static function verifMdp($string){

}

// check HTTP method //
$method = strtolower($query['method']); // Je verifie si c'est bien un get
if ($method !== 'get') {
  http_response_code(405);
  echo json_encode(array('message' => 'This method is not allowed.'));
  exit(); // SInon je sors
}

public static function getCitationLikes($query)
  {


    $queryStmt = "SELECT likesCitation FROM S2_Citations WHERE idCitation = :idCitation";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute('idCitation'=>$query['body']['idCitation']);

    $likes = $stmt->fetch();
    echo $likes;
    exit();
    }
  }

// Update likes citations
    public static function updateCitationLikes($query)
      {

        // check HTTP method //
      $method = strtolower($query['method']); // Je verifie si c'est bien un get
      if ($method !== 'put') {
          http_response_code(405);
          echo json_encode(array('message' => 'This method is not allowed.'));
          exit(); // SInon je sors
      }

        $queryStmt = "UPDATE S2_Citations SET likesCitation= :newlikes WHERE idCitation = :id";

        $stmt = MyPDO::getInstance()->prepare($queryStmt);
        $stmt->execute(
          array(
            'newlikes' =>$query['body']['likes'],
            'id'=>$query['body']['idCitation']
          )
        );

        if ($stmt->rowCount() == 0) {
          return NULL;
        }
      }

  ////////////////////////////////////////////////////////////////
  ///////////////////////////// ERROR //////////////////////////
  //////////////////////////////////////////////////////////////

  public static function throwAnError($query)
   {
     echo json_encode("An error occured. \n" $query);
     http_response_code(500);
     exit();
   }


?>
