<?php
class Offshoreevolution_Sugarcrm_Block_Adminhtml_Useroperations_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		$resultRs = $read->fetchAll("SELECT * FROM oepl_module_access_list");
				
		$form = new Varien_Data_Form(
								  array(
								  	'id' => 'edit_form',
								  	'action' => $this->getUrl('*/*/accesssave'),
									//'onsubmit' => 'ajaxCall()',
								  	'method' => 'post',
								  ));
		 
		$form->setUseContainer(true);
		$this->setForm($form);
		
		require_once( OPEL_MODULE_PATH . 'oepl.SugarCRM.cls.php');
		$test = new ClassOEPL();
		$modules = $test->ModuleList;
		
		$helper = Mage::helper('sugarcrm');
		$fieldset = $form->addFieldset('display', array(
				'legend' => $helper->__('Access Control'),
		));
		$i = 0;
		foreach($modules as $key=>$val)
		{
			$temp = $val;
			$temp =	$fieldset->addFieldset($val, array(
					'legend' => $helper->__($val." Module"),
			));
			
			$temp->addField($val.'Insert', 'select', array(
					'label'     => Mage::helper('sugarcrm')->__('Insert'),
					'name'      => $val.'Insert',
					'onclick' => "",
					'onchange' => "",
					'value'  => $resultRs[$i]['is_insert'],
					'values' => array(
									  array('value'=>'N','label'=>'Disbled'),
									  array('value'=>'Y','label'=>'Enabled'),
								 ),
					'disabled' => false,
					'readonly' => false,
					'tabindex' => 1
			));
			$temp->addField($val.'Update', 'select', array(
					'label'     => Mage::helper('sugarcrm')->__('Update'),
					'name'      => $val.'Update',
					'onclick' => "",
					'onchange' => "",
					'value'  => $resultRs[$i]['is_update'],
					'values' => array(
									  array('value'=>'N','label'=>'Disbled'),
									  array('value'=>'Y','label'=>'Enabled'),
								 ),
					'disabled' => false,
					'readonly' => false,
					'tabindex' => 1
			));

			$temp->addField($val.'Delete', 'select', array(
					'label'     => Mage::helper('sugarcrm')->__('Delete'),
					'name'      => $val.'Delete',
					'onclick' => "",
					'onchange' => "",
					'value'  => $resultRs[$i]['is_delete'],
					'values' => array(
					                  array('value'=>'N','label'=>'Disbled'),
									  array('value'=>'Y','label'=>'Enabled'),
								 ),
					'disabled' => false,
					'readonly' => false,
					'tabindex' => 1
			));
			$i++;
			$temp->addField($val.'Hidden', 'hidden', array(
					'name'      => $val,
					'value'  => $val,
			));
		}
		$Guest_user = $fieldset->addFieldset('Guest_Order_Sync', array(
				'legend' => $helper->__("Insert Guest order customer information into SugarCRM Contact Module as a new Contact"),
		));
		$Guest_user->addField('guest_order_sync', 'select', array(
				'label'     => Mage::helper('sugarcrm')->__('Status'),
				'name'      => 'guest_order_sync',
				'onclick' => "",
				'onchange' => "",
				'value'  => $resultRs[$i]['is_insert'],
				'values' => array(
								  array('value'=>'N','label'=>'Disbled'),
								  array('value'=>'Y','label'=>'Enabled'),
							 ),
				'disabled' => false,
				'readonly' => false,
				'after_element_html' => '<a class="OEPL_show_info" style="margin-left:15px;cursor:pointer">Click here</a> for help',
				'tabindex' => 1
		));
		/*if (Mage::registry('sugarcrm'))
		{
			$form->setValues(Mage::registry('sugarcrm')->getData());
		}*/
		 
		return parent::_prepareForm();
	}
}
?>