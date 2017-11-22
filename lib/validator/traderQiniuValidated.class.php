<?php
class traderQiniuValidated extends sfValidatedFile
{
	/**
	 * Constructor.
	 *
	 * @param string $originalName  The original file name
	 * @param string $type          The file content type
	 * @param string $tempName      The absolute temporary path to the file
	 * @param int    $size          The file size (in bytes)
	 * @param string $path          The path to save the file (optional).
	 */
	public function __construct($originalName, $type, $tempName, $size, $path = null)
	{
		$this->originalName = $originalName;
		$this->tempName = $tempName;
		$this->type = $type;
		$this->size = $size;
		$this->path = $path;
	}
	/**
	 * Returns the name of the saved file.
	 */
	public function __toString()
	{
		return null === $this->savedName ? '' : $this->savedName;
	}
	public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
	{

		$qiniuObj = new tradeQiNiu();
		$qiuniu_path = $qiniuObj->uploadFile($this->path.'/'.date('Ym').'/'.date('dH').'/'.md5(microtime().rand(0,10000)).$this->getExtension(),$this->getTempName());
		if(empty($qiuniu_path))
		{
			throw new sfValidatorError(new sfValidatorFile(array(),array('invalid'=>'上传到图片服务器失败')), 'invalid', array());
		}
		$this->savedName = $qiuniu_path;
		return $qiuniu_path;
	}

    public function getQiniuPath()
    {
        return $this->path.'/'.date('Ym').'/'.date('dH').'/'.md5(microtime().rand(0,10000)).$this->getExtension();
    }
}
?>
