<?php
require_once dirname(__FILE__) . '/../../../bootstrap/doctrine.php';
$t = new lime_test(27);
$t->diag('Unit test for: twitterUserFollowTable');

/*
 * delete test data
 */
twitterUserFollowTable::getInstance()->createQuery('t')
        ->delete()
        ->whereIn('t.user_id', array(1,2,3,4, 5010678))
        ->execute();

/*
 * getInstance()
 */
$t->isa_ok(twitterUserFollowTable::getInstance(), 'twitterUserFollowTable', 'function getInstance() return a twitterUserFollowTable object');
/*
 * getFollowMessages()
 */

$t->isa_ok(twitterUserFollowTable::getFollowMessages(5010678), 'Doctrine_Collection', '::getFollowMessages() return Doctrine_Collection object');
voiceUserData::clearUserFollows();
twitterUserFollowTable::clearUserFollowObject();

/*
 * getUserFollowsObject()
 */
$u = new twitterUserFollow();
$u->setUserId(1);
$u->setTwitterUserIds('1234567890');
$u->save();
$user = twitterUserFollowTable::getUserFollowsObject(1);
$t->is($user->getTwitterUserIds(), '1234567890', '::getUserFollowsObject() return twitterUserFollow Object that exists in the db');
voiceUserData::clearUserFollows();
twitterUserFollowTable::clearUserFollowObject();
$u = twitterUserFollowTable::getUserFollowsObject(5010678);
$t->isa_ok($u, 'twitterUserFollow', '::getUserFollowsObject() create a object return twitterUserFollow Object when the user does not exist in the db');
$t->isa_ok($u->getTwitterTagIds(), 'string', '::getUserFollowsObject() auto follow user\' favor teams');

/*
 * getUserFollows()
 */
$f = twitterUserFollowTable::getUserFollows(5010678);

$t->isa_ok($f, 'array', '::getUserFollows() return an array for an exists user');

$t->is((count($f) ==3 && isset($f['users']) && isset($f['tags']) && isset($f['teams'])), true, '::getUserFollows() return an array that who length is 3 and contain users, tags and teams for an exists user');
voiceUserData::clearUserFollows();
twitterUserFollowTable::clearUserFollowObject();
$f = twitterUserFollowTable::getUserFollows(2);
$t->isa_ok($f, 'array', '::getUserFollows() return an array for an exists user');
$t->is((count($f) ==3 && isset($f['users']) && isset($f['tags']) && isset($f['teams'])), true, '::getUserFollows() return an array that who length is 3 and contain users, tags and teams for an doesn\'t exist user');

/*
 * follow()
 */
voiceUserData::clearUserFollows();
twitterUserFollowTable::clearUserFollowObject();
$t->is(twitterUserFollowTable::follow(3, 'tag', 7), 1, '::follow() can follow a tag and return 1 for a does not exist user');
$u = twitterUserFollowTable::getUserFollowsObject(3);
$t->is(in_array(7, $u->getTwitterTagIdsArr()), true, '::follow() follows tag 7 success');
$t->is(twitterUserFollowTable::follow(3, 'tag', 7), 0, '::follow() can\'t follow a tag that has followed and return 0');
$t->is(twitterUserFollowTable::follow(3, 'tag', 1), 0, '::follow() can\'t follow a tag that is not a root tag');
$t->is(twitterUserFollowTable::follow(3, 'tag', 100000), 0, '::follow() can\'t follow a tag that does not exist and return 0');

voiceUserData::clearUserFollows();
twitterUserFollowTable::clearUserFollowObject();
$t->is(twitterUserFollowTable::follow(4, 'user', 593), 1, '::follow() can follow a user and return 1 for a does not exist user');
$u = twitterUserFollowTable::getUserFollowsObject(4);
$t->is(in_array(593, $u->getTwitterUserIdsArr()), true, '::follow() follows user 593 success');
$t->is(twitterUserFollowTable::follow(4, 'user', 593), 0, '::follow() can\'t follow a user that has followed');
$t->is(twitterUserFollowTable::follow(4, 'user', 100000000), 0, '::follow() can\'t follow a user that does not exist');

/*
 * disfollowTag()
 */
voiceUserData::clearUserFollows();
twitterUserFollowTable::clearUserFollowObject();
$t->is(twitterUserFollowTable::disfollow(3, 'tag', 7), 1, '::disfollow() can unfollow a tag and return 1');
$u = twitterUserFollowTable::getUserFollowsObject(3);
$t->is(!in_array(7, $u->getTwitterUserIdsArr()), 1, '::disfollow() unfollow tag 7');
$t->is(twitterUserFollowTable::disfollow(3, 'tag', 7), 0, '::disfollow() can\'t unfollow a tag the user has\'t followed');
$t->is(twitterUserFollowTable::disfollow(3, 'tag', 1), 0, '::disfollow() can\'t unfollow a tag which is a root tag');
$t->is(twitterUserFollowTable::disfollow(3, 'tag', 10000000), 0, '::disfollow() can\'t unfollow a tag which isn\'t exist');

voiceUserData::clearUserFollows();
twitterUserFollowTable::clearUserFollowObject();
$t->is(twitterUserFollowTable::disfollow(4, 'user', 593), 1, '::disfollow() can follow a user and return 1');
$u = twitterUserFollowTable::getUserFollowsObject(4);
$t->is(!in_array(593, $u->getTwitterUserIdsArr()), true, '::disfollow() follows user 593 success');
$t->is(twitterUserFollowTable::disfollow(4, 'user', 593), 0, '::disfollow() can\'t disfollow a user that has not followed');
$t->is(twitterUserFollowTable::disfollow(4, 'user', 100000000), 0, '::disfollow() can\'t disfollow a user that does not exist');