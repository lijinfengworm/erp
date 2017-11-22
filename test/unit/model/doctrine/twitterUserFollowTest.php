<?php
require_once dirname(__FILE__) . '/../../../bootstrap/doctrine.php';
$t = new lime_test(37);
$t->diag('Unit test for: twitterUserFollow');

/*
 * delete test data
 */
twitterUserFollowTable::getInstance()->createQuery('t')
        ->delete()
        ->whereIn('t.user_id', array(1,2,3, 5010678))
        ->execute();
$user1 = new twitterUserFollow();
$user1->setUserId(1);
$user1->setTwitterUserIds(1,2,3);
$user1->setTwitterTagIds(7,8,9);
$user1->save();
$user2 = new twitterUserFollow();
$user2->setUserId(2);
$user2->setTwitterUserIds();
$user2->setTwitterTagIds();
$user2->save();


/*
 * getTwitterUserIdsArr()
 */
$t->isa_ok($user1->getTwitterUserIdsArr(), 'array', '->getTwitterUserIdsArr() return an array');
$t->isa_ok($user2->getTwitterUserIdsArr(), 'array', '->getTwitterUserIdsArr() return an array');

/*
 * getTwitterTagIdsArr()
 */
$t->isa_ok($user1->getTwitterTagIdsArr(), 'array', '->getTwitterTagIdsArr() return an array');
$t->isa_ok($user2->getTwitterTagIdsArr(), 'array', '->getTwitterTagIdsArr() return an array');

/*
 * getUserFollowsArr()
 */
$a = $user1->getUserFollowsArr();
$t->isa_ok($a, 'array', '->getUserFollowsArr() return an array');
$t->is(isset($a['users']), true, '->getUserFollowsArr() return an array with key: users');
$t->is(isset($a['tags']), true, '->getUserFollowsArr() return an array with key: tags');
$t->is(isset($a['teams']), true, '->getUserFollowsArr() return an array with key: teams');
$a = $user2->getUserFollowsArr();
$t->is(isset($a['users']), true, '->getUserFollowsArr() return an array with key: users');
$t->is(isset($a['tags']), true, '->getUserFollowsArr() return an array with key: tags');
$t->is(isset($a['teams']), true, '->getUserFollowsArr() return an array with key: teams');
$user3 = new twitterUserFollow();
$a = $user3->getUserFollowsArr();
$t->isa_ok($a, 'array', '->getUserFollowsArr() return an array for a new object');
$t->is(isset($a['users']), true, '->getUserFollowsArr() return an array with key: users');
$t->is(isset($a['tags']), true, '->getUserFollowsArr() return an array with key: tags');
$t->is(isset($a['teams']), true, '->getUserFollowsArr() return an array with key: teams');

/*
 * getFavorTeamIdsArr()
 */
$t->isa_ok($user1->getFavorTeamIdsArr(), 'array', '->getFavorTeamIdsArr() return an array');
$t->isa_ok($user2->getFavorTeamIdsArr(), 'array', '->getFavorTeamIdsArr() return an array');
$t->isa_ok($user3->getFavorTeamIdsArr(), 'array', '->getFavorTeamIdsArr() return an array');
$user4 = new twitterUserFollow();
$user4->setUserId(5010678);
$t->is(count($user4->getFavorTeamIdsArr())==2, true, '->getFavorTeamIdsArr() return the user\'s favor teams');

/*
 * followFavorTeams()
 */
$user4->followFavorTeams();
$t->isnt($user4->getTwitterTagIds(), '', '->followFavorTeams() followes favor teams');
$t->isnt($user4->getTwitterUserIds(), '', '->followFavorTeams() followes favor users');

/*
 * followTag()
 */
$user5 = new twitterUserFollow();
$user5->setUserId(3);
$tag = twitterTagTable::getInstance()->find(7);
$t->is($user5->followTag($tag), 1, '->followTag() follows tag 7 and return 1');
$t->is(in_array(7, $user5->getTwitterTagIdsArr()), true, '->followTag() followes tag 7');
$t->isnt($user5->getTwitterUserIds(), '', '->followTag() followes the users in tag 7 when follow tag 7 ');
$t->is($user5->followTag($tag), 0, '->followTag() can\'t follow tag 7 again');

/*
 * disfollowTag()
 */
$t->is($user5->disfollowTag($tag), 1, '->disfollowTag() unfollows tag 7 and return 1');
$t->is(!in_array(7, $user5->getTwitterTagIdsArr()), true, '->disfollowTag() unfollows tag 7');
$t->is($user5->getTwitterUserIds(), '', '->followTag() unfollowes the users in tag 7 when unfollow tag 7 ');
$t->is($user5->disfollowTag($tag), 0, '->disfollowTag() can\'t unfollows a tag has not followed');

/*
 * followUser()
 */
$user = twitterUserTable::getInstance()->find(1);
$user5->followUser($user);
$t->is(in_array(1, $user5->getTwitterUserIdsArr()), true, '->followUser() follows user 1');
$o = $user5->getTwitterUserIdsArr();
$user5->followUser($user);
$t->is($o, $user5->getTwitterUserIdsArr(), '->followUser() can\'t follows user 1 again');
$users = twitterUserTable::getInstance()->createQuery('u')->whereIn('u.id', array(2,3))->execute();
$user5->followUser($users);
$t->is($user5->getTwitterUserIds(), '1,2,3', '->followUser() follows users');
$users = twitterUserTable::getInstance()->createQuery('u')->whereIn('u.id', array(3,4))->execute();
$user5->followUser($users);
$t->is($user5->getTwitterUserIds(), '1,2,3,4', '->followUser() follows users');

/*
 * disfollowUser()
 */
$user = twitterUserTable::getInstance()->find(1);
$user5->disfollowUser($user);
$t->is(in_array(1, $user5->getTwitterUserIdsArr()), false, '->disfollowUser() unfollows user 1');
$o = $user5->getTwitterUserIdsArr();
$user5->disfollowUser($user);
$t->is($o, $user5->getTwitterUserIdsArr(), '->disfollowUser() unfollows user 1 again');
$users = twitterUserTable::getInstance()->createQuery('u')->whereIn('u.id', array(2,3))->execute();
$user5->disfollowUser($users);
$t->is($user5->getTwitterUserIdsArr(), array(4), '->disfollowUser() unfollows user 2,3');
$users = twitterUserTable::getInstance()->createQuery('u')->whereIn('u.id', array(3,4))->execute();
$user5->disfollowUser($users);
$t->is($user5->getTwitterUserIdsArr(), array(), '->disfollowUser() unfollows user 4');




