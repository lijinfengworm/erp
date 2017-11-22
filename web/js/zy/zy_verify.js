/**
 * Description camp verify endit
 * User: guwei
 * Update: 13-3-14 10:14
 */
function editVer() {
    var resWrap = G.$('JEditVer');
    if (!resWrap)return;
    var resBtn = G.$$$('m-submit', resWrap)[0];
    G.listenEvent(resBtn, 'click', function () {
        e = arguments[0] || window.event;
        G.preventDefault(e);
        var value = $("#JEditVer").find('form').serialize(),action = G.$$('form',resWrap)[0].getAttribute('action');
        $.post(action, value, function (data) {
            if (data.status == 0) {
                window.location.href = "/djt/zy_verify";
            } else {
                alert(data.msg)
            }
        }, 'json');
    })
}
addload(function () {
    //console.log(img_big);
    checkForm.init(true);
    //bPic=true;
    ic.Url = img_big;
    //ic.Height = 200;
    ic.Init();
    editVer()
});



