function errorLayer(html){
      var str = '<div class="comment-layer">\
            <div class="title-area-box"><s id="error_close2"></s>提示</div>\
            <div class="clearfix">\
              <div class="t1">\
                <img src="http://www.shihuo.cn/images/tradeadmin/trd_goods/g1.jpg" />\
              </div>\
              <div class="t2">\
                <p>'+html+'</p>\
                <div class="close-btn" id="error_close">关闭</div>\
              </div>\
            </div>\
          </div>';
          $(str).appendTo('body');
          $.Jui._showMasks(0.6);
          $(".comment-layer").css({
               left:$.Jui._position($(".comment-layer"))[0],
               top:$.Jui._position($(".comment-layer"))[1]
          });
  }
  
function fileQueueError(file, errorCode, message) {
  try {
    var imageName = "error.gif";
    var errorName = "";
    /*
    if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
      errorName = "你添加的文件超过了限制！";
    }

    if (errorName !== "") {
      alert(errorName);
      return;
    }
*/
   

    switch (errorCode) {
      case -100:
          errorLayer("您已超出5长图片上限，请重新选择");
          break;
      case -130:
          errorLayer("只能上传jpg、png、jpeg格式的文件");
          break;
      case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
        imageName = "zerobyte.gif";
        break;
      case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
        //imageName = "toobig.gif";
        errorLayer("你上传的文件过大");
        break;
      case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
      case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
      default:
        errorLayer(message);
        break;
    }

    //addImage("/ui/img/" + imageName);
    

  } catch (ex) {
    this.debug(ex);
  }

}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
  try {
    if (numFilesQueued > 0) {
      this.startUpload();
    }
  } catch (ex) {
    this.debug(ex);
  }
}

function uploadProgress(file, bytesLoaded) {

  try {
    var percent = Math.ceil((bytesLoaded / file.size) * 100);

    var progress = new FileProgress(file,  this.customSettings.upload_target);
    progress.setProgress(percent);
    if (percent === 100) {
      progress.setStatus("生成缩略图...");
      progress.toggleCancel(false, this);
    } else {
      progress.setStatus("正在上传...");
      progress.toggleCancel(true, this);
    }
  } catch (ex) {
    this.debug(ex);
  }
}

function uploadSuccess(file, serverData) {
    var progress = new FileProgress(file,  this.customSettings.upload_target);
    var stats = swfu.getStats();
    var data = $.parseJSON(serverData);
    if(data.status == 200)
    {
        addImage(data.data.url,this.customSettings.upload_list);
        progress.setStatus("缩略图生成成功！");
        progress.toggleCancel(false);
    }else{
        alert(eval("("+serverData+")").msg);
        progress.setStatus("错误.");
        progress.toggleCancel(false);
    }

    stats.successful_uploads--;
    swfu.setStats(stats);
}

function uploadComplete(file) {
  try {
    /*  I want the next upload to continue automatically so I'll call startUpload here */
    if (this.getStats().files_queued > 0) {
      this.startUpload();
    } else {
      var progress = new FileProgress(file,  this.customSettings.upload_target);
      progress.setComplete();
      progress.setStatus("所有图片上传完成.");
      progress.toggleCancel(false);
    }
  } catch (ex) {
    this.debug(ex);
  }
}

function uploadError(file, errorCode, message) {
  var imageName =  "error.gif";
  var progress;
  try {
    switch (errorCode) {
      case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
        try {
          progress = new FileProgress(file,  this.customSettings.upload_target);
          progress.setCancelled();
          progress.setStatus("Cancelled");
          progress.toggleCancel(false);
        }
        catch (ex1) {
          this.debug(ex1);
        }
        break;
      case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
        try {
          progress = new FileProgress(file,  this.customSettings.upload_target);
          progress.setCancelled();
          progress.setStatus("Stopped");
          progress.toggleCancel(true);
        }
        catch (ex2) {
          this.debug(ex2);
        }
      case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
        alert(message);
        break;
      default:
        alert(message);
        break;
    }

  } catch (ex3) {
    this.debug(ex3);
  }

}

