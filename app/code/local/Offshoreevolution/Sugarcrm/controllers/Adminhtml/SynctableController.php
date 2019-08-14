<?php
class Offshoreevolution_Sugarcrm_Adminhtml_SynctableController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{	
		$this->loadLayout();
		$data = $this->getRequest()->getPost();
		if($data['module'] == 'none')
		{
			Mage::getSingleton('core/session')->addError('Please Select a Module');
			$this->_redirect('*/sugarsetting/modulelist');
		}
		if($this->getRequest()->getParam('module'))
		{
			Mage::getSingleton("core/session")->setFiltermodule($data['module']);
		}
		
		$block = Mage::app()->getLayout()->getBlock('synctable');
		$block->setModule($data['module']);

		$this->_setActiveMenu('sugarcrm_settings/form');
		$this->renderLayout();
	}
	
	public function EditAction()
	{
		$id = $this->getRequest()->getParam('id');
		$data = Mage::getModel('sugarcrm/settings')->load($id);
		if($data->getId() || $id == 0)
		{
			Mage::register('syncTable', $data);
			$this->loadLayout();
			$this->_setActiveMenu('sugarcrm_settings/form');
			$this->renderLayout();
		}
		else
		{
			Mage::getSingleton('core/session')->addError('Record does not exist');
			$this->_redirect('*/*/');
		}
	}
	
	public function NewAction()
	{
		//$this->_forward('edit');
	}
	
	public function saveAction()
	{
		
	}
	
	public function BridgeAction()
	{
		//$attributeInfo = Mage::getResourceModel('sales/order_invoice_collection');
		//echo "<pre>"; print_r($attributeInfo->getData()); echo "</pre>";
		$this->loadLayout();
		$this->_setActiveMenu('sugarcrm_settings/form');
		$this->renderLayout();
	}
}
?>