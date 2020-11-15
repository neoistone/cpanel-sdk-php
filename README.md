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
 $username = $_GET['username'];
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
$username = $_GET['username'];
$reson = $_GET['reson'];
echo json_encode($WHM->suspend_account($username,$reson));
?>
```
### unsuspend Accound ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
$username = $_GET['username'];
echo json_encode($WHM->unsuspend_account($username));
?>
```
### Change password ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
$username = $_GET['username'];
$password = $_GET['password'];
echo json_encode($WHM->change_password_account($username,$password));
?>
```
### List Account ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
$search_type = ""; // domain,username,ip,package
$search_word = ""; // example username (testuser) or ip (1.1.1.1) or domain (example.com) or package (pkg1) 
echo json_encode($WHM->list_accounts($search_type,$search_word));
?>
```
### set bandwidth user ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
$username = $_GET['username'];
$bwt = $_GET['']; //bwt in MB
echo json_encode($WHM->limit_user_bandwidth($username,$bwt));
?>
```

### get all list packages ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
echo json_encode($WHM->list_packages());
?>
```


### delete package ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
$username = $_GET['username'];
echo json_encode($WHM->delete_package($username));
?>
```

### show load average ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
echo json_encode($WHM->show_load_avg());
?>
```

### Get server hostname ###
```
<?php
include_once('vendor/autoload.php');
$WHM = new WHM( false , '1.1.1.1' , 'root' , 'pass' );
echo json_encode($WHM->get_host_name());
?>
```
