<?php

class tradeWidgetFormKupload extends sfWidgetFormInput {

    protected function configure($options = array(), $attributes = array()) {

        $this->addOption("callback");
        $this->addOption("rule");
        $this->addOption("shoutao");
        $this->addOption("goods");

        parent::configure($options, $attributes);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $shoutao =  $this->getOption('shoutao');

		if(sfConfig::get('sf_environment') == 'prod' || sfConfig::get('sf_environment') == 'stg'){
            if(true !== $shoutao)
            {
                $uploadImagUrl = '/trade.php/api/ueditorImageUpload';
            }
            else
            {
                $uploadImagUrl = '/tradeadmin.php/trd_shoutao/taobaoImageUpload';
            }
		}else{
            if(true !== $shoutao)
            {
                $uploadImagUrl = '/trade_dev.php/api/ueditorImageUpload';
            }
            else
            {

                $uploadImagUrl = '/tradeadmin_dev.php/trd_shoutao/taobaoImageUpload';
                
            }
		}


        sfContext::getInstance()->getResponse()->addStylesheet('/simpleKindeditor/themes/default/default.css');
        sfContext::getInstance()->getResponse()->addJavascript('/simpleKindeditor/kindeditor-min.js');
        sfContext::getInstance()->getResponse()->addJavascript('/simpleKindeditor/lang/zh_CN.js');

        $callback ="";
        if($this->hasOption("callback"))
        {
            $callback =  $this->getOption('callback');
        }

        $parameter = "";
        if($this->hasOption("goods"))
        {
            $parameter.=  "&goods=".$this->getOption('goods');
        }
        $rule = array();
        if($this->hasOption("rule"))
        {
            $rule =  $this->getOption('rule');
            $parameter.= "&rule=".urlencode(serialize($rule));
        }

		$return = '
            <script>
                KindEditor.ready(function(K) {
				var inf'.substr(md5($name),0,5).' = K.uploadbutton({
					button : K("#inf'.substr(md5($name),0,5).'")[0],
					fieldName : "imgFile",
					url : "'.$uploadImagUrl.'?'.$parameter.'",
					afterUpload : function(data) {
						if (data.error === 0) {
                            '.$callback.'
						} else {
							alert(data.message);
						}
					},
					afterError : function(str) {
						alert("自定义错误信息: " + str);
					}
				});
				inf'.substr(md5($name),0,5).'.fileBox.change(function(e) {
					inf'.substr(md5($name),0,5).'.submit();
				});

            });
            </script>

            <input type="button" id="inf'.substr(md5($name),0,5).'" value="选择文件" />
        ';

        return $return;
    }


}