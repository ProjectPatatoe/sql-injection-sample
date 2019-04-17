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
$dbPDO = new PDO(	'pgsql:dbname='.$config['db_data'].
								';host='.$config['db_host'].
								';user='.$config['db_user'].
								';password='.$config['db_pass']
								);
$dbstmt = $dbPDO->query('
                FOR i IN SELECT table_name
                           FROM information_schema.tables
                          WHERE table_schema = \'your_desired_schema\'
                LOOP
                    sql_string := sql_string || format($$
                        -- some whitespace is mandatory here
                        UNION
                        SELECT name FROM %I
                    $$,
                    i.table_name);
                END LOOP;

                EXECUTE sql_string;
                ');
$results = $dbstmt->fetchAll(PDO::FETCH_ASSOC);
$responsearr['status'] = $dbstmt->errorInfo();
$responsearr['response'] = $results;
if (isset($_POST['debug']))
    var_dump($responsearr);
else {
    echo json_encode($responsearr);
}
 ?>
