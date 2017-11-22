<?php
/**
 * 增加幻灯片
 */
class slideService
{
    private static $slide = array(
          'youhuiIndex'=>array(
              'redis_key'=>'trade_youhui_index_slide2',
              'name' => '优惠首页',
              'help'=>'图片尺寸为611*276',
              'pic_rule'=>array(
                  'required'=>true,
                  'path'=>'youhuiIndex',
                  'max_size'=>'2000000',
             )
          ),

          'haitaoIndex'=>array(
              'redis_key'=>'trade_haitao_n_news_index_slide2',
              'name' => '海淘首页',
              'help'=>'图片尺寸为637*255',
              'pic_rule'=>array(
                  'required'=>true,
                  'path'=>'haitaoIndex',
                  'max_size'=>'2000000',
             )
          ),

          'tuangouIndex'=>array(
              'redis_key'=>'trd_groupon_newindex_focus2_v2',
              'name' => '团购首页',
              'help'=>'图片尺寸为606x274',
              'pic_rule'=>array(
                  'required'=>true,
                  'path'=>'tuangouIndex',
                  'max_size'=>'2000000',
             )
          ),

            'tuangouFocusIndex'=>array(
                'redis_key'=>'trade_tuangou_focus_v2',
                'name' => '团购焦点图',
                'help'=>'图片尺寸为225*131',
                'min_num'=>0,
                'pic_rule'=>array(
                    'required'=>true,
                    'path'=>'tuangoufocus',
                    'max_size'=>'2000000',
                )
            ),

        'tuangouMIndex'=>array(
            'redis_key'=>'trd_groupon_newindex_m_focus2_v1',
            'name' => 'M站团购首页banner',
            'help'=>'图片尺寸为750*258',
            'pic_rule'=>array(
                'required'=>true,
                'path'=>'tuangouIndexm',
                'max_size'=>'2000000',
            )
        ),

        'tuangouMFocusIndex'=>array(
            'redis_key'=>'trade_tuangou_m_focus_v1',
            'name' => 'M站团购焦点图',
            'help'=>'第一张图片尺寸为409*164，其余为204*204',
            'min_num'=>0,
            'num' => 3,
            'pic_rule'=>array(
                'required'=>true,
                'path'=>'tuangoufocusm',
                'max_size'=>'2000000',
            )
        ),

         'findIndex'=>array(
             'redis_key'=>'trd_find_homepage_options2',
             'name' => '发现首页',
             'help'=>'图片尺寸为880*330',
             'pic_rule'=>array(
                 'required'=>true,
                 'path'=>'findIndex',
                 'max_size'=>'2000000',
             )
         ),


         'activityIndex'=>array(
             'redis_key'=>'trade_activity_coupon',
             'name' => '优惠券首页左侧',
             'help'=>'1号banner尺寸560*270',
             'pic_rule'=>array(
                 'required'=>true,
                 'path'=>'activityIndex',
                 'max_size'=>'2000000',
             )
         ),
        'activityIndex2'=>array(
            'redis_key'=>'trade_activity_coupon2',
            'name' => '优惠券首页右侧',
            'help'=>'2号banner尺寸265*270',
            'pic_rule'=>array(
                'required'=>true,
                'path'=>'activityIndex',
                'max_size'=>'2000000',
            )
        ),

        'shoeIndex'=>array(
            'redis_key'=>'trade_sneakers_index_options2',
            'name' => '运动鞋首页',
            'help'=>'图片尺寸为760*400',
            'color'=>true,
            'pic_rule'=>array(
                'required'=>true,
                'path'=>'shoeIndex',
                'max_size'=>'2000000',
            )
        ),

        'shopIndex'=>array(
            'redis_key'=>'trade:shopindex:slide:v1',
            'name' => '推荐店铺首页',
            'help'=>'图片尺寸为844x272',
            'pic_rule'=>array(
                'required'=>true,
                'path'=>'shoeIndex',
                'max_size'=>'2000000',
            )
        ),

        'shopAdIndex'=>array(
            'redis_key'=>'trade:shopindex:ad:v1',
            'name' => '推荐店铺首页广告位',
            'help'=>'图片尺寸为225x131',
            'num' => 2,
            'min_num' => 2,
            'pic_rule'=>array(
                'required'=>true,
                'path'=>'shopIndex',
                'max_size'=>'2000000',
            )
        ),

        'nikeIndex'=>array(
            'redis_key'=>'trade_nike_index_slide',
            'name' => 'NIKE广告位',
            'help'=>'图片尺寸为270*110',

            'pic_rule'=>array(
                'required'=>true,
                'path'=>'nikeIndex',
                'max_size'=>'2000000',
            )
        ),
    );

    private static  $_default = array(
        'redis_key'=>'trd.slide',
        'name' => '默认',
        'help'=>'无',
        'color'=>false,
        'num' => 6,
        'min_num'=>1,
        'pic_rule'=>array(
            'required'=>true,
            'path'=>'defaultIndex',
            'max_size'=>'2000000',
        )
    );

    public static  function  has($name){
        return isset(self::$slide[$name]) ? true : false;
    }

    public static  function  get($name){
        self::init();

        if(self::has($name))
           return self::$slide[$name];
        else
           return array();
    }

    public static  function  all(){
        self::init();

        return self::$slide;
    }

    private static function init(){
        foreach(self::$slide as $k=>&$v){
            $v = array_merge(self::$_default,$v);
        }
    }

}