<?php

/**
 * TrdGrouponTreasure form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class trdGrouponTreasureForm extends BaseTrdGrouponTreasureForm
{
    public function configure()
    {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['discount']);
        unset($this['price']);
        unset($this['url']);
        unset($this['original_price']);
        unset($this['apply_for_time']);
        unset($this['hupu_uid']);
        unset($this['hupu_username']);
        //unset($this['title']);
        //unset($this['category_id']);
        //unset($this['brand_id']);
        unset($this['pic_attr']);
        unset($this['goods_num']);
        unset($this['end_time']);
        unset($this['superiority']);


        if($this->getObject()->getMemo()){//数据格式处理
            $this->getObject()->setMemo(
                json_decode(gzuncompress(base64_decode($this->getObject()->getMemo())), true)
            );
        }

        if($this->getObject()->getPicAttr()){//数据格式处理
            $pic_attr = json_decode($this->getObject()->getPicAttr(), true);
            if(!empty($pic_attr['normal_img'])){
                $normal_img = $pic_attr['normal_img'][0];
            }ELSE{
                $normal_img = NUll;
            }
        }else{
            $normal_img = NUll;
        }


        $this->setWidget('memo',new tradeWidgetFormUeditor(array('channel'=>'group_treasure')));
        $this->setWidget('status',new sfWidgetFormInputText());
        $this->setWidget('start_time',new sfWidgetFormDateTime());
        $this->setWidget('intro',new sfWidgetFormTextarea());
        $this->setWidget('is_sold',new sfWidgetFormInputText());
        $this->setWidget('title',new sfWidgetFormInputText(array(),array('class'=>'calibration w340')));
        $this->setWidget('brand_id',new sfWidgetFormDoctrineChoice(array('model' => 'TrdBrand', 'add_empty' => true)));
        $this->setWidget('category_id',new sfWidgetFormDoctrineChoice(array('model' => 'TrdGrouponCategory', 'add_empty' => true)));
        $rule = array(
            'required'=>true,
            'path'=>'groupon',
            'max_size'=>'500000',
        );
        $this->setWidget('normal_img',new sfWidgetFormInputText());
        $this->setWidget('normal_img_btn',new tradeWidgetFormKupload(array('label'=>'封面图',"callback"=>"callback('trd_groupon_treasure_normal_img',data.url);","rule"=>$rule)));
        $this->setDefault('normal_img', $normal_img);

        $this->setValidator('memo', new sfValidatorPass());
        $this->setValidator('status', new sfValidatorNumber());
        $this->setValidator('is_sold', new sfValidatorNumber());
        $this->setValidator('title', new sfValidatorString(
                array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 8),
                array('required' => '标题必填',  'max_length' => '标题不大于40个字', 'min_length' => '标题不少于8个字'))
        );
        $this->setValidator('brand_id', new sfValidatorNumber());
        $this->setValidator('category_id', new sfValidatorNumber());
        $this->setValidator('normal_img', new sfValidatorUrl(
            array('required' => '封面图必传'))
        );

        $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
        );
    }


    public function myCallback($validator, $values)
    {
        $id         =  !empty($values['id']) ? $values['id'] : 0;
        $status     =  $values['status'] ;

        if($status == 2){
            if(empty($values['start_time'])){
                throw new sfValidatorError($validator, '上架开始时间必填');
            }

            if(empty($values['intro'])){
                throw new sfValidatorError($validator, '推荐理由必填');
                if(mb_strlen($values['intro'], 'utf-8') > 200) {
                    throw new sfValidatorError($validator, '推荐理由大于200个字');
                }
            }

            $start_time    =  date('Y-m-d H:i:s', strtotime(substr($values['start_time'],0,10)) + 10*3600);
            $end_time      =  date('Y-m-d H:i:s', strtotime(substr($values['start_time'],0,10)) + (24*3600) - 1);

           /* $next_monday   =  date('Y-m-d', strtotime('next monday'));
            $next_sunday   =  date('Y-m-d', strtotime('next monday') + (3600*24*6)).' 23:59:59';
            if($start_time < $next_monday || $start_time > $next_sunday){
                throw new sfValidatorError($validator, '上架开始时间必须在下周');
            }*/

            $groupon_treasure = trdGrouponTreasureTable::getInstance()->createQuery()
                ->where('status = ?', 2)
                ->andWhere('start_time = ?', $start_time)
                ->andWhere('id != ?', $id)
                ->fetchOne();
            if($groupon_treasure){
                throw new sfValidatorError($validator, $start_time.'已有审核成功的商品');
            }else{
                $values['start_time'] = $start_time;
                $values['end_time']   = $end_time;
            }
        }else{
            $values['start_time'] = NULL;
            $values['end_time'] = NULL;
        }

        //图片
        if($values['normal_img']){
            $pic_attr = json_decode($this->getObject()->getPicAttr(), true);
            $pic_attr['normal_img'][0] = $values['normal_img'];
            $values['pic_attr'] = json_encode($pic_attr);
        }

        //数据格式处理
        $values['memo'] =  base64_encode(gzcompress(json_encode($values['memo'])));
        return $values;
    }
}
