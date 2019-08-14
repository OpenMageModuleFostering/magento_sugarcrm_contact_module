<?php

class Offshoreevolution_Sugarcrm_Model_Observer extends Varien_Event_Observer
{
	private $sugarObj = '';
	public function __construct()
	{
		define ( OPEL_MODULE_PATH, Mage::getBaseDir('code').'/local/Offshoreevolution/Sugarcrm/') ;
		require_once( OPEL_MODULE_PATH . 'oepl.SugarCRM.cls.php');
		$OEPLSugarObj = new ClassOEPL();
		
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$resultRs = $read->fetchAll("SELECT * FROM oepl_sugar");		

		$OEPLSugarObj->SugarURL 	= $resultRs[0]['meta_value'];
		$OEPLSugarObj->SugarUser 	= $resultRs[1]['meta_value'];
		$OEPLSugarObj->SugarPass 	= $resultRs[2]['meta_value'];
		
		if($OEPLSugarObj->SugerSessID == '')
		{
			$OEPLSugarObj->LoginToSugar();
			$this->sugarObj = $OEPLSugarObj;
			//Mage::register('OEPLSugarObj', $OEPLSugarObj);
		}
	}
	
	public function syncsugarcrm($observer)
	{		
		$event = $observer->getCustomer();
		$customerData = $event->getData();
		$defaultBilling = $event->getDefaultBillingAddress()->getData();
		$defaultShipping = $event->getDefaultShippingAddress()->getData();

		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write  = Mage::getSingleton('core/resource')->getConnection('core_write');

		$fieldsToExport = $read->fetchAll("SELECT * FROM oepl_map_fields WHERE module = 'Contacts' AND `mag_field` != ''");
		$permission 	= $read->fetchAll("SELECT * FROM oepl_module_access_list WHERE module_name = 'Contacts'");
		$Flag = true;
		$name_value_list = array();
		foreach ($fieldsToExport as $temp)
		{
			if($temp['mag_field_type'] == "Billing"){
				$name_value_list[$temp['field_name']] = $defaultBilling[$temp['mag_field']];
			} else if ($temp['mag_field_type'] == "Shipping"){
				$name_value_list[$temp['field_name']] = $defaultShipping[$temp['mag_field']];
			} else {
				$name_value_list[$temp['field_name']] = $customerData[$temp['mag_field']] ;
			}
		}
		$query2 = "SELECT sugar_id FROM oepl_sugar_mag_usermap 
				   WHERE mag_id = '".$customerData['entity_id']."'";
		$sugarID = $read->fetchAll($query2);
		if(!empty($sugarID) && $sugarID != '')
		{
			$name_value_list['id'] = $sugarID[0]['sugar_id'];
		}
		$OEPLSugarObj = $this->sugarObj;		
		if($OEPLSugarObj->SugerSessID != '')
		{
			if(!empty($sugarID)){
				if($permission[0]['is_update'] == 'N'){
					$Flag = false;
				}
			} else {
 				if($permission[0]['is_insert'] == 'N'){
					$Flag = false;
				}
			}
			if($Flag){
				$set_entry_parameters = array(	 "session" 			=> $OEPLSugarObj->SugerSessID,
											 "module_name"		=> "Contacts",
											 "name_value_list" 	=> $name_value_list
										 );
				$SugarResult = $OEPLSugarObj->SugarCall("set_entry", $set_entry_parameters);
			}
			if(empty($sugarID)){
				if($Flag){
					$query = "INSERT INTO oepl_sugar_mag_usermap 
					  SET mag_id='".$customerData['entity_id']."',sugar_id='".$SugarResult->id."'";
					$write->query($query);
				}
			}
		}
	}
	
	public function guest_user_sync($observer){
		if(!Mage::helper('customer')->isLoggedIn()){
           	
			
			$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			$write  = Mage::getSingleton('core/resource')->getConnection('core_write');
	
			$fieldsToExport = $read->fetchAll("SELECT * FROM oepl_map_fields WHERE module = 'Contacts' AND `mag_field` != ''");
			$permission 	= $read->fetchAll("SELECT * FROM oepl_module_access_list WHERE module_name = 'Guest_Order_Sync'");
			if($permission[0]['is_insert'] && $permission[0]['is_insert'] == 'Y')
			{
				$order = $observer->getEvent()->getOrder();
				$billingAddress = $order->getBillingAddress();
				$shippingAddress = $order->getShippingAddress();
				$name_value_list = array();
				foreach ($fieldsToExport as $temp)
				{
					if($temp['mag_field_type'] == "Billing"){
						$name_value_list[$temp['field_name']] = $billingAddress[$temp['mag_field']];
					} else if ($temp['mag_field_type'] == "Shipping"){
						$name_value_list[$temp['field_name']] = $shippingAddress[$temp['mag_field']];
					} else {
						$name_value_list[$temp['field_name']] = $billingAddress[$temp['mag_field']] ;
					}
				}
				$OEPLSugarObj = $this->sugarObj;
				$set_entry_parameters = array( "session" 			=> $OEPLSugarObj->SugerSessID,
											   "module_name"		=> "Contacts",
											   "name_value_list" 	=> $name_value_list
											 );
				$SugarResult = $OEPLSugarObj->SugarCall("set_entry", $set_entry_parameters);
			}
		}
	}
	
	public function userDelete($observer)
	{
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write  = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		$data = $observer->getEvent()->getData();
		$id = $data['object']->_data['entity_id'];
		if($id && $id != ''){
			$permission = $read->fetchAll("SELECT * FROM oepl_module_access_list WHERE module_name = 'Contacts'");
			if($permission[0]['is_delete'] == 'Y')
			{
				$query = "SELECT sugar_id FROM oepl_sugar_mag_usermap 
											WHERE mag_id = '".$id."'";
				
				$sugarIdRs = $read->fetchAll($query);
				$sugarID = $sugarIdRs[0]['sugar_id'];
				$_fields = array(
									'id' => $sugarID,
									'deleted' => 1
								);
				
				$OEPLSugarObj = $this->sugarObj;
				if($OEPLSugarObj->SugerSessID != '')
				{
					$set_entry_parameters = array(	 "session" 			=> $OEPLSugarObj->SugerSessID,
													 "module_name"		=> "Contacts",
													 "name_value_list" 	=> $_fields
												 );
					$SugarResult = $OEPLSugarObj->SugarCall("set_entry", $set_entry_parameters);
				}
				if($sugarID && $sugarID != '')
				{
					$where = "mag_id = ".$id." AND sugar_id = '".$sugarID."'"; 		
					$write->delete('oepl_sugar_mag_usermap', $where);	
				}
			}
		}
	}
	
}
?>