define(function(){

    var birthday = {
        initYear:function(year) {
            var yearNow = new Date().getFullYear();
            var yearSel = $(year).attr("rel");
            $(year).html("<option value='0'>请选择</option>");
            for(var i=yearNow ; i>=1900;i--){
                var sed = yearSel==i?"selected":"";
                var yearStr = "<option value=\"" + i + "\" " + sed+">" + i + "</option>";
                $(year).append(yearStr);
            }
        },

        initMonth:function(month) {
            var monthSel = $(month).attr("rel");
            $(month).html("<option value='0'>请选择</option>");
            for (var i = 1; i <= 12; i++) {
                var sed = monthSel==i?"selected":"";
                var monthStr = "<option value=\"" + i + "\" "+sed+">" + i + "</option>";
                $(month).append(monthStr);
            }
        },

        buildDay:function(year,month,day) {
            if($(year).val()==0 || $(month).val() == 0) {
                $(day).html("<option value='0'>请选择</option>"); //年月未选择不添加数据
            } else {
                $(day).html("<option value='0'>请选择</option>");
                var yearVal =parseInt($(year).val());
                var monthVal = parseInt($(month).val());
                var dayCount = 0;
                switch (monthVal) {
                    case 1:
                    case 3:
                    case 5:
                    case 7:
                    case 8:
                    case 10:
                    case 12:
                        dayCount = 31;
                        break;
                    case 4:
                    case 6:
                    case 9:
                    case 11:
                        dayCount = 30;
                        break;
                    case 2:
                        dayCount = 28;
                        if ((yearVal % 4 == 0) && (yearVal % 100 != 0) || (yearVal % 400 == 0)) {
                            dayCount = 29;
                        }
                        break;
                    default:
                        break;
                }
                var daySel = $(day).attr("rel");
                for (var i = 1; i <= dayCount; i++) {
                    var sed = daySel==i?"selected":"";
                    var dayStr = "<option value=\"" + i + "\" "+sed+">" + i + "</option>";
                    $(day).append(dayStr);
                }

            }
        }
    };

    return birthday;

});
