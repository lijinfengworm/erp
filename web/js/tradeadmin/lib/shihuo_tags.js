/**
 * 识货整站 tags 类
 * 梁天 2015-05-10
 */
function ShihuoTags(args) {
    var _this = this;
    // 分类 dom
    this.rootDom = args.rootDom;
    this.secondDom = args.secondDom;
    //品牌dom
    this.brandDom = args.brandDom;
    this.addBrandBtnDom = args.addBrandBtnDom;
    this.addBrandInputDom = args.addBrandInputDom;
    this.brand_ajax_url = args.brand_ajax_url;
    this.model_ajax_url = args.model_ajax_url;
    this.tags_ajax_url = args.tags_ajax_url;
    this.add_brand_ajax_url = args.add_brand_ajax_url;
    this.auto_brand_ajax_url = args.auto_brand_ajax_url;

    //型号dom
    this.modelDom = args.modelDom;
    this.addModelBtnDom = args.addModelBtnDom;
    this.addModelInputDom = args.addModelInputDom;
    this.addModelTypeInputDom = args.addModelTypeInputDom;
    this.add_model_ajax_url = args.add_model_ajax_url;

    //标签 dom
    this.tagsDom = args.tagsDom;
    this.addTagsBtnDom = args.addTagsBtnDom;
    this.addTagsInputDom = args.addTagsInputDom;
    this.add_tags_ajax_url = args.add_tags_ajax_url;
    //tag 展示box
    this.tags_select_box = args.tags_select_box ? args.tags_select_box : '#shihuo_tags_ul';
    //tag 选中 box
    this.tags_curr_box = args.tags_curr_box ? args.tags_curr_box : '#shihuo_tags_box';
    //tags input
    this.tags_input_box = args.tags_input_box;

    //初始化操作
    this.init();

    //一级分类选择后 重置所有
    $(this.rootDom).on('change',function(){
        _this.resetElement([1,2,3]);
    });

    //二级分类change时出发修改品牌
    $(this.secondDom).on('change',function(){
        _this.brandChange($(this).val());
        _this.resetElement([2,3]);
    });

    //品牌选择后选择型号
    $(this.brandDom).on('change',function(){
        _this.modelChange($(this).val());
        _this.resetElement([3]);
    });

    //增加品牌
    $(this.addBrandBtnDom).on('click',function(){
        _this.addBrand();
    });

    //增加型号
    $(this.addModelBtnDom).on('click',function(){
        _this.addModel();
    });

    //增加品牌
    $(this.addTagsBtnDom).on('click',function(){
        _this.addTags();
    });

    //型号选择后 选择 tag
    $(this.modelDom).on('change',function(){
        _this.tagsChange($(this).val());
        _this.resetElement([3]);
    });


    //型号选择后 添加到已选择列表里面
    $(document).on('click',this.tags_select_box+" li",function(){
        _this.currentTags($(this).text());
    });

    //删除标签
    $(document).on('click',this.tags_curr_box+" span s",function(){
        _this.delTags($(this));
    });



}

