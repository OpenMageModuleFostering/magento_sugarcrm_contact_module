<?php
class Offshoreevolution_Sugarcrm_Block_Adminhtml_Moduleform_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_blockGroup = 'sugarcrm';
		$this->_controller = 'adminhtml_moduleform';
		//$this->_removeButton('save');
		$this->_updateButton('save', 'label','Load Fields Mapping');
		$this->_removeButton('back');
		$this->_removeButton('reset');
		$this->_headerText = Mage::helper('sugarcrm')->__('Modules List');
	}
}
?>