<?php

$responsearr['status'] = "";
$responsearr['response'] = "";

if (isset($_POST['input']))
{
    require_once('config.php');
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    $dbPDO = new PDO(	'pgsql:dbname='.$config['db_data'].
    								';host='.$config['db_host'].
    								';user='.$config['db_user'].
    								';password='.$config['db_pass']
    								,$opt);

        $stmt = $dbPDO->query(" SELECT *
                                WHERE somenumber=".$_POST['input'].";");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $responsearr['status'] = $dbstmt->errorInfo();
        $responsearr['response'] = $results;

}
else {
    $reponsearr['status'] = 0;
    $responsearr['response'] = "NO INPUT!";
}
if (isset($_GET['debug']))
    var_dump($responsearr);
else {
    echo json_encode($responsearr);
}
 ?>