ShihuoTags.prototype = {
    init:function(){
        //判断如果 初始化型号有内容 那么就调用一次标签
        var _model_id = $(this.modelDom).val();
        if(_model_id != '' && _model_id > 0) {
            this.tagsChange(_model_id);
        }
    },
    addTags:function(){
        var _this = this;
        var mid = $(this.modelDom).val();
        var val = $(this.addTagsInputDom).val();
        if (val) {
            var url = this.add_tags_ajax_url+"?mid=" + mid + '&tag=' + encodeURIComponent(val);
            $.getJSON(url, function (data) {
                if(data.status == 1) {
                    $(_this.addTagsInputDom).val('');
                    $(_this.tags_select_box).append('<li>'+val+'</li>');
                } else {
                    toast.error(data.info);
                }
            })
        } else {
            toast.error("请先填写需要增加的标签！");
        }
    },
    addModel:function(){
        var _this = this;
        var bid = $(this.brandDom).val();
        var tid = $(this.addModelTypeInputDom).val();
        var val = $(this.addModelInputDom).val();
        var cid = $(this.secondDom).val();
        if (val) {
            var url = this.add_model_ajax_url+"?bid=" + bid + '&tid=' + tid + '&cid=' + cid + '&name=' + encodeURIComponent(val);
            $.getJSON(url, function (data) {
                if(data.status == 1) {
                    $(_this.addModelTypeInputDom).val('');
                    $(_this.addModelInputDom).val('');
                    _this.modelChange(bid,data.data.id);
                    _this.tagsChange(data.data.id);
                    $(_this.tags_curr_box).empty();
                } else {
                    toast.error(data.info);
                }
            })
        } else {
            toast.error("请先填写需要增加的型号！");
        }
    },
    addBrand:function(){
        var _this = this;
        var cid = $(this.secondDom).val();
        var val = $(this.addBrandInputDom).val();
        var usage = 'brand';
        if (!cid) {
            toast.error("参数不完整！");
            return false;
        }
        if (val) {
            var url = this.add_brand_ajax_url+'?cid=' +cid+ '&usage=' + usage + '&name=' + encodeURIComponent(val);
            $.getJSON(url, function (data) {
                if(data.status == 1) {
                    $(_this.addBrandInputDom).val('');
                    _this.brandChange(cid,data.data.id);
                } else {
                    toast.error(data.info);
                }
            })
        } else {
            toast.error("请先填写需要增加的品牌！");
        }

    },
    resetElement:function(arr) {
        //重置品牌
        if($.inArray(1,arr) >= 0) {
            $(this.brandDom).html('<option value="0">请选择品牌</option>');
        }
        //重置型号
        if($.inArray(2,arr) >= 0) {
            $(this.modelDom).html('<option value="0">请先择型号</option>');
        }
        //清空标签
        if($.inArray(3,arr) >= 0) {
            $(this.tags_curr_box).empty();
            $(this.tags_select_box).empty();
        }

    },
    delTags:function(obj) {
        obj.parent().remove();
        this.sync_tags_input();
    },
    sync_tags_input:function(){
        var tags = new Array();
        $(this.tags_curr_box+' span').each(function(i,v){
            tags[i] =  $(this).find('i').text();
        });
        $(this.tags_input_box).val(tags.join());

    },
    currentTags:function(_text) {
        var is_repeat = false;
        //检测重复
        $(this.tags_curr_box+' span').each(function(i,v){
            if(_text == $(this).find('i').text()) is_repeat = true;
        });
        if(is_repeat) {
            toast.info("不要重复添加！");
            return true;
        }
        var _html = '<span class="curr_tags"><i>'+_text+'</i><s>x</s></span>';
        $(this.tags_curr_box).append(_html);
        //同步到标签input里面
        this.sync_tags_input();

    },
    brandChange:function(secondId,current_id){
        var _this = this;
        if(secondId == 0 || secondId == '') return true;
        $.ajax({
            type: "GET",
            url: this.brand_ajax_url,
            data: {children_id:secondId,current_id:current_id},
            dataType: "json",
            success: function(data){
                if (data.status == 1){
                    $(_this.brandDom).html(data.data.brand);
                } else {
                    toast.error(data.info);
                }
            }
        });
    },
    modelChange:function(brandId,model_id) {
        var _this = this;
        if(brandId == 0 || brandId == '') return true;
        $.ajax({
            type: "GET",
            url: this.model_ajax_url,
            data: {brand_id:brandId,model_id:model_id},
            dataType: "json",
            success: function(data){
                if (data.status == 1){
                    $(_this.modelDom).html(data.data);
                } else {
                    toast.error(data.info);
                }
            }
        });
    },
    tagsChange:function(model_id) {
        var _this = this;
        if(model_id == 0 || model_id == '') return true;
        $.ajax({
            type: "GET",
            url: this.tags_ajax_url,
            data: {model_id:model_id},
            dataType: "json",
            success: function(data){
                if (data.status == 1){
                    $(_this.tags_select_box).html(data.data);
                } else {
                    toast.error(data.info);
                }
            }
        });

    }







}

