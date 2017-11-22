Wind.use('My97DatePicker');
//卡号HTML
var html_one = '<div><div class="txt-line b-t-d-333"></div>\
                <div class="form-item">\
                <label class="item-label"> 时间设置</label>\
                <div class="controls">\
                    <select class="postpone_type" name="record[postpone_type][]">\
                    <option value="1">预先设定</option>\
                    <option value="2">动态生成</option>\
                    </select>\
                </div></div>\
                 <div class="form-item d-hide postpone_day_box">\
                <label class="item-label"> 发放天数</label>\
                <div class="controls">\
                <input type="text"  name="record[postpone_day][]"  class="w100 postpone_day" /> <span class="L10 c-999">以天为单位 比如 30 就是在卡密入账的那一刻过期时间是30天以后</span>\
                </div></div>\
                 <div class="form-item d-hide overdue_day_box">\
                <label class="item-label"> 到期激活</label>\
                <div class="controls">\
                <input type="text"  name="record[overdue_day][]"  class="w100 overdue_day" /> <span class="L10 c-999">默认不限制，以天为单位。比如说你写30天 那么用户获取了卡密后 如果生成卡密开始30天内还不激活 那么就失效了。</span>\
                </div></div>\
            <div class="form-item time_box">\
            <label class="item-label"> 有效时间</label>\
            <div class="controls">\
            <input type="text" name="record[stime][]" class="stime" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" />&nbsp;&nbsp;&nbsp;至&nbsp;&nbsp;&nbsp;<input name="record[etime][]" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" class="etime" type="text" />\
            </div>\
            </div>\
            <div class="form-item">\
            <label class="item-label">  变身大卡</label>\
            <div class="controls">\
            <select class="is_large" name="record[is_large][]">\
            <option selected="1" value="0" >不变身</option>\
            <option value="1" >变身</option>\
            </select>\
            <span class="mL10  c-999">注：变身大卡，就是这一批所有的卡都变成一张卡 能用你指定的数量，但是一个用户只能使用一次！，并且不能单张用！</span>\
            </div></div>\
            <div class="form-item large_card" style="display:none;">\
            <label class="item-label">  设置兑换码</label>\
            <div class="controls">\
            <input name="record[is_large_card][]" type="text" />\
            </div></div>\
            <div class="form-item">\
            <label class="item-label">  使用范围</label>\
            <div class="controls">\
            <select class="scope" name="record[scope][]">\
            <option value="1">全场券</option>\
            <option value="2">集合券</option>\
            </select>\
            </div></div>\
            <div name="record[kll_group_id][]" class="form-item kll_group_id" style="display:none">\
            <label class="item-label">  集合id</label>\
            <div><input size="10" type="text" name="record[group_id][]" > \
            <div class="help" style="color:red"></div>\
            </div></div>\
            <div class="form-item">\
            <label class="item-label">  单张面额</label>\
            <div class="controls">\
            <select name="record[amount][]">\
            <option value="1" >1元</option>\
            <option value="5" >5元</option>\
            <option value="10" >10元</option>\
            <option value="20" >20元</option>\
            <option value="30" >30元</option>\
            <option value="40" >40元</option>\
            <option value="50" >50元</option>\
            <option value="60" >60元</option>\
            <option value="70" >70元</option>\
            <option value="80" >80元</option>\
            <option value="100" >100元</option>\
            <option value="110" >110元</option>\
             <option value="140" >140元</option>\
            <option value="150" >150元</option>\
            <option value="200" >200元</option>\
            <option value="300" >300元</option>\
            <option value="500" >500元</option>\
            </select>\
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label >数量</label> <input name="record[num][]" type="text" />\
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="remove_card_line gwyy_btn ">删除该行</button>\
            </div></div>\
            <div class="form-item">\
            <label class="item-label">  限制条件</label>\
            <div class="controls">\
            <div class="card_limit_box">\
             订单金额大于等于 <input value="" name="record[card_limit][][order_money]" class="w40 tCenter card_limit_order_money" type="text" />元可以使用\
             </div> </div> </div>\
            </div>';


