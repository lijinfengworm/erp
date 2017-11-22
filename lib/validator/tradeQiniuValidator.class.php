<?php
class traderQiniuValidator extends sfValidatedFile
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
		$qiuniu_path = $qiniuObj->uploadFile('tmp/'.rand(0,11111111),$this->getTempName());
		$this->savedName = $qiuniu_path;
		return $qiuniu_path;
	}
}
?>
