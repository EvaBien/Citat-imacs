<?php

require_once 'MyPDO.class.php';
if ($_SERVER['SERVER_NAME']=="localhost"){
  MyPDO::setConfiguration('mysql:host=localhost;dbname=citatimac;charset=utf8', 'root', '');

} else {
  MyPDO::setConfiguration('mysql:host=etudiant.u-pem.fr;dbname=akohlmul_db;charset=utf8', 'akohlmul', 'BDBwait2see');
}
?>
