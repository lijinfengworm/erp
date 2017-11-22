<?php

class myTradeTagListInputValidator extends sfValidatorDoctrineChoice {
 
     public function configure($options = array(), $attributes = array()) {
        $this->addRequiredOption('separator');
        $this->addRequiredOption('autosave');
        
        $this->addOption('replace', empty($options['replace'])?false:$options['replace']);
        $this->setOption('separator', $options['separator']);
        //没这个tag的话自动添加
        $this->setOption('autosave', empty($options['autosave'])?true:$options['autosave']);
        
        parent::configure($options, $attributes);
    } 
    
    protected function doClean($value) {
        
        
        $value = trim($value, $this->getOption('separator'));
        //对经常用错的中英文符号进行替换
        if($this->getOption('replace'))
        {
            $value = str_replace($this->getOption('replace'), $this->getOption('separator'), $value);
        }
        $value = array_filter(explode($this->getOption('separator'), $value));
        
        return  array_filter(trdProductTagTable::getTagIdsByNamesAndCategory($value,$this->getOption('autosave')?$this->getOption('autosave'):false ));        
    }

}
