<?php

/* 这个类有点问题 请用 dateDirValidatedFile
 * 返回日期目录的文件名
 * 如： 120101/filename
 */

class dateValidatedFile extends sfValidatedFile{
    
    private $savedFilename = null;

    public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) {
        return date('ymd') . '/' . parent::save($file, $fileMode, $create, $dirMode);
    }

}

?>
