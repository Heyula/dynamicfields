<?php
$modversion['name'] = 'Dinamik Özel Alanlar';
$modversion['version'] = '1.0';
$modversion['description'] = 'Dinamik özel alanlar oluşturma modülü';
$modversion['author'] = 'Senin Adın';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['dirname'] = 'dynamicfields';$modversion['image'] = 'assets/images/logoModule.png';
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/customfields.php';
$modversion['hasMain'] = 1;
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = 'dynamicfields_fields';
$modversion['templates'][0]['file'] = 'dynamicfields_form.html';
$modversion['templates'][0]['description'] = 'Özel alan form şablonu';
$modversion['templates'][1]['file'] = 'dynamicfields_display.html';
$modversion['templates'][1]['description'] = 'Özel alan görüntüleme şablonu';
?>