function fileDialogStart(){
    //console.log(SWFUpload.BUTTON_ACTION)
}

function mouseClickhandler(){
    //alert(123)
}

var thumbid=0;
function addImage(src,list) {
  var newImgDiv = document.createElement("div");
  newImgDiv.id = 'thumb'+ (thumbid+1);
  newImgDiv.className = "imgbox list";
  window.imagesUplodingList = list;
  $(".photo_list").eq(window.imagesUplodingList).append(newImgDiv);
  if($(".photo_list").eq(window.imagesUplodingList).height() > 150){
    $(".check-more").eq(window.imagesUplodingList).show();
  }else{
    $(".check-more").eq(window.imagesUplodingList).hide();
  }
  newImgDiv.innerHTML = '<img src="'+src+'" data_src="'+src+'" class="get_img_src" />'+
                '<div class="close"><input type="hidden" name="pictures" value="'+(src.substring(src.lastIndexOf('path=')+5)).replace(/_/g,'/')+'" /><a href="javascript:void(0);">&nbsp;</a></div>'+
                '<div class="allbtn">'+
                '<div class="fade">置顶</div>'+
                '<div class="left"></div>'+
                '<div class="right"></div></div>';
  newImgDiv.onload = function () {
    fadeIn(newImgDiv, 0);
  };
  thumbid=thumbid+1;
  $(".photo_list").eq(window.imagesUplodingList).find(".allbtn").eq(0).hide();
  img_uploding.ajaxEditPic($(".photo_list").eq(window.imagesUplodingList),$(".photo_list").eq(window.imagesUplodingList).parents(".add-color-area").attr("idstr"));
}
function delPic(id){
    var oldpic=document.getElementById('thumb'+id);
    var myString=oldpic.firstChild.src;
    var p=myString.lastIndexOf('?');
    var stats = swfu.getStats();
    stats.successful_uploads--;
    swfu.setStats(stats);
    //$.get("/item/delPic?" + myString.substr(++p,myString.length-p) + "&" + Math.random());
    $("#thumb"+id).remove();
}

function loaded() {  
    if(pic_collect.length != 0) {  
        for( val in pic_collect ) {  
            addImageFromDb(pic_collect[val],this);
        }  
    }  
}  
function loadedImage(src) {  
    addImageFromDb(src,swfu);
}  
//初始化已经上传过的图片  
function addImageFromDb(src_s,swfu) {  

    var stats = swfu.getStats();  
    stats.successful_uploads++;  
    swfu.setStats(stats);  
    addImage("/item/thumbImage?path="+src_s); 
}  

function fadeIn(element, opacity) {
  var reduceOpacityBy = 5;
  var rate = 30;	// 15 fps


  if (opacity < 100) {
    opacity += reduceOpacityBy;
    if (opacity > 100) {
      opacity = 100;
    }

    if (element.filters) {
      try {
        element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
      } catch (e) {
        // If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
        element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
      }
    } else {
      element.style.opacity = opacity / 100;
    }
  }

  if (opacity < 100) {
    setTimeout(function () {
      fadeIn(element, opacity);
    }, rate);
  }
}



