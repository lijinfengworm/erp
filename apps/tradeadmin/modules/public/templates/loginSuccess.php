<!doctype html>
<html lang="en">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php include_stylesheets() ?>
    <script>
        var GV = {
            DIMAUB: "/",
            JS_ROOT: "/js/"
        };
    </script>
    <?php include_javascripts() ?>
</head>
<body id="login-page" style="background: #d2d6de;">
<div class="login-box">
<div class="login-logo">
    <a href="#">卡路里后台</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?php echo url_for('@default?module=public&action=login') ?>" method="post" class="login-form">
      <div class="form-group has-feedback">
        <input type="input" name="login[username]" class="form-control" placeholder="username">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="login[password]" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <div class="login_btn_panel">
                <button class="btn btn-primary btn-block btn-flat" type="submit">
                    <span class="on">登录</span>
                </button>
                <div class="check-tips"></div>
            </div>
        </div>
        <!-- /.col -->
      </div>
    </form>

    


  </div>
  <!-- /.login-box-body -->
  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px; display: none;"></div>
  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px; display: none;"></div>
  <div class="chart" id="line-chart" style="height: 250px; display: none;"></div>
</div>
<script>
    $(".login-form").on("focus", "input", function(){
        $(this).closest('.item').addClass('focus');
    }).on("blur","input",function(){
        $(this).closest('.item').removeClass('focus');
    });

    $(document)
        .ajaxStart(function(){
            $("button:submit").addClass("log-in").attr("disabled", true);
        })
        .ajaxStop(function(){
            $("button:submit").removeClass("log-in").attr("disabled", false);
        });

    $("form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(data){
            if(data.status){
                window.location.href = data.url;
            } else {
                self.find(".check-tips").text(data.info);
            }
        }
    });

    $(function(){
        $("#itemBox").find("input[name=username]").focus();


        function isPlaceholer(){
            var input = document.createElement('input');
            return "placeholder" in input;
        }
        if(!isPlaceholer()){
            $(".placeholder_copy").css({
                display:'block'
            })
            $("#itemBox input").keydown(function(){
                $(this).parents(".item").next(".placeholder_copy").css({
                    display:'none'
                })
            })
            $("#itemBox input").blur(function(){
                if($(this).val()==""){
                    $(this).parents(".item").next(".placeholder_copy").css({
                        display:'block'
                    })
                }
            })


        }
    });
</script>
</body>
</html>
