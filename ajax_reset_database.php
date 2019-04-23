<?php
/*
reset db
*/
$responsearr['status'] = "";
$responsearr['response'] = "";

require_once('config.php');
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$dbPDO = new PDO(	'pgsql:dbname='.$config['db_data'].
								';host='.$config['db_host'].
								';user='.$config['db_user'].
								';password='.$config['db_pass']
								,NULL,NULL,$opt);
$dbstmt = $dbPDO->query(file_get_contents('new_database.sql'));
$results = $dbstmt->fetchAll(PDO::FETCH_ASSOC);
$responsearr['status'] = $dbstmt->errorInfo();
$responsearr['response'] = $results;
if (isset($_GET['debug']))
    var_dump($responsearr);
else {
    header('Content-Type: application/json');
    echo json_encode($responsearr);
}
 ?>
