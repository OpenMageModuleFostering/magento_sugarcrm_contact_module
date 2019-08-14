<?php
class Offshoreevolution_Sugarcrm_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getContactsArray()
	{
		$resultRs2 = Mage::getModel('customer/entity_attribute_collection')->getData();
		$CustomerDetail = array();
		foreach ($resultRs2 as $temp2){
			$CustomerDetail[$temp2['attribute_code']] = $temp2['frontend_label'];
		}
		return $CustomerDetail;
	}
	
	public function getAddressEntityList(){
		$resultRs1 = Mage::getModel('customer/entity_address_attribute_collection')->getData();
		$AddressFields = array();
		foreach ($resultRs1 as $temp1){
			$AddressFields[$temp1['attribute_code']] = $temp1['frontend_label'];
		}
		return $AddressFields;
	}
	
	public function getSugarObject()
	{
		define ( OPEL_MODULE_PATH, Mage::getBaseDir('code').'/local/Offshoreevolution/Sugarcrm/') ;
		require_once( OPEL_MODULE_PATH . 'oepl.SugarCRM.cls.php');
		$OEPLSugarObj = new ClassOEPL();
		
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$resultRs = $read->fetchAll("SELECT * FROM oepl_sugar");		
		
		$OEPLSugarObj->SugarURL = $resultRs[0]['meta_value'];
		$OEPLSugarObj->SugarUser = $resultRs[1]['meta_value'];
		$OEPLSugarObj->SugarPass = $resultRs[2]['meta_value'];
		return $OEPLSugarObj;
	}
}
?>