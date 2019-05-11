<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelCitations.php';
require '../model/ModelTagCitation.php';
require '../model/ModelTypesAuteur.php';



////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////


//URL - POST : citations?new
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
//URL - GET : citations?all
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

//URL - GET : citations?id="id"
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
  $citation = "Cannot found movie with id {$query['idCitation']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();


}

////////////////////// GET CITATION BY TAGS ///////////////////
//URL - GET : citations?tags="tags"
public function apiGetCitationByTags(HttpRequest $request){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit();
}
$tagsList =''; // Je fais une liste avec mes tags
foreach ($query['Tags'] as $tag){ // Pour chaque tag dans la requête
  $tagsList+=$tag["nomTag"].', '; // Je l'ajoute à ma liste en les séparant d'une ,
}
$tagsList=substr($tagsList,-2) // J'enlève le virgule + espace à la fin

$queryStmt = "SELECT * FROM S2_Citations
  INNER JOIN S2_TagCitations ON S2_TagCitation.idCitation = S2_Tags.idCitation
  INNER JOIN S2_Tags ON S2_Tags.idTags = S2_TagCitation.idTag
  WHERE S2_Tags.nomTag IN $tagsList;"

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
  $citation = "Cannot found movie with tags {$query['Tags']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}


//////////////////// GET CITATION BY KEYWORD /////////////////
//URL - GET : citations?keyword="keyword"
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
  $citation = "Cannot found movie with keyword {$query['keyWord']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}

////////////////////// GET CITATION BY TYPEAUTEUR ///////////////////
//URL - GET : citations?typesAuteur="types"
public function apiGetCitationByTypeAuteur(HttpRequest $query){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}
$typesList =''; 
foreach ($query['typesAuteur'] as $type){
  $typesList.=$type["idTypeAuteur"].', ';
}
$typesList=substr($typesList,-2);


$queryStmt = "SELECT * FROM S2_Citations
  WHERE S2_Citations.idTypeAuteur IN $typesList;"

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
  $citation = "Cannot found movie with keyword {$query['keyWord']}.";
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
//URL - GET : citations?keyword="keyword"&tags="tags"&typesAuteur="types"
public function apiGetCitationByAll(HttpRequest $query){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}

$tagsList =''; // Je fais une liste avec mes tags
foreach ($query['Tags'] as $tag){ // Pour chaque tag dans la requête
  $tagsList+=$tag["nomTag"].', '; // Je l'ajoute à ma liste en les séparant d'une ,
}
$tagsList=substr($tagsList,-2) // J'enlève le virgule + espace à la fin

$typesList =''; 
foreach ($query['typesAuteur'] as $type){
  $typesList.=$type["idTypeAuteur"].', ';
}
$typesList=substr($typesList,-2);

$queryStmt = "SELECT * FROM S2_Citations
WHERE idCitation IN (
  SELECT idCitation FROM S2_TagCitation 
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN :tags
)
AND WHERE S2_Citations.idTypeAuteur IN :auteurList
AND WHERE contenuCitation LIKE %:keyword%"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(
      array(
        ':keyword' => $query["keyWord"],
        ':tags' => $tagsList,
        ':auteurList' => $typesList
      )
);

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
  $citation = "Cannot found movie with keyword {$query['keyWord']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}

//////////////////// GET BY KEYWORD & TAGS ////////////////////
//URL - GET : citations?keyword="keyword"&tags="tags"
public function apiGetCitationByTagsAndKeyword(HttpRequest $query){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}

$tagsList =''; // Je fais une liste avec mes tags
foreach ($query['Tags'] as $tag){ // Pour chaque tag dans la requête
  $tagsList+=$tag["nomTag"].', '; // Je l'ajoute à ma liste en les séparant d'une ,
}
$tagsList=substr($tagsList,-2) // J'enlève le virgule + espace à la fin


