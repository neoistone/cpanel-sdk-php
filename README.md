# cpanel_sdk

``Creating Cpanel User```
```
<?php
 include __DIR__.'/class.whm.php';
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
