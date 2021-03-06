<?php
require dirname(__FILE__).'/../externals/testmore-php/testmore.php';
require(dirname(__FILE__).'/../config.inc.php');

plan('no_plan');
require_ok(dirname(__FILE__).'/../src/OpenErpOcs.php');

$time = time();
diag($openerp_server);
diag("time = $time");
diag("username = $username");
diag("db = $dbname");

class myOpenErpPqr extends OpenErpPqr {
    protected $create_operation_name = 'new_from_data';

    protected function processAttributes() {
    }
}

$c = new OpenErpWebServiceClient($openerp_server, $username, $pwd, $dbname);

$data = array (
  'partner_address_id' =>
  array (
    'name' => $time,
    'last_name' => $time,
    'document_type' => 'CC',
    'document_number' => $time,
    'email' => $time.'@my.email.com',
    'gender' => 'f',
  ),
  'categ_id' => 1,
  'classification_id' => 1,
  'sub_classification_id' => 21,
  'csp_id' => 1,
  'channel' => 5,
  'orfeo_id' => '0',
  'priority' => 'l',
  'state' => 'pending',
  'description' => 'testing',
  'damage_type_by_citizen' => 'hundimiento',
  'damage_width_by_citizen' => 'ns-nr',
  'damage_length_by_citizen' => 'ns-nr',
  'damage_deep_by_citizen' => 'ns-nr',
  'geo_point' => '{"type":"Point","coordinates":[-8246435.1410983,512561.2012486]}',
  'email_from' => $time.'@my.email.com',
);

$result = $c->execute('crm.claim', 'new_from_data', $data);
//var_export($result);
ok($result['status'] == 'success', 'Success');
ok($result['result']['id'] > 0, 'Object Created');

$new_pqr = new myOpenErpPqr($c);
$new_pqr->attributes = $data;
$result = $new_pqr->create();
// var_export($result);
ok($result['status'] == 'success', 'Success');
ok($result['result']['id'] > 0, 'Object Created');

$data = array (
  'partner_address_id' =>
  array (
    'name' => $time,
    'last_name' => $time,
    'document_type' => 'CC',
    'document_number' => $time,
    'email' => $time.'@my.email.com',
    'gender' => 'f',
  ),
  'categ_id' => 'queja',
  'sub_classification_id' => 'Malla Vial Arterial',
  'csp_id' => 1,
  'channel' => 'chat',
  'orfeo_id' => '0',
  'priority' => 'l',
  'state' => 'pending',
  'description' => 'testing with names instead of IDs',
  'email_from' => $time.'@my.email.com',
);

$new_pqr->attributes = $data;
$result = $new_pqr->create();
//var_export($result);
ok($result['status'] == 'success', 'Success');
ok($result['result']['id'] > 0, 'Object Created');

$data = array (
  'categ_id' => 'queja',
  'sub_classification_id' => 'Malla Vial Arterial',
  'csp_id' => 1,
  'channel' => 'chat',
  'orfeo_id' => '0',
  'priority' => 'l',
  'state' => 'pending',
  'description' => 'testing with names instead of IDs',
  'email_from' => $time.'@my.email.com',
);

$new_pqr->attributes = $data;
$result = $new_pqr->create();
//var_export($result);
ok($result['status'] == 'success', 'Success');
ok($result['result']['id'] > 0, 'Object Created');

$pqr_load = new myOpenErpPqr($c);
$pqr_load->loadOne($result['result']['id']);
ok(!empty($pqr_load->attributes), 'Data retrieved');

//Attach a file
$file_encode64 = base64_encode(file_get_contents(__FILE__));
$data = array (
  'categ_id' => 'queja',
  'sub_classification_id' => 'Malla Vial Arterial',
  'csp_id' => 1,
  'channel' => 'chat',
  'orfeo_id' => '0',
  'priority' => 'l',
  'state' => 'pending',
  'description' => 'testing with attachment',
  'attachment' => $file_encode64,
  'attachment_name' => "$time.txt",
);

$new_pqr = new myOpenErpPqr($c);
$new_pqr->attributes = $data;
$result = $new_pqr->create();
//var_export($result);
ok($result['status'] == 'success', 'Success');
ok($result['result']['id'] > 0, 'Object Created');
