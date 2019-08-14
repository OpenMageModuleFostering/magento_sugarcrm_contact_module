<?php
class Offshoreevolution_Sugarcrm_Block_Adminhtml_Useroperations_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_blockGroup = 'sugarcrm';
		$this->_controller = 'adminhtml_useroperations';
		$this->_headerText = Mage::helper('sugarcrm')->__('User Operations');
	}
}
?>