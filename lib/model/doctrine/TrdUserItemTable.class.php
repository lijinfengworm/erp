<?php

/**
 * TrdUserItemTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdUserItemTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdUserItemTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdUserItem');
    }

    //检查用户之前是否已经发布过了
    public function getByItemAndUid($item_id, $uid) {
        return self::getInstance()->createQuery()
            ->from("TrdUserItem as u")
            ->leftJoin("u.Item t on t.item_id = $item_id")
            ->where("t.item_id = ?", $item_id)
            ->andwhere('u.user_id = ?', $uid)
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL)
            ->orderby("t.is_verified desc, t.created_at desc")
            ->fetchOne();
    }

    public function getByItemAllAndUid($item_id, $uid) {
        return self::getInstance()->createQuery()
            ->from("TrdUserItem as u")
            ->leftJoin("u.ItemAll t on t.item_id = $item_id")
            ->where("t.item_id = ?", $item_id)
            ->andwhere('u.user_id = ?', $uid)
            ->andwhere('is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('status = ?', TrdItemTable::STATUS_NORMAL)
            ->orderby("t.created_at desc")
            ->fetchOne();
    }

    public function getByItemidAndUid($item_id, $uid) {
        return self::getInstance()->createQuery()
            ->from("TrdUserItem as u")
            ->andwhere('u.user_id = ?', $uid)
            ->andwhere('u.item_id = ?', $item_id)
            ->fetchOne();
    }

    public function getByItemAllIdAndUid($item_id, $uid) {
        return self::getInstance()->createQuery()
            ->from("TrdUserItem as u")
            ->andwhere('u.user_id = ?', $uid)
            ->andwhere('u.item_all_id = ?', $item_id)
            ->fetchOne();
    }
    
    public function getByBaoliaoItemAllIdAndUid($baoliao_id, $uid) {
        return self::getInstance()->createQuery()
            ->from("TrdUserItem as u")
            ->andwhere('u.user_id = ?', $uid)
            ->andwhere('u.baoliao_id = ?', $baoliao_id)
            ->fetchOne();
    }
    
    public function getByBaoliaoItemAllId($baoliao_id) {
        return self::getInstance()->createQuery()
            ->from("TrdUserItem as u")
            ->andwhere('u.baoliao_id = ?', $baoliao_id)
            ->fetchOne();
    }

    public function all_auto_save($user_id, $item_id) {
        if($rs = $this->getByItemAllIdAndUid($item_id, $user_id)) {
            $rs->setCreatedAt(date("Y-m-d H:i:s", time()));;
            $rs->save();

        } else {
            $TrdUserItem = New TrdUserItem();
            $TrdUserItem->setUserId($user_id);
            $TrdUserItem->setItemAllId($item_id);
            $TrdUserItem->save();
        }
    }
    
    public function all_baoliao_auto_save($user_id, $baoliao_id) {
        if($rs = $this->getByBaoliaoItemAllIdAndUid($baoliao_id, $user_id)) {
            $rs->setCreatedAt(date("Y-m-d H:i:s", time()));;
            $rs->save();

        } else {
            $TrdUserItem = New TrdUserItem();
            $TrdUserItem->setUserId($user_id);
            $TrdUserItem->setBaoliaoId($baoliao_id);
            $TrdUserItem->save();
        }
    }
    
    public function all_baoliao_update_save($baoliao_id,$item_all_id = NULL,$shoe_id = NULL) {
        if($rs = $this->getByBaoliaoItemAllId($baoliao_id)) {
            if (!empty($shoe_id)) $rs->setItemId($shoe_id);
            if (!empty($item_all_id)) $rs->setItemAllId($item_all_id);
            $rs->setCreatedAt(date("Y-m-d H:i:s", time()));
            $rs->save();

        }
    }

    public function auto_save($user_id, $item_id) {
        if($rs = $this->getByItemidAndUid($item_id, $user_id)) {
            $rs->setCreatedAt(date("Y-m-d H:i:s", time()));;
            $rs->save();

        } else {
            $TrdUserItem = New TrdUserItem();
            $TrdUserItem->setUserId($user_id);
            $TrdUserItem->setItemId($item_id);
            $TrdUserItem->save();
        }
    }

    public function getByUid($uid) {
        return self::getInstance()->createQuery()
            ->from("TrdUserItem as u")
            ->andwhere('u.user_id = ?', $uid)
            ->execute();
    }
}