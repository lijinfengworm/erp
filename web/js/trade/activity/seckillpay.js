
angular.module('app',[]).controller("secKill",function($scope,$sce){
    $scope.goods=res.data;

    var paystatus = res.status == 0 ? true : false;
    $scope.payerror = function(){
        return paystatus ? false : true;
    }

    $scope.paysuccess = function(){
        return paystatus ? true : false;
    }
});

var countdown;

$(function(){
    if(res.status != 0){
        var sec = $("#gobacksec").text();
        countdown = setInterval(function(){
            if(sec==0){
                clearInterval(countdown);
                window.location.href="http://www.shihuo.cn/haitao/blackFriday/act/secKill";
            }else{
                sec--;
                $("#gobacksec").text(sec);
            }
        },1000);
    }
    $('.qq-kefu').on('click', function() {
        window.open('http://www.shihuo.cn/kefu', 'kefuwindow', 'toolbar=no, status=no,scrollbars=0,resizable=0,menubar＝0,location=0,width=700,height=500');
    });
});