/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, targetID) {
  this.fileProgressID = "divFileProgress";

  this.fileProgressWrapper = document.getElementById(this.fileProgressID);
  if (!this.fileProgressWrapper) {
    this.fileProgressWrapper = document.createElement("div");
    this.fileProgressWrapper.className = "progressWrapper";
    this.fileProgressWrapper.id = this.fileProgressID;

    this.fileProgressElement = document.createElement("div");
    this.fileProgressElement.className = "progressContainer";

    var progressCancel = document.createElement("a");
    progressCancel.className = "progressCancel";
    progressCancel.href = "#";
    progressCancel.style.visibility = "hidden";
    progressCancel.appendChild(document.createTextNode(" "));

    var progressText = document.createElement("div");
    progressText.className = "progressName";
    progressText.appendChild(document.createTextNode(file.name));

    var progressBar = document.createElement("div");
    progressBar.className = "progressBarInProgress";

    var progressStatus = document.createElement("div");
    progressStatus.className = "progressBarStatus";
    progressStatus.innerHTML = "&nbsp;";

    this.fileProgressElement.appendChild(progressCancel);
    this.fileProgressElement.appendChild(progressText);
    this.fileProgressElement.appendChild(progressStatus);
    this.fileProgressElement.appendChild(progressBar);

    this.fileProgressWrapper.appendChild(this.fileProgressElement);

    document.getElementById(targetID).appendChild(this.fileProgressWrapper);
    fadeIn(this.fileProgressWrapper, 0);

  } else {
    this.fileProgressElement = this.fileProgressWrapper.firstChild;
    this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
  }

  this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
  this.fileProgressElement.className = "progressContainer green";
  this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
  this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
  this.fileProgressElement.className = "progressContainer blue";
  this.fileProgressElement.childNodes[3].className = "progressBarComplete";
  this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setError = function () {
  this.fileProgressElement.className = "progressContainer red";
  this.fileProgressElement.childNodes[3].className = "progressBarError";
  this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setCancelled = function () {
  this.fileProgressElement.className = "progressContainer";
  this.fileProgressElement.childNodes[3].className = "progressBarError";
  this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setStatus = function (status) {
  this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
  this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
  if (swfuploadInstance) {
    var fileID = this.fileProgressID;
    this.fileProgressElement.childNodes[0].onclick = function () {
      swfuploadInstance.cancelUpload(fileID);
      return false;
    };
  }
};


!(function($){
  function tips(a,arr) {
      return this.each(function() {
          var $this = $(this),
              str = '<div class="tips_layer" style="position: absolute; border-radius:5px; background-color:#ff6600; display:none; z-index:995">\
                <div class="tips-text" style="padding:2px 5px; line-height:18px; color:#fff;">'+a+'</div>\
                <div class="diamond"></div>\
            </div>';
           if($(".tips_layer")){
              $(".tips_layer").remove();
           }
          $(str).appendTo("body");
          var $tips_text = $(".tips-text"),
                  $tips_layer = $(".tips_layer");
          if(arr){
             $tips_layer.css({
                "top": arr.top,
                "left": arr.left
              }).show();
          }else{
            $tips_layer.css({
              "top": $this.offset().top - parseInt($this.height())-10,
              "left": $this.offset().left + parseInt($this.width()/2) -30
            }).show();
          }
          setTimeout(function(){
             $tips_layer.remove();
          },2000);
      });
  }

  $.Jui = $.Jui || {};
    $.extend($.Jui, {
        // version: "1.0",
        _$: function(a, b) {
            a.siblings().removeClass(b);
            a.addClass(b);
        },
        _showMasks: function(a) {
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + $(document).height() + "px; background-color:#000;  z-index:91;'></div>";
            $("body").append(str);
            $(".body-mask").css("opacity", 0);
            $(".body-mask").animate({
                "opacity": a ? a : "0.8"
            });
        },
        _closeMasks: function() {
            var close = $(".body-mask");
            close.fadeOut(function() {
                close.remove();
            });
        },
        _getpageSize: function() {
            /*
             height:parseInt($(document).height()),
             width:parseInt($(document).width())
             */
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
        },
        _position: function(obj) {//计算对象放置在屏幕中间的值   obj:需要计算的对象
            var left = ((this._getpageSize()[0] - parseInt(obj.outerWidth())) / 2);
            var top = ((this._getpageSize()[1] - parseInt(obj.outerHeight())) / 2);
            return [left, top];
        },
        _getpageScroll: function() {
            var yScrolltop;
            if (self.pageYOffset) {
                yScrolltop = self.pageYOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
                yScrolltop = document.documentElement.scrollTop;
            } else if (document.body) {
                yScrolltop = document.body.scrollTop;
            }
            return yScrolltop;
        },
        DOC: $(document),
        WIN: $(window),
        HEAD: $(document).find("head"),
        BODY: $(document).find("body")
    });


  $.fn.tips = tips;
})(jQuery);