<?php

/**
 * TrdHomepageItemBigTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TrdHomepageItemBigTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TrdHomepageItemBigTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TrdHomepageItemBig');
    }

    public function byCategoryName($name) {
        return self::getInstance()->createQuery()
            ->from("TrdHomepageItemBig as t")
            ->leftJoin("t.Category c")
            ->andWhere("c.name = ?", $name)
            ->orderby("created_at desc")
            ->execute();
    }

    public function byCategoryNames($names = array(), $limit = 4) {
        $sql = self::getInstance()->createQuery()
            ->from("TrdHomepageItemBig as t")
            ->leftJoin("t.Category c")
            ->orderby("created_at desc")
            ->limit($limit);

        if(count($names) > 1) {
            $i = 1;
            foreach($names as $name) {
                if($i == 1) {
                    $sql->andWhere("c.name = ?", $name);
                } else {
                    $sql->orWhere("c.name = ?", $name);
                }
                $i++;
            }
        } else {
            $sql->andWhere("c.name = ?", $name);
        }

        return $sql->execute();
    }
}
