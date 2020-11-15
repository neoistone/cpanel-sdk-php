# cpanel sdk php

## installtion ###
```
composer require "neoistone/cpanel-sdk-php @dev"
```

### Creating Cpanel User ###
```
<?php
include_once('vendor/autoload.php');;
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
$domain = $_GET['domain'];
$email = $_GET['email'];
$package = $_GET['package'];

$data = array();
$data['domain'] = $domain;
$data['email'] = $email;
$data['package'] = $package;

print_r($WHM->create_account($data));
?>
```

### Creating User Ssesion ###
```
<?php
include_once('vendor/autoload.php');
$username = $_GET['username'];
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
echo $WHM->login_with_user($username);
?>
```
RETURN login url

### remove account ###
```
<?php
include_once('vendor/autoload.php');
 $WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
 $username = $_GET['id'];
 $res = $WHM->delete_account($username);
 if($res['0'] == null){
	   echo $res['1'];
 } else {
	  echo 'account remove sussfully';
 }
?>
```
### suspend Accound ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
$username = $_GET['id'];
echo $WHM->suspend_account($username,'test');
?>
```
