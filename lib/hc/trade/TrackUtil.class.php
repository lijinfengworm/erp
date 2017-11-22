<?php
class TrackUtil {
    static public function create($track_arr = array(), $plus = array()) {
        if(!empty($plus)) {
            foreach($plus as $item) {
                $track_arr[] = $item;
            }
        }

        return join("-", $track_arr);
    }

}

