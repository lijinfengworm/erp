<?php
    /*
     * 图片裁剪类
     */
    define("OP_TO_FILE", 1);              // Output to file
    define("OP_OUTPUT", 2);               // Output to browser
    define("OP_NOT_KEEP_SCALE", 4);       // Free scale
    define("OP_BEST_RESIZE_WIDTH", 8);    // Scale to width
    define("OP_BEST_RESIZE_HEIGHT", 16);  // Scale to height

    define("CM_DEFAULT",0);               // Clipping method: default
    define("CM_LEFT_OR_TOP",1);           // Clipping method: left or top
    define("CM_MIDDLE",2);                // Clipping method: middle
    define("CM_RIGHT_OR_BOTTOM",3);       // Clipping method: right or bottom

    /* S Image class */

    class imgResize {
        /**
         * vxResize
         *
         * @param string $srcFile source file
         * @param string $srcFile destination file
         * @param int $dstW width of destination file (pixel)
         * @param int $dstH height of destination file (pixel)
         * @param int $option options, you add multiple options like 1+2(or 1|2), this utilize function 1 & 2
         *      1: defaultï¼?output to file 2: output to browser stream 4: free scale
         *      8ï¼?scale to width 16ï¼?scale to height
         * @param int $cutmode clipping method 0: default 1: left or top 2: middle 3: right or bottom
         * @param int $startX start X axis (pixel)
         * @param int $startY start Y axis (pixel)
         * @return array return[0]=0: OK; return[0] error code return[1] string: error description
         */

         function __construct($srcFile, $dstFile, $dstW, $dstH, $option=OP_TO_FILE, $cutmode=CM_DEFAULT, $startX=0, $startY=0) {
            $img_type = array(1=>"gif", 2=>"jpeg", 3=>"png");
            $type_idx = array("gif"=>1, "jpg"=>2, "jpeg"=>2, "jpe"=>2, "png"=>3);

            if (!file_exists($srcFile)) {
                return array(-1, "Source file not exists: $srcFile.");
            }

            $path_parts = @pathinfo($dstFile);
            $ext = strtolower ($path_parts["extension"]);

            if ($ext == "") {
                return array(-5, "Can't detect output image's type.");
            }

            $func_output = "image" . $img_type[$type_idx[$ext]];

            if (!function_exists ($func_output)) {
                return array(-2, "Function not exists for outputï¼?$func_output.");
            }

            $data = @GetImageSize($srcFile);
            $func_create = "imagecreatefrom" . $img_type[$data[2]];

            if (!function_exists ($func_create)) {
                return array(-3, "Function not exists for createï¼?$func_create.");
            }

            $im = @$func_create($srcFile);

            $srcW = @ImageSX($im);
            $srcH = @ImageSY($im);
            $srcX = 0;
            $srcY = 0;
            $dstX = 0;
            $dstY = 0;

            if ($option & OP_BEST_RESIZE_WIDTH) {
                $dstH = round($dstW * $srcH / $srcW);
            }

            if ($option & OP_BEST_RESIZE_HEIGHT) {
                $dstW = round($dstH * $srcW / $srcH);
            }

            $fdstW = $dstW;
            $fdstH = $dstH;

            if ($cutmode != CM_DEFAULT) { // clipping method 1: left or top 2: middle 3: right or bottom

                $srcW -= $startX;
                $srcH -= $startY;

                if ($srcW*$dstH > $srcH*$dstW) {
                    $testW = round($dstW * $srcH / $dstH);
                    $testH = $srcH;
                } else {
                    $testH = round($dstH * $srcW / $dstW);
                    $testW = $srcW;
                }

                switch ($cutmode) {
                    case CM_LEFT_OR_TOP: $srcX = 0; $srcY = 0; break;
                    case CM_MIDDLE: $srcX = round(($srcW - $testW) / 2);
                                    $srcY = round(($srcH - $testH) / 2); break;
                    case CM_RIGHT_OR_BOTTOM: $srcX = $srcW - $testW;
                                             $srcY = $srcH - $testH;
                }

                $srcW = $testW;
                $srcH = $testH;
                $srcX += $startX;
                $srcY += $startY;

            } else {
                if (!($option & OP_NOT_KEEP_SCALE)) {
                    if ($srcW*$dstH>$srcH*$dstW) {
                        $fdstH=round($srcH*$dstW/$srcW);
                        $dstY=floor(($dstH-$fdstH)/2);
                        $fdstW=$dstW;
                    } else {
                        $fdstW=round($srcW*$dstH/$srcH);
                        $dstX=floor(($dstW-$fdstW)/2);
                        $fdstH=$dstH;
                    }

                    $dstX=($dstX<0)?0:$dstX;
                    $dstY=($dstX<0)?0:$dstY;
                    $dstX=($dstX>($dstW/2))?floor($dstW/2):$dstX;
                    $dstY=($dstY>($dstH/2))?floor($dstH/s):$dstY;

                }
            }

            if( function_exists("imagecopyresampled") and
                function_exists("imagecreatetruecolor") ){
                $func_create = "imagecreatetruecolor";
                $func_resize = "imagecopyresampled";
            } else {
                $func_create = "imagecreate";
                $func_resize = "imagecopyresized";
            }

            $newim = @$func_create($dstW,$dstH);
            $black = @ImageColorAllocate($newim, 255,255,255);
            $back = @imagecolortransparent($newim, $black);
            @imagefilledrectangle($newim,0,0,$dstW,$dstH,$black);
            @$func_resize($newim,$im,$dstX,$dstY,$srcX,$srcY,$fdstW,$fdstH,$srcW,$srcH);

            if ($option & OP_TO_FILE) {
                switch ($type_idx[$ext]) {
                    case 1:
                    case 3:
                        @$func_output($newim,$dstFile);
                        break;
                    case 2:
                        @$func_output($newim,$dstFile,100);
                        break;
                }
            }

            if ($option & OP_OUTPUT) {
                if (function_exists("headers_sent")) {
                    if (headers_sent()) {
                        return array(-4, "HTTP already sent, can't output image to browser.");
                    }
                }
                header("Content-type: image/" . $img_type[$type_idx[$ext]]);
                @$func_output($newim);
            }

            @imagedestroy($im);
            @imagedestroy($newim);

            return array(0, "OK");
        }
    }
