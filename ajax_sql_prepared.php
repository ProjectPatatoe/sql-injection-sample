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
    								,NULL,NULL,$opt);

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

if (isset($_GET['debug']))
    var_dump($responsearr);
else {
    echo json_encode($responsearr);
}
 ?>
