<?php
require_once dirname(__FILE__) . '/../../../bootstrap/doctrine.php';
$t = new lime_test(14);
$t->diag('Unit test for: voiceUserData');

/*
 * delete 
 */
twitterUserFollowTable::getInstance()->createQuery('u')
        ->delete()
        ->whereIn('u.user_id', array(1,2))
        ->execute();

$t->diag('Test: voiceUserData::getCache()');
$t->isa_ok(voiceUserData::getCache(), 'voiceUserDataCache', '::getCache() return an object');

$t->diag('Test: voiceUserData::getUserFollows()');
$userFollows = voiceUserData::getUserFollows(1);
$t->isa_ok($userFollows, 'array', '::getUserFollows() return an array');
$t->ok((count($userFollows) == 3 && isset($userFollows['users']) && isset($userFollows['tags']) && isset($userFollows['teams'])), '::getUserFollows() return an array whoes length  is 3 and with keys: users, tags and teams');

$t->diag('Test: voiceUserData::setUserFollows()');
voiceUserData::clearUserFollows();
twitterUserFollowTable::getInstance()->createQuery('u')
        ->delete()
        ->where('u.user_id = 2');
$user2 = new twitterUserFollow();
$user2->setUserId(2);
$user2->setTwitterUserIds('1,2,3');
$user2->setTwitterTagIds('7,8,9');
$user2->save();
voiceUserData::setUserFollows($user2);
$user2Follows = voiceUserData::getUserFollows(2);
$t->is($user2Follows['users'], array(0=>1, 1=>2, 2=>3), '::setUserFollows() follows user 1,2,3');
$t->is($user2Follows['tags'], array(0=>7, 1=>8, 2=>9), '::setUserFollows() follows tag 7,8,9');
$t->isa_ok($user2Follows['teams'], 'array', '::setUserFollows() follows favor teams');

$t->diag('Test: voiceUserData::hasFollowedTag()');
$t->is(voiceUserData::hasFollowedTag(2, 7), true, '::hasFollowedTag() return true when follow the tag');
$t->is(voiceUserData::hasFollowedTag(2, 10), false, '::hasFollowedTag() return false when not follow the tag');

$t->diag('Test: voiceUserData::hasFollowedUser()');
$t->is(voiceUserData::hasFollowedUser(2, 1), true, '::hasFollowedUser() return true when follow the user');
$t->is(voiceUserData::hasFollowedUser(2, 4), false, '::hasFollowedUser() return false when not follow the user');

$t->diag('Test: voiceUserData::getFollowedUsers()');
$t->is(voiceUserData::getFollowedUsers(2), array(0=>1, 1=>2, 2=>3), '::hasFollowedUser() return the follow users');

$t->diag('Test: voiceUserData::getFollowedTags()');
$t->is(voiceUserData::getFollowedTags(2), array(0=>7, 1=>8, 2=>9), '::hasFollowedUser() return the follow tags');

$t->diag('Test: voiceUserData::getFollowedTeams()');
$t->isa_ok(voiceUserData::getFollowedTeams(2), 'array', '::getFollowedTeams() return the favor teams');

$t->diag('Test: voiceUserData::clearUserFollows()');
voiceUserData::clearUserFollows();
$t->is(voiceUserData::$user_follows, null, '::clearUserFollows() clear voiceUserData::$user_follows to null');