<?php

class tradeWidgetFormUeditor extends sfWidgetFormTextarea {


    protected function configure($options = array(), $attributes = array()) {
        $this->addOption("button_widget");
        $this->addOption("channel");
        parent::configure($options, $attributes);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $channel = $this->getOption('channel');                                                                   //频道

        if( $button_widget = $this->getOption('button_widget')){                                                  //增加组件按钮
            $button_widget = (array)$button_widget;
            $button_widget['name'] = !empty($button_widget['name']) ? $button_widget['name'] : '添加商品';
            $button_widget['dialog_url'] = !empty($button_widget['dialog_url']) ? $button_widget['dialog_url'] : 'http://www.shihuo.cn/js/trade/ueditor/dialogs/shihuo/guoneiWidget.html';
            $button_widget['dialog_title'] = !empty($button_widget['dialog_title']) ? $button_widget['dialog_title'] : '添加商品';

        }

		unset($attributes['id'],$attributes['name']);
		$attrs = array();
		foreach($attributes as $k=>$v)
		{
			$attrs[] = $k.'='.$v;
		}
        if(empty($attributes['width'])) $attributes['width'] = 0;
        if(empty($attributes['water'])){
            $attributes['water'] = 0;
        }
		if(sfConfig::get('sf_environment') == 'prod' || sfConfig::get('sf_environment') == 'stg'){
			$uploadImagUrl = '/trade.php/api/ueditorImageUpload?width='.$attributes['width'].'&water='.$attributes['water'];
		}else{
			$uploadImagUrl = '/trade_dev.php/api/ueditorImageUpload?width='.$attributes['width'].'&water='.$attributes['water'];
		}

        $return = '';
        //频道控制加载配置文件
        if(in_array($channel, array('news','group_treasure'))){
            sfContext::getInstance()->getResponse()->addJavascript('/js/trade/ueditor/ueditor.news.config.js');
            sfContext::getInstance()->getResponse()->addJavascript('/js/trade/ueditor/ueditor.news.all.js');

            $return .=<<<EOF
<script type="text/javascript">
$(function(){
    //自定义
    var editor = UE.getEditor('js_editor_trd_news_text', {

    });
    editor.addListener('afterSelectionChange',function(){
        var imgobj = $("#ueditor_0").contents().find("img");
        $(imgobj).each(function(index, el){
            var isrc = $(this).attr("src");

            if(isrc.indexOf(".gif") < 0){
                if($(this).parent().children().length == 1){
                   // $(this).parent().attr('contentEditable','false');
                }
                if(!$(this).hasClass('trade_editor_test')){
                   $(this).addClass('trade_editor_img');
                }
            }else{
                $(this).addClass('trade_editor_img_gif');
            }
        });
    });
})
</script>
EOF;
        }else{
            sfContext::getInstance()->getResponse()->addJavascript('/js/trade/ueditor/ueditor.config.js');
            sfContext::getInstance()->getResponse()->addJavascript('/js/trade/ueditor/ueditor.all.min.js');
        }

        $new_name = strtr($name,array('['=>'_',']'=>''));
		$return .= '<script id="js_editor_'.$new_name.'" type="text/plain" '.implode(' ',$attrs).' name="'.$name.'" >'.$value.'</script>
				<!--编辑器-->
				<script type="text/javascript">
					$(function() {
						var ue_'.$new_name.'= UE.getEditor("js_editor_'.$new_name.'");
						UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
						UE.Editor.prototype.getActionUrl = function(action) {
							if (action == "config")
							{
								return "/js/trade/ueditor/php/config.json";
							}
							if (action == "uploadimage") {
								return "'.$uploadImagUrl.'";
							} else {
							return this._bkGetActionUrl.call(this, action);
							}
						}
                ';

        if($button_widget){//创建一个标题button
            $return .= 'UE.registerUI("'.$button_widget['name'].'", function(editor, uiName) {
                        var btn = new UE.ui.Button({
                            name: uiName,
                            title: uiName,
                            cssRules: "background-position: -1786px -798px;",
                            onclick: function() {
                                 dialog.render();
                                 dialog.open();
                            }
                        });


                        //弹层
                        var dialog = new UE.ui.Dialog({
                        iframeUrl:"'.$button_widget["dialog_url"].'",
                        editor:editor,
                        name:uiName+"dialog",
                        title:"'.$button_widget["dialog_title"].'",
                        cssRules:"width:500px;height:230px;",
                        buttons:[
                            {
                                className:"edui-okbutton",
                                label:"确定",
                                onclick:function () {
                                    $title = $("iframe").contents().find("#widget_title").val();
                                    $url = $("iframe").contents().find("#widget_url").val();
                                    $img = $("iframe").contents().find("#widget_img").val();
                                    $price = $("iframe").contents().find("#widget_price").val();
                                    $from = $("iframe").contents().find("#widget_from").val();
                                    $all = $title+"_shihuoflag_"+$url+"_shihuoflag_"+$img+"_shihuoflag_"+$price+"_shihuoflag_"+$from;

                                    //获取特殊的 base64
                                    var $text = "";
                                    $.ajax({
                                       type: "POST",
                                       dataType: "json",
                                       url: "http://www.shihuo.cn/api/byAdmin/act/base64",
                                       data: "text="+encodeURIComponent($title),
                                       beforeSend:function(){

                                       },
                                       success: function(msg){
                                         $text = msg.text;
                                       },
                                       async: false
                                    });

                                    $url = "http://shihuo.hupucdn.com/youhuiIndex/201507/3010/ab48017fd3aea39b84d9df27ea13c51b.png?watermark/2/text/"+$text+"/font/5b6u6L2v6ZuF6buR/dx/150/dy/30/gravity/NorthWest";
                                    if($.trim($title)!="" && $.trim($url)!="" && $.trim($img)!="" && $.trim($price)!="" && $.trim($from)!=""){
                                        $new_id = "<img  class=\"trade_editor_test\" title=\""+$all+"\" src=\""+$url+"\"\/>";
                                        ue_'.$new_name.'.execCommand("inserthtml", $new_id);

                                        dialog.close(false);
                                    }else{
                                        alert("不能留空");
                                    }
                                }
                            },
                            {
                                className:"edui-cancelbutton",
                                label:"取消",
                                onclick:function () {
                                    dialog.close(false);
                                }
                            }
                        ]});
                        return btn;
                    });';
        }

        $return .='ue_'.$new_name.'.ready(function(){
						})
					});

				</script>';
        return $return;
    }


}