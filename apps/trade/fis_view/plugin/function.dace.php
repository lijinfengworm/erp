<?php
/**
dace
 */

function smarty_function_dace($params, &$smarty) {
    echo '<script type="text/javascript">';
    $routname = 'xx';//sfContext::getInstance()->getRouting()->getCurrentRouteName();
    if (preg_match("/\/haitao/", $_SERVER['REQUEST_URI'])):
        echo "window.__daceDataNameOfChannel = 'sh_m_haitao'";
    elseif (preg_match("/\/daigou/", $_SERVER['REQUEST_URI'])):
        echo "window.__daceDataNameOfChannel = 'sh_m_haitao'";
     elseif (preg_match("/\/tuangou/", $_SERVER['REQUEST_URI'])):
        echo "window.__daceDataNameOfChannel = 'sh_m_tuangou'";
     elseif (preg_match("/\/youhui/", $_SERVER['REQUEST_URI'])):
        echo "window.__daceDataNameOfChannel = 'sh_m_youhui'";
     elseif (preg_match("/\/shoe/", $_SERVER['REQUEST_URI'])):
         echo "window.__daceDataNameOfChannel = 'sh_m_shoe'";
     elseif (preg_match("/\/find/", $_SERVER['REQUEST_URI']) || preg_match("/\/detail/",$_SERVER['REQUEST_URI'])):
        echo "window.__daceDataNameOfChannel = 'sh_m_find'";
     elseif (preg_match("/\/shaiwu/", $_SERVER['REQUEST_URI'])):
         echo "window.__daceDataNameOfChannel = 'sh_m_shaiwu'";
     elseif (preg_match("/\/special/", $_SERVER['REQUEST_URI'])):
        echo "window.__daceDataNameOfChannel='sh_m_special'";
     elseif (preg_match("/\/shop/", $_SERVER['REQUEST_URI'])):
         echo "window.__daceDataNameOfChannel = 'sh_m_dianpu'";
     elseif ($routname == 'homepage') :
        echo "window.__daceDataNameOfChannel ='sh_m_home'";
     endif;
    echo "</script>";

    echo '<script src="http://b3.hoopchina.com.cn/web/module/dace/m/m_dace.js"></script>';
    $daceAnalyticsImageUrl = 'xx';//daceVid::daceAnalyticsGetImageUrl();
    echo "<noscript><link rel=\"stylesheet\" type=\"text/css\" href=\"$daceAnalyticsImageUrl\"/></noscript>\n";
}

