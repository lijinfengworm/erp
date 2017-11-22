<?php

/**
 * KllWiki form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllWikiForm extends BaseKllWikiForm
{
  public function configure()
  {

    $this->setWidget('title', new sfWidgetFormInput(array(), array('class'=>'w460')));
    $this->setValidator('title',
        new sfValidatorString(array('required' => true, 'trim' => true),
            array('required' => '标题必填！')));
    $this->setWidget('banner', new sfWidgetFormInput(array(), array('class'=>'w460')));
    # 上传图片
    $rule = array(
        'required'=>true,
        'max_size'=>'500000',
      //    'height'=>400,
      //    'width'=>400,
        'path'=>'uploads/kaluli/wiki',
      //   'ratio'=>'1x1'
    );
    $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
    $this->setValidator('banner',
        new sfValidatorString(array('required' => true, 'trim' => true),
            array('required' => '封面必须上传！')));
    for($i=1; $i<100; $i++){
      $this->setWidget('upload_path'.$i,new tradeWidgetFormKupload(array("callback"=>"displayCoverImage(data.url, $i);","rule"=>$rule)));
    }

    $this->validatorSchema->setPostValidator(
        new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
    );
  }

  /**
   * 组装content 和qa数据
   * @param $validator
   * @param $values
   */
  public function myCallback($validator, $values) {

  }

  //处理解析后的标签
  public static function _formatForm($content){
    $newData = [
        "block"    =>  [],

    ];
    //block
    preg_match_all("/.*?<block>(.*?)<\/block>.*?/", $content, $block);
    if(!empty($block) && isset($block[1][0])){
      $newData["block"] = self::_formatBlock($block[1][0]);
    }

    return $newData;

  }

  public static function  _formatBlock($block){
    $data = array();
    if($block){
      $area = self::_checkPreg("area", $block);
      if($area){
        foreach ($area as $ka => $a){
          $areaTmpTitle = self::_checkPreg("title", $a);//解析title
          $areaTmpStage = self::_checkPreg("stage", $a);//解析stage
          //判断是否有区
          if(!empty($areaTmpTitle[0])){
            $pregTmp = self::_checkPreg("part", $a);
            if($pregTmp){
              //判断是否有区块
              $data["area"][$ka]["title"] = $areaTmpTitle[0];
              $data["area"][$ka]["stage"] = $areaTmpStage[0];
              foreach ($pregTmp as $kp => $p){

                //$data["area"][$ka]["part"][$kp] = ["cover"  => "","number"  => "","title"   => "","content" => ""];
                $partTmpTitle = self::_checkPreg("title",$p);
                if(!empty($partTmpTitle[0])){
                  $data["area"][$ka]["part"][$kp]["title"] = $partTmpTitle[0];
                  $partTmpContent = self::_checkPreg("content",$p);
                  if($partTmpContent){
                    $data["area"][$ka]["part"][$kp]["content"] = $partTmpContent[0];
                  }
                  $partTmpCover = self::_checkPreg("cover",$p);
                  if($partTmpCover){
                    $data["area"][$ka]["part"][$kp]["cover"] = $partTmpCover[0];
                  }
                  $partTmpItem = self::_checkPreg("item",$p);
                  if($partTmpItem) {
                    $data["area"][$ka]["part"][$kp]["item"] = $partTmpItem[0];
                    $itemHtmls = self::_formatItemHtml(intval($partTmpItem[0]));
                    $data["area"][$ka]["part"][$kp]["itemHtml"] = $itemHtmls['html'];
                    $data["area"][$ka]["part"][$kp]["itemMhtml"] = $itemHtmls['mhtml'];

                  }

                }else{
                  break;
                }
              }
            }
          }else{
            break;
          }
        }
      }
    }
    return $data;

  }

  private static function _checkPreg($label, $content){
    preg_match_all("/.*?<".$label.">(.*?)<\/".$label.">.*?/", $content, $preg);
    if(!empty($preg) && isset($preg[1])){
      return $preg[1];
    }
    return false;
  }

  private static function _formatItemHtml($id) {
    $itemTable = KaluliItemTable::getInstance();
    $ids = [$id];
    $products = $itemTable::getMessageOff(array('ids' => $ids, 'select' => 'id,title,pic,sell_point,discount_price,price,intro'));
    $newData = [];

    foreach ($products as $v) {
      $newData['id'] = $v['id'];
      $newData['status'] = $v['status'];
      $newData['title'] = $v['title'];
      $newData['pic'] = isset($v['pic']) ? $v['pic'] : '';
      $newData['sell_point'] = $v['sell_point'];
      $newData['intro'] = $v['intro'];
      $newData['price'] = $v['price'];
      $newData['discount_price'] = $v['discount_price'];
      $newData['discount'] = number_format(($newData['discount_price']/$newData['price'])*10, 1, '.', '');
      $newData['format_price'] = FormatPrice::getInstance()->getPrice($v['id']);
    }
      $html = "";
      $mhtml = "";
    if(!empty($newData)){
      //下架后商品隐藏链接
      $show_status = $ad_img = $bg_color =  $font_color = '';
      if($newData['status'] == 4){
          $show_status = 'display:none;';
          $bg_color = "background-color: #f7f7f7;";
          $font_color = 'color: #999;';
      }else{
          $ad_img = "//kaluli.hoopchina.com.cn/images/kaluli/cms/icon_advertisement.png";
          
      }
      $html = '<div class="recommend-goods" style="margin: 20px 0 50px;background-color: #f7f7f7;border: 1px solid #efefef;border-radius: 5px;height: 212px;position: relative"><img style="position: absolute; right: 0;top: 0;" src="'.$ad_img.'"><ul class="list-inline" style="display: block;padding-left: 0;margin-left: -5px;">';
      $html .= '<li><div class="goodsimg" style="float:left; margin-top: 20px; height: 151px;width: 151px;background: url(http://kaluli.hoopchina.com.cn/images/kaluli/cms/goodsBac.png) center no-repeat;}" >';
      $html .= '<img style="width: 95px;height: 95px;position: relative;top: 28px;left: 28px;" src="' . $newData['pic'] . '">';
      $html .= '</div></li><li><div class="info" style="    width: 248px;text-align: left;margin-left: 15px;margin-top: 13px;border-right: 1px solid #ddd;height: 190px;float: left;padding-top: 30px;padding-right: 5px;overflow: hidden;"> ';
      $html .= '<p style="color: #fa6731;font-size: 14px;">';
      $html .= '' . $newData['title'] . '</p>';
      $html .= '<p style="color: #7a7a7a;margin: 15px 0 25px;height: 84px;overflow: hidden">' . $newData['intro'] . '</p>';

      $html .= '</div></li>';
      if($newData['format_price']['kaluli_discount_type'] == 0){
        $html .= '<li><div class="pirce"><div style="'.$show_status.'">原价 <span class="yuanjia" style="'.$show_status.'">￥<b>'.$newData['format_price']['kaluli_lineation_price'].'</b></span></div><div class="mt-10"><span class="kaluli-price" style="'.$font_color.$bg_color.'">卡路里价 : ￥<b>'.$newData['format_price']['kaluli_discount_price'].'</b></span><span class="discourt" style="'.$show_status.'">'.$newData['format_price']['kaluli_activity_label'].'</span></div><p class="mt-10"><a href="https://www.kaluli.com/product/' . $newData['id'] . '.html" target="_blank" class="link-detail" onmouseover="this.style.cssText=\'background-color:#fd6732; color:#fff; text-decoration:none;\'" onmouseout="this.style.cssText=\'color:#b3b3b3;text-decoration:none\'" style="'.$show_status.'">查看详情</a></p></div></li></ul></div>';
      }elseif ($newData['format_price']['kaluli_discount_type'] == 1) {
        $html .= '<li><div class="pirce"><div style="'.$show_status.'"> <span class="yuanjia" style="'.$show_status.'"></span></div><div class="mt-10"><span class="kaluli-price" style="'.$font_color.$bg_color.'">卡路里价 : ￥<b>'.$newData['format_price']['kaluli_lineation_price'].'</b></span><span class="discourt" style="'.$show_status.'">'.$newData['format_price']['kaluli_activity_label'].'</span></div><p class="mt-10"><a href="https://www.kaluli.com/product/' . $newData['id'] . '.html" target="_blank" class="link-detail" onmouseover="this.style.cssText=\'background-color:#fd6732; color:#fff; text-decoration:none;\'" onmouseout="this.style.cssText=\'color:#b3b3b3;text-decoration:none\'" style="'.$show_status.'">查看详情</a></p></div></li></ul></div>';
      }else{
        $html .= '<li><div class="pirce"><div style="'.$show_status.'"><span class="yuanjia" style="'.$show_status.'"></span></div><div class="mt-10"><span class="kaluli-price" style="'.$font_color.$bg_color.'">卡路里价 : ￥<b>'.$newData['format_price']['kaluli_lineation_price'].'</b></span><span class="discourt" style="'.$show_status.'"></span></div><p class="mt-10"><a href="https://www.kaluli.com/product/' . $newData['id'] . '.html" target="_blank" class="link-detail" onmouseover="this.style.cssText=\'background-color:#fd6732; color:#fff; text-decoration:none;\'" onmouseout="this.style.cssText=\'color:#b3b3b3;text-decoration:none\'" style="'.$show_status.'">查看详情</a></p></div></li></ul></div>';
      }
      

      $mhtml .= '<div class="main main_type_2">';
      $mhtml .= '<div class="shop">';
      $mhtml .= '        <div class="shop_area">';
      $mhtml .= '            <div class="area_left">';
      $mhtml .= '<img  src="'.$newData['pic'].'" alt="">';
      $mhtml .= '         </div>';
      $mhtml .= '            <div class="area_right">';
      $mhtml .= '                <p class="shop_title">'.$newData["title"].'</p >';
      $mhtml .= '   <p class="shop_text">'.$newData['intro'].'</p >';
      $mhtml .= '</div></div>';
      $mhtml .= '<div class="area_bottom">';
      
      $mhtml .=     '<div class="price">';
      if($newData['format_price']['kaluli_discount_type'] == 0){
        $mhtml .= '卡路里价:<span class="price_number" style="">&nbsp;￥'.$newData['format_price']['kaluli_discount_price'].'</span>';
        $mhtml .= '<span class="price_label">'.$newData['format_price']['kaluli_activity_label'].'</span><span class="price_lineation">'.$newData['format_price']['kaluli_lineation_price'].'</span>';
        
      }elseif ($newData['format_price']['kaluli_discount_type'] == 1) {
        $mhtml .= '卡路里价:<span class="price_number" style="">&nbsp;￥'.$newData['format_price']['kaluli_lineation_price'].'</span>';
        $mhtml .= '<span class="price_label">'.$newData['format_price']['kaluli_activity_label'].'</span>';
      }else{
        $mhtml .= '卡路里价:<span class="price_number" style="">&nbsp;￥'.$newData['format_price']['kaluli_lineation_price'].'</span>';
      }
      $mhtml .= '<a class="shop-link" href="https://www.kaluli.com/product/'.$newData['id'].'.html" style="'.$show_status.'">商品链接<span class="iconfont icon-gongyongdanjiantouyoubian"></span></a >';
      $mhtml .= '</div></div></div>';
      
    }
    return ['html'=>$html,'mhtml'=>$mhtml];

  }




}