var html_two = '<div><div class="txt-line b-t-d-333"></div><div class="form-item">\
                <input type="hidden" name="record[postpone_type][]" value="1" />\
                <input type="hidden" name="record[postpone_day][]" value="" />\
                <label class="item-label"> 有效时间</label>\
                <div class="controls">\
                <input type="text" name="record[stime][]" class="" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" />&nbsp;&nbsp;&nbsp;至&nbsp;&nbsp;&nbsp;<input name="record[etime][]" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" class="J_date" type="text" />\
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="">  单张面额： </label>\
                <select name="record[amount][]">\
                 <option value="1" >1元</option>\
                <option value="5" >5元</option>\
                <option value="10" >10元</option>\
                <option value="20" >20元</option>\
                <option value="30" >30元</option>\
                <option value="40" >40元</option>\
                <option value="50" >50元</option>\
                <option value="60" >60元</option>\
                <option value="70" >70元</option>\
                <option value="80" >80元</option>\
                <option value="100" >100元</option>\
                <option value="110" >110元</option>\
                <option value="140" >140元</option>\
                <option value="150" >150元</option>\
                <option value="200" >200元</option>\
                <option value="300" >300元</option>\
                <option value="500" >500元</option>\
                </select>\
                </div>\
                </div>\
                <div class="form-item">\
                <label class="item-label">  使用范围</label>\
                <div class="controls">\
                <select class="scope" name="record[scope][]">\
                <option value="1">全场券</option>\
                <option value="2">集合券</option>\
                </select>\
                </div></div>\
                <div name="record[kll_group_id][]" class="form-item kll_group_id" style="display:none">\
                <label class="item-label">  集合id</label>\
                <div><input size="10" type="text" name="record[group_id][]" > \
                <div class="help" style="color:red"></div>\
                </div></div>\
                <div class="form-item">\
                <label class="item-label">  接收账户</label>\
                <div class="controls">\
                <textarea name="record[accept_uids][]" class="gwyy_textarea h80  fL" placeholder="填写发放账户的虎扑uid 比如：1345632 254326 一行一个"></textarea>\
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="remove_card_line gwyy_btn ">删除该行</button>\
                </div></div>\
                 <div class="form-item">\
            <label class="item-label">  限制条件</label>\
            <div class="controls">\
            <div class="card_limit_box">\
             订单金额大于等于 <input value="" name="record[card_limit][][order_money]" class="w40 tCenter card_limit_order_money" type="text" />元可以使用\
             </div> </div> </div>\
                </div>';





$(function(){

    $('.error_list').click(function(){
        $(this).hide();
    });

    //新增一条
    $('#add-card').on('click',function(){
        var _type = $('#lipinkaForm').attr('type-id');
        if(_type == 1) {
            $('#form-item-card-box').append(html_one);
        } else {
            $('#form-item-card-box').append(html_two);
        }

    });

    //删除一行
    $(document).on('click','.remove_card_line',function(){
        var _this = $(this);
        var $_this = this;
        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '确定要删除吗？',
                okValue: '确定',
                ok: function () {
                    _this.parent().parent().parent().remove();
                },
                cancelValue: '取消',
                cancel: function () {}
            }).show($_this);
        });
    });

    //礼品卡时间类型
    $(document).on('change',".postpone_type",function(e){
        var _type = $(this).val();
        var par = $(this).parent().parent().parent();
        if(_type == 1) {
            par.children('.postpone_day_box').hide();
            par.children('.overdue_day_box').hide();
            par.children('.time_box').show();
            par.find('.postpone_day').val('');
        } else {
            par.children('.postpone_day_box').show();
            par.children('.overdue_day_box').show();
            par.children('.time_box').hide();
            par.find('.stime').val('');
            par.find('.etime').val('');
        }
    });

    //选择申请类型  页面跳转
    $("input:radio[name='kaluli_lipinka[type]']").on('change',function(e){
        //取消事件的默认动作
        e.preventDefault();
        //终止事件 不再派发事件
        e.stopPropagation();
        var _this = $(this);
        var _val = $(this).val();
        var type_url = $('#lipinkaForm').attr('type-url').replace('GWYY',_val);
        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '你确定要改变申请类型吗，会刷新当前页面的？',
                okValue: '确定',
                ok: function () {
                    window.location = type_url;
                },
                cancelValue: '取消',
                cancel: function () {
                    var  curr = (_val == 1) ? 1 : 0;
                    $("input:radio[name='kaluli_lipinka[type]']:eq("+curr+")").prop("checked",1);
                    $("input:radio[name='kaluli_lipinka[type]']:eq("+(_val-1)+")").removeAttr("checked");
                }
            }).showModal();
        });


    });





    //限制规则
    $(document).on('keyup paste','.card_limit_order_money',function(){
        var money = $(this).val();
        money = parseInt(money);
        if(isNaN(money) || money == '' || money <= 0) {
            $(this).parent().removeClass('card_limit_box_current');
            return true;
        }
        $(this).parent().addClass('card_limit_box_current');
    });
    
    //使用范围
    $(document).on('change',".scope",function(e){
        var scope = $(this).val();
        var par = $(this).parent().parent().parent();
        if(scope == 2) {
            par.children('.kll_group_id').show();
        } else {
            par.children('.kll_group_id').hide();
        }
    });    

    $(document).on('change', ".is_large", function(e){
        var large = $(this).val();
        if(large == 1){
            $('.large_card').show();
        }else{
            $('.large_card').hide();
        }
    });


});




















