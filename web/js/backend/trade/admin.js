$("document").ready(function() {
    $(".go_ignore").click(function(e) {
        e.preventDefault();
        $(this).parent().parent().hide();
        var url = $(this).attr("url");
        $.get(url);
    });

    $(".go_process").click(function() {
        $(this).parent().parent().hide();
        var url = $(this).attr("url");
        $.get(url);
    });
});

