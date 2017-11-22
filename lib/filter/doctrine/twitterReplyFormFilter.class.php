<?php

/**
 * twitterReply filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class twitterReplyFormFilter extends BasetwitterReplyFormFilter
{    
    private $allChannel = array(
           ''  => 'channels',
           '1' => '篮球',
           '2' => '足球',
           '3' => '赛车',
           '4' => '网球',
           '5' => '综合体育',
           '6' => 'CBA',
           '7' => '中超',
           '8' => 'WCBA',
           '9' => '装备', 
        );
    
    public function configure() {
        parent::configure();
        $this->setWidget('twitter_message_id',new sfWidgetFormInput());
        $this->setValidator('twitter_message_id', new sfValidatorPass(array('required' => false)));
        $this->setValidator('user_id', new sfValidatorPass(array('required'=>false)));
        $this->setValidator('user_name', new myTwitterReplyValidator(array('required'=>false,'trim'=>true)));
        
        $this->setWidget('fifty_light_record', new sfWidgetFormChoice(array('choices' => array('1' => ''), 'expanded' => TRUE, 'multiple' => FALSE, 'label' => '当天前50亮评')));
        $this->setValidator('fifty_light_record', new sfValidatorPass()); 
        
        /*$this->setWidget('reply_channel_in', new sfWidgetFormChoice(array('choices' => $this->allChannel,'label'=>'频道')));
        $this->setValidator('reply_channel_in', new sfValidatorPass());*/
        
        $this->setWidget('fiter_topic_reply', new sfWidgetFormChoice(array('choices' => array('1' => ''), 'expanded' => TRUE, 'multiple' => FALSE, 'label' => '专题评论')));
        $this->setValidator('fiter_topic_reply', new sfValidatorPass()); 
    }    
    
     public function getFields() {
        $fields = parent::getFields();
        $fields['fifty_light_record'] = 'Text';
        $fields['fiter_topic_reply'] = 'Text';
       // $fields['reply_channel_in'] = 'Text';
        return $fields;
    }
    
    public function addFiftyLightRecordColumnQuery($query, $field, $value){
        if ($value['text'] == 1){
            $rootAlias = $query->getRootAlias();
            
            return $query->andWhere($rootAlias . '.created_at <= ?', date('Y-m-d H;i:s', time()))
                         ->andWhere($rootAlias . '.created_at >= ?', date('Y-m-d H;i:s', time() - 86400))
                         ->orderBy($rootAlias . '.light_count desc');
 //                        ->addOrderBy($rootAlias . '.created_at desc')
 //                        ->limit(10);
        }else{
            return $query;
        }
    }
    
    public function addFiterTopicReplyColumnQuery($query, $field, $value){
        if ($value['text'] == 1){           
            return $query->leftJoin('r.twitterTopic t')
                         ->andWhere('r.twitter_topic_id is not null');
        }else{
            return $query;
        }
    }
    
    /*public function addReplyChannelInColumnQuery($query, $field, $value){
        if($value['text']){
            return $query->leftJoin('r.twitterTopic t')
                         ->leftJoin('r.twitterMessage m')
                         ->andWhere('t.category = ?', $value['text'])
                         ->orWhere('m.category = ?', $value['text']);         
        }else{
            return $query;
        }
    }*/
}
