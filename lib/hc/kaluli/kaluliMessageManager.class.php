<?php

/**
 * Description of tradeMessageManager
 *
 * @author hcsyp
 */
class kaluliMessageManager {

    private $key = 'kaluli.message.content.';
    private $redis;
    private $id;
    private $updateTime = 35;

    public function __construct($id) {
        $this->id = $id;
        $this->key = $this->key.$id;
    }

    public function get() {
        $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->redis->select(10);
        $res = unserialize($this->redis->get($this->key));
        if(!$res){
            $res = $this->set();
        }

        return $res;
    }
    
    public function set() {
        $article = kaluliArticleTable::getInstance()->createQuery()->where('id = ?',$this->id)->andWhere('status = ?',1)->fetchOne();
        $res = array('status'=>true);

        if($article){
            $articleTags = kaluliTagsRelateTable::getTags( 2, $this->id);

            $res['data'] = array(
                'id'=>$article->getId(),
                'title'=>$article->getTitle(),
                'type'=>$article->getType(),
                'intro'=>$article->getIntro(),
                'content'=>$article->getContent(),
                'created_at'=>$article->getCreatedAt(),
                'hits'=>$article->getHits(),
                'tags'=>$articleTags
            );
        }else{
            $res['status'] = false;
        }

        $this->redis->set($this->key,serialize($res),$this->updateTime);

        return $res;
    }
}
?>
