;(function ($) {
    $.fn.hcheckbox = function (options) {
        $(':checkbox+label').click(function (event) {
            if (!$(this).prev().is(':checked')) {
                $(this).addClass("checked");
                $(this).prev()[0].checked = true;
                $(this).prev()[0].value = 1;
            }
            else {
                $(this).removeClass('checked');
                $(this).prev()[0].checked = false;
                $(this).prev()[0].value = 0;
            }
            event.stopPropagation();
        }
        ).prev().hide();
    }

//Download by http://down.liehuo.net
})(jQuery)

jQuery.divselect = function (divselectid, inputselectid) {

    var inputselect = $(inputselectid);
    $(divselectid + " cite").click(function () {
        var ul = $(divselectid + " ul");
        if (ul.css("display") == "none") {
            ul.slideDown("fast");
        } else {
            ul.slideUp("fast");
        }
    });
    $(divselectid + " ul li a").click(function () {
        var txt = $(this).text();
        $(divselectid + " cite").html(txt);
        var value = $(this).attr("selectid");
        inputselect.val(value);
        $(divselectid + " ul").hide();
    });
    $(document).click(function () {
        $(divselectid + " ul").hide();
    });
};
