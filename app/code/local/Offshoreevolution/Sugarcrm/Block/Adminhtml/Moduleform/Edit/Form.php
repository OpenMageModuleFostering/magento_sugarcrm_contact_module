<?php
class Offshoreevolution_Sugarcrm_Block_Adminhtml_Moduleform_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
/**
* Preparing form
*
* @return Mage_Adminhtml_Block_Widget_Form
*/
	protected function _prepareForm()
	{		
		$form = new Varien_Data_Form(
								  array(
								  	'id' => 'edit_form',
								  	'action' => $this->getUrl('*/synctable/index'),
									//'onsubmit' => 'ajaxCall()',
								  	'method' => 'post',
								  ));
		 
		$form->setUseContainer(true);
		$this->setForm($form);
		 
		$helper = Mage::helper('sugarcrm');
		$allModule = $helper->getSugarObject()->ModuleList;
		
		$fieldset = $form->addFieldset('display', array(
									  'legend' => $helper->__('SugarCRM modules List'),
									  //'class' => 'fieldset-wide'
													   ));
		
		$moduleArray = array();
		$moduleArray['none'] = 'Please Select..';
		foreach($allModule as $val)
		{
			$moduleArray[$val] = $val;
		}
		
		$fieldset->addField('modulelist', 'select', array(
									  'label'     => Mage::helper('sugarcrm')->__('Select Module'),
									  'class'     => 'required-entry',
									  'required'  => true,
									  'name'      => 'module',
									  'onchange' => "",
									  //'value'  => '4',
									  'values' => $moduleArray,
									  'disabled' => false,
									  'readonly' => false,
									  //'after_element_html' => '&nbsp;&nbsp;<button id="import">Import</button>',
									  'tabindex' => 1
        ));
		 
		if (Mage::registry('sugarcrm'))
		{
			$form->setValues(Mage::registry('sugarcrm')->getData());
		}
		 
		return parent::_prepareForm();
	}
}
	
?>