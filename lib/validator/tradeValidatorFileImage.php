<?php
class tradeValidatorFileImage extends sfValidatorFile{
	/**
	 * @param array $options   An array of options
	 * @param array $messages  An array of error messages
	 *
	 * @see sfValidatorFile
	 */
	protected function configure($options = array(), $messages = array())
	{

		parent::configure($options, $messages);

		$this->addMessage('invalid_image', '%value% is an incorrect image file.');
		$this->addMessage('max_height', '"%value%" height is too long (%max_height% pixels max).');
		$this->addMessage('min_height', '"%value%" height is too short (%min_height% pixels min).');
		$this->addMessage('max_width', '"%value%" width is too long (%max_width% pixels max).');
		$this->addMessage('min_width', '"%value%" width is too short (%min_width% pixels min).');

		$this->addOption('max_height');
		$this->addOption('min_height');
		$this->addOption('max_width');
		$this->addOption('min_width');
		$this->addOption('is_only_image',false);
        $this->addOption('width');
        $this->addOption('height');

		$this->addMessage('ratio', '高宽比例不符合"%value%"');
        $this->addMessage('width', '宽度只能是"%value%"像素');
        $this->addMessage('height','高宽只能是"%value%"像素');
		$this->addOption('ratio');

		$this->addMessage('ratioError', '误差');
		//允许误差范围
		$this->addOption('ratioError',10);
	}
	public function clean($value)
	{
		return parent::clean($value);
	}
	/**
	 * @see sfValidatorFile
	 */
	protected function doClean($value)
	{

		$clean = parent::doClean($value);

		$size = getimagesize($clean->getTempName());
		if (!$size){
			throw new sfValidatorError($this, 'invalid_image', array('value' => $value['name']));
		}


		list($width, $height) = $size;
		if($this->getOption('max_height') && $this->getOption('max_height') < $height){
			throw new sfValidatorError($this, 'max_height', array('value' => $value['name'], 'max_height' => $this->getOption('max_height')));
		}

		if($this->getOption('min_height') &&  $this->getOption('min_height') > $height){
			throw new sfValidatorError($this, 'min_height', array('value' => $value['name'], 'min_height' => $this->getOption('min_height')));
		}

		if($this->getOption('max_width') && $this->getOption('max_width') < $width){
			throw new sfValidatorError($this, 'max_width', array('value' => $value['name'], 'max_width' => $this->getOption('max_width')));
		}

		if($this->getOption('min_width') && $this->getOption('min_width') > $width){
			throw new sfValidatorError($this, 'min_width', array('value' => $value['name'], 'min_width' => $this->getOption('min_width')));
		}

		if($this->getOption('ratio'))
		{
			$ratioArray = explode('x',$this->getOption('ratio'));
			if (abs ($height/$ratioArray[0]*$ratioArray[1] - $width) > $this->getOption('ratioError'))
			{
				throw new sfValidatorError($this, 'ratio', array('value' => $this->getOption('ratio'), 'ratio' => $this->getOption('ratio')));
			}
		}

        if($this->getOption('width') && $this->getOption('width') != $width){
            throw new sfValidatorError($this, 'width', array('value' => $value['name'], 'width' => $this->getOption('width')));
        }

        if($this->getOption('height') && $this->getOption('height') != $height){
            throw new sfValidatorError($this, 'height', array('value' => $value['name'], 'height' => $this->getOption('height')));
        }

        return $clean;
	}
}