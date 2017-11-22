<?php

//排序
function itemSort($a, $b) {
    if($a["click_count"] == $b["click_count"]) return 0; 
    return ($a["click_count"] > $b["click_count"]) ? -1 : 1;
}
