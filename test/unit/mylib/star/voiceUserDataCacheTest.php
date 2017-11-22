<?php
require_once dirname(__FILE__) . '/../../../bootstrap/doctrine.php';
$t = new lime_test(6);
$t->diag('Unit test for: voiceUserDataCache');

$t->diag('Test: __construct');
$cache = new voiceUserDataCache();
$t->ok($cache->cache instanceof Memcache, 'class voiceUserDataCache instance a memcache object');

$t->diag('Test: get() and set()');
$cache->set('foo', 'bar');
$t->is($cache->get('foo'), 'bar', 'class voiceUserDataCache set a key and get its value');

$cache->set('test', 'test value', -10);
$t->is($cache->get('test'), null, 'class voiceUserDataCache return null when get a has expired key');

$t->is($cache->get('notexist'), null, 'class voiceUserDataCache return null when get a not expired key');

$t->diag('Test: getUserFollows() and setUserFollows()');
$cache->set('sf_voice_user_follows_1', null, -10);
$t->isa_ok($cache->getUserFollows(1), 'array', '->getUserFollows() return the users follows array');

$cache->setUserFollows(2, array());
$t->isa_ok($cache->getUserFollows(2), 'array', '->setUserFollows() set an empty array');


