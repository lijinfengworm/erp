<?php
require_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once sfConfig::get('sf_symfony_lib_dir').'/hc/API/QueryUser.class.php';

$t = new lime_test(9);

$t->diag('__construct');
$query = new QueryUser('imikay');
$t->ok(is_object($query), 'Contruct QueryUser object succefully with username[imikay]');

$t->diag('->getUserId()');
$userId = $query->getUserId();
$t->is($userId, 5291804, 'Got user ID. and user id is '.$userId);

$t->diag('->exists()');
$userExists = $query->exists();
$t->cmp_ok($userExists, '===', true, 'User exists.');

$t->diag('Contruct object with Chinese name');
$query = new QueryUser('饿虎扑食啦');
$t->ok(is_object($query), 'Contruct QueryUser object with Chinese name succefully with username[饿虎扑食啦]');

$t->diag('->getUserId() with Chinese name.');
$userId = $query->getUserId();
$t->is($userId, 5366307, 'Got user ID. and user id is '.$userId);

$t->diag('->exists() with Chinese name');
$userExists = $query->exists();
$t->cmp_ok($userExists, '===', true, 'User exists.');

$t->diag('-------------- Nonexist user test ------------');

$t->diag('__construct');
$query = new QueryUser('nonexistuser');
$t->ok(is_object($query), 'Contruct QueryUser object succefully with username[nonexistuser]');

$t->diag('->getUserId()');
$userId = $query->getUserId();
$t->is($userId, 0, 'User id is '.$userId);

$t->diag('->exists()');
$userExists = $query->exists();
$t->cmp_ok($userExists, '===', false, 'User does not exist.');