$queryStmt = "SELECT * FROM S2_Citations
WHERE idCitation IN (
  SELECT idCitation FROM S2_TagCitation 
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN :tags
)
AND WHERE contenuCitation LIKE %:keyword%"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(
      array(
        ':keyword' => $query["keyWord"],
        ':tags' => $tagsList
      )
);

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
  $citation = "Cannot found movie with keyword {$query['keyWord']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}


////////////////// GET BY KEYWORD & TYPEAUTEUR ///////////////
//URL - GET : citations?keyword="keyword"&typesAuteur="types"
public function apiGetCitationByTypeAuteurAndKeyword(HttpRequest $query){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}
$typesList =''; 
foreach ($query['typesAuteur'] as $type){
  $typesList.=$type["idTypeAuteur"].', ';
}
$typesList=substr($typesList,-2);


$queryStmt = "SELECT * FROM S2_Citations
  WHERE S2_Citations.idTypeAuteur IN :typeAuteur AND S2_Citations.contenuCitation LIKE %:keyword%;"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(
      array(
        ':keyword' => $query["keyWord"],
        ':typeAuteur' => $typesList
      )
);

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
  $citation = "Cannot found movie with keyword {$query['keyWord']}.";
}
else {
  http_response_code(200);
}

echo json_encode($citation);
exit();
}



////////////////// GET BY  TAGS & TYPEAUTEUR ///////////////
//URL - GET : citations?tags="tags"&typesAuteur="types"

public function apiGetCitationByTypeAuteurAndTags(HttpRequest $query){
  // check HTTP method //
$method = strtolower($_SERVER['REQUEST_METHOD']); // Je verifie si c'est bien un get
if ($method !== 'get') {
    http_response_code(405);
    echo json_encode(array('message' => 'This method is not allowed.'));
    exit(); // SInon je sors
}
$typesList =''; 
foreach ($query['typesAuteur'] as $type){
  $typesList.=$type["idTypeAuteur"].', ';
}
$typesList=substr($typesList,-2);

$tagsList =''; // Je fais une liste avec mes tags
foreach ($query['Tags'] as $tag){ // Pour chaque tag dans la requête
  $tagsList+=$tag["nomTag"].', '; // Je l'ajoute à ma liste en les séparant d'une ,
}
$tagsList=substr($tagsList,-2) // J'enlève le virgule + espace à la fin


$queryStmt = "SELECT * FROM S2_Citations
WHERE idCitation IN (
  SELECT idCitation FROM S2_TagCitation 
  JOIN S2_Tags ON S2_TagCitation.idTag = S2_Tags.idTag
  WHERE S2_Tags.nomTags IN :tags
)
AND WHERE idTypeAuteur IN :typeAuteur"

$citations = array();
$stmt = MyPDO::getInstance()->prepare($queryStmt);

$stmt->execute(
      array(
        ':tags' => $tagsList,
        ':typeAuteur' => $typesList
      )
);

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
  $citation = "Cannot found movie with keyword {$query['keyWord']}.";
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

//URL - PUT : citations?id="id"
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

//URL - DELETE : citations?id="id"
public static function apiDeleteCitation(HTTPRequest $request)
  {

    $queryStmt1 = "DELETE FROM S2_TagCitation WHERE idCitation = :idcitation";

    $stmt1 = MyPDO::getInstance()->prepare($queryStmt1);
    $stmt1->execute(['idcitation' => $query['idCitation']);

    $queryStmt2 = "DELETE FROM S2_Citations WHERE idCitation = :idcitation";

    $stmt2 = MyPDO::getInstance()->prepare($queryStmt2);
    $stmt2->execute(['idcitation' => $query['idCitation']);

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


//URL - GET : citations?id="id"
public static function GetCitationLikes($id)
  {

    $queryStmt = "SELECT likesCitation FROM S2_Citations WHERE idCitation = $id";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute();

    exit();
    }
  }

// Update likes citations
//URL - PUT : citations?id="id"
    public static function UpdateCitationLikes($id, $likes)
      {

        $queryStmt = "UPDATE S2_Citations SET likesCitation= $likes WHERE idCitation = $id";

        $stmt = MyPDO::getInstance()->prepare($queryStmt);
        $stmt->execute();

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
