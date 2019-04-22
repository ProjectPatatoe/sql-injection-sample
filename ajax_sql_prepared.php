<?php

$responsearr['status'] = "";
$responsearr['response'] = "";

if (isset($_POST['input']))
{
    require_once('config.php');
    $dbPDO = new PDO(	'pgsql:dbname='.$config['db_data'].
    								';host='.$config['db_host'].
    								';user='.$config['db_user'].
    								';password='.$config['db_pass']
    								);
    if ($dbPDO)
    {
        $stmt = $dbPDO->prepare(" SELECT *
                                WHERE somenumber=:input;");
        $stmt->bindParam(':input',$_POST['input']);
        if ($stmt->execute())
        {
            $responsearr['status'] = 1;
            $responsearr['response'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            $responsearr['status'] = 0;
            $responsearr['response'] = "execute error!";
        }
    }
    else {
            $responsearr['status'] = 0;
            $responsearr['response'] = "PDO not connected";
        }
}
else {
    $responsearr['status'] = 0;
    $responsearr['response'] = "NO INPUT!";
}
if (isset($_GET['debug']))
    var_dump($responsearr);
else {
    echo json_encode($responsearr);
}
 ?>
