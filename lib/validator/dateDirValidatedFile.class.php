<?php

/*
 * 返回日期目录的文件名
 * 如： 120101/filename
 */

class dateDirValidatedFile extends sfValidatedFile{
    
    private $savedFilename = null;
    public function __construct($originalName, $type, $tempName, $size, $path = null) {
        parent::__construct($originalName, $type, $tempName, $size, $path);
        //自动加上日期目录
        $this->path = $path.date('ymd').'/';
    }

    public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) {
        //返回的时候也加上目录
        return date('ymd') . '/' . parent::save($file, $fileMode, $create, $dirMode);
    }
    
}

?>
