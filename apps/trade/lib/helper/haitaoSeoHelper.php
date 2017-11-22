<?php
/*海淘列表页seo路由*/
function seo_link($urlName,$type,$infos){   //生成seo链接
    $_param = array();
    if(isset($infos['w']) && $infos['w']) $_param['w'] = $infos['w'];
    if(isset($infos['sort']) && $infos['sort']) $_param['sort'] = $infos['sort'];

    switch($type){
        case 'store_id':
            $flag = $infos['root_id'] || $infos['root_type'] ?  true : false;
            if($flag){
                if(!$urlName){
                    $url =  '/haitao/youhui/'.$infos['root_id'].'-'.$infos['root_type'].'/all';
                }else{
                    $url = '/haitao/youhui/'.$infos['root_id'].'-'.$infos['root_type'].'/'.$urlName;
                }
            }else{
                if($urlName){
                    $url = '/haitao/'.$urlName;
                }else{
                    $url = '/haitao/youhui';
                }
            }

            break;
        case 'root_id':
            $flag = $infos['root_type'] || $infos['store_id'] ? true : false;
            if($flag){
                if($infos['store_name']){
                    $url = '/haitao/youhui/'.$urlName.'-'.$infos['root_type'].'/'.$infos['store_name'];
                }else{
                    $url = '/haitao/youhui/'.$urlName.'-'.$infos['root_type'].'/all';
                }
            }else{
                if($urlName){
                    $url = '/haitao/youhui/'.$urlName;
                }else{
                    $url = '/haitao/youhui';
                }
            }
            break;
        case 'w':
            if($infos['root_id'] && $infos['store_id']){
                $url = '/haitao/youhui/'.$infos['root_id'].'-0/'.$infos['store_name'];
            }elseif($infos['root_id']){
                $url = '/haitao/youhui/'.$infos['store_name'];
            }elseif($infos['store_id']){
                $url = '/haitao/youhui/'.$infos['root_id'];
            }else{
                $url = '/haitao/youhui';
            }

            if(!$urlName){
                unset($_param['w']);
            }
            break;
        default:
            $flag = $infos['root_type'] || $infos['store_id'] || $infos['root_id'] ? true : false;
            if($flag){
                if($infos['store_name']){
                    $url = '/haitao/youhui/'.$infos['root_id'].'-'.$infos['root_type'].'/'.$infos['store_name'];
                }else{
                    $url = '/haitao/youhui/'.$infos['root_id'].'-'.$infos['root_type'].'/all';
                }
            }else{
                $url = '/haitao/youhui';
            }
            break;
    }

    if($_param) $url .= '?'.http_build_query($_param);
    return $url;
}

/*优惠列表页seo路由*/
function youhui_seo_link($urlName,$type,$infos){   //生成seo链接
    $_param = array();
    if(isset($infos['w']) && $infos['w']) $_param['w'] = $infos['w'];
    if(isset($infos['sort']) && $infos['sort']) $_param['sort'] = $infos['sort'];
    if(isset($infos['type']) && $infos['type']) $_param['type'] = $infos['type'];

    switch($type){
        case 'store_id':
            $flag = $infos['root_id'] || $infos['root_type'] ?  true : false;
            if($flag){
                if(!$urlName){
                    $url =  '/youhui/'.$infos['root_id'].'-'.$infos['root_type'].'/all';
                }else{
                    $url = '/youhui/'.$infos['root_id'].'-'.$infos['root_type'].'/'.$urlName;
                }
            }else{
                if($urlName){
                    $url = '/youhui/'.$urlName;
                }else{
                    $url = '/youhui/list';
                }
            }

            break;
        case 'root_id':
            $flag = $infos['root_type'] || $infos['store_id'] ? true : false;
            if($flag){
                if($infos['store_name']){
                    $url = '/youhui/'.$urlName.'-'.$infos['root_type'].'/'.$infos['store_name'];
                }else{
                    $url = '/youhui/'.$urlName.'-'.$infos['root_type'].'/all';
                }
            }else{
                if($urlName){
                    $url = '/youhui/'.$urlName;
                }else{
                    $url = '/youhui/list';
                }
            }
            break;

        case 'root_type':
            $flag = $infos['root_type'] || $infos['store_id'] || $infos['root_id'] ? true : false;
            if($flag){
                $store_name = $infos['store_id'] ? $infos['store_name'] : 'all' ;
                if($infos['root_type'] && strpos($infos['root_type'],$urlName)!==false){
                    $root_type_name = preg_replace('/'.$urlName.'/','',$infos['root_type']);
                    $root_type_name = $root_type_name ? $root_type_name : 0;
                }elseif($infos['root_type'] && strpos($infos['root_type'],$urlName) == false){
                    $root_type_name = $infos['root_type'].$urlName;
                }else{
                    $root_type_name = $urlName;
                }

                $url = '/youhui/'.$infos['root_id'].'-'.$root_type_name.'/'.$store_name;

            }else{
                $url = '/youhui/'.$infos['root_type_name'][$urlName];
            }

            break;
        case 'type':
            $flag = $infos['root_type'] || $infos['store_id'] || $infos['root_id'] ? true : false;
            if($flag){
                $store_name = $infos['store_id'] ? $infos['store_name'] : 'all' ;
                $root_type_name = $infos['root_type'];
                $url = '/youhui/'.$infos['root_id'].'-'.$root_type_name.'/'.$store_name;
            }else{
                $url = '/youhui/list';
            }
//            if($infos['type']){
//                $_param['type'] = 0;
//            }else{
//                $_param['type'] = 1;
//            }
            $_param['type'] = $urlName;
            break;
        case 'w':
            $flag = $infos['root_type'] || $infos['store_id'] || $infos['root_id'] ? true : false;
            if($flag){
                $store_name = $infos['store_id'] ? $infos['store_name'] : 'all' ;
                $root_type_name = $infos['root_type'];
                $url = '/youhui/'.$infos['root_id'].'-'.$root_type_name.'/'.$store_name;
            }else{
                $url = '/youhui/list';
            }

            $_param['w'] = $urlName;
            break;
    }

    if($_param) $url .= '?'.http_build_query($_param);
    return $url.'#qk=shaixuan';
}
