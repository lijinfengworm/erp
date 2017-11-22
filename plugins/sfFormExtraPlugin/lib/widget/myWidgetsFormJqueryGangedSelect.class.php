<?php
class myWidgetsFormJqueryGangedSelect extends sfWidgetForm
{
	protected function configure($options = array(), $attributes = array())
	{
		parent::configure($options, $attributes);
		$this->addRequiredOption('url');
		$this->addRequiredOption('firstSelectData');
		$this->addOption('template', <<<EOF
%sel_1% %sel_2% %sel_3%
EOF
		);
	}

	public function render($name, $value = null, $attributes = array(), $errors = array())
	{
		$nameid = strtr($name, array('['=>'_',']'=>'')); 
		
		$associatedWidget1 = new sfWidgetFormSelect(array('choices' => $this->getOption('firstSelectData')),array('onchange'=>'cacleSelectNext("'.$nameid.'");ajaxSelectOptions("'.$this->getOption('url').'","selected_options_1","selected_options_2");'));
		$associatedWidget2 = new sfWidgetFormSelect(array('choices' => array(''=>'请选择')),array('onchange'=>'ajaxSelectOptions("'.$this->getOption('url').'","selected_options_2","'.$nameid.'");'));
		$associatedWidget3 = new sfWidgetFormSelect(array('choices' => array(''=>'请选择')));
		return strtr($this->getOption('template'), array(
      '%sel_1%'          => $associatedWidget1->render('selected_options_1'),
      '%sel_2%'          => $associatedWidget2->render('selected_options_2'),
      '%sel_3%'          => $associatedWidget3->render($name),
		));
	}
	
  public function getJavascripts()
  {
    //return array('/sfFormExtraPlugin/js/select_list.js');
  }
}
