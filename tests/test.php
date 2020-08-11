<?php
require_once( '../src/class.whm.php' );

#* https://1.1.1.1
$WHM = new WHM( true , 'hostname or ip' , 'root' , 'password or hash key' );

#* http://1.1.1.1:
# $WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );


//<!--- creating user ssesion---> \\
//echo $WHM->login_with_user();

//<!--- get all list account ---> \\
//echo $WHM->list_accounts()['0']['suspendreason'];


//<!--- creating account ---> \\
/*
$domain = $_GET['domain'];
$email = $_GET['email'];
$package = $_GET['package'];

$data = array();
$data['domain'] = $domain;
$data['email'] = $email;
$data['package'] = $package;

print_r($WHM->create_account($data));
*/

//<!--- remove account ---> \\
/*
$username = $_GET['id'];

$res = $WHM->delete_account($username);
if($res['0'] == null){
	echo $res['1'];
} else {
	echo 'account remove sussfully';
}
*/
/*$username = $_GET['id']
echo $WHM->suspend_account($username,'test');
*/
?>
