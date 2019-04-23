<?php
/*
https://dba.stackexchange.com/questions/44729/to-select-from-all-tables-inside-the-schema-in-postgresql
FOR i IN SELECT table_name
           FROM information_schema.tables
          WHERE table_schema = 'your_desired_schema'
LOOP
    sql_string := sql_string || format($$
        -- some whitespace is mandatory here
        UNION
        SELECT name FROM %I
    $$,
    i.table_name);
END LOOP;

EXECUTE sql_string;
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
//list tables
	$dbstmt = $dbPDO->query('
                        SELECT *
                      FROM information_schema.tables
                      WHERE table_type = \'BASE TABLE\'
                      AND table_schema = \'public\'
                      ORDER BY table_type, table_name
	                ');
	$results = $dbstmt->fetchAll(PDO::FETCH_ASSOC);
	$responsearr['status'] = $dbstmt->errorInfo();
	$responsearr['response']['tablelist'] = $results;
//table contents
$dbstmt2 = $dbPDO->prepare('  SELECT *
                              FROM ?;');
for ($idx = 0; $idx < count($responsearr['response']['tablelist']);++$idx)
{
  $dbstmt2->execute(array($responsearr['response']['tablelist']['table_name']));
  $responsearr['response']['tables'][ $responsearr['response']['tablelist']['table_name'] ] = $dbstmt2->fetchAll();
}

if (isset($_POST['debug']))
    var_dump($responsearr);
else {
    header('Content-Type: application/json');
    echo json_encode($responsearr);
}
 ?>
