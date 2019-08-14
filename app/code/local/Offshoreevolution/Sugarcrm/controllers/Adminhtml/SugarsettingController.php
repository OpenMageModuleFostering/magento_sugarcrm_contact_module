<?php
define ( OPEL_MODULE_PATH, Mage::getBaseDir('code').'/local/Offshoreevolution/Sugarcrm/') ;
class Offshoreevolution_Sugarcrm_Adminhtml_SugarsettingController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
		//$resultRs2 = Mage::getModel('sales/order')->getCollection()->getData();
		//echo "<pre>"; print_r($resultRs2); echo "</pre>";
		$this->loadLayout();
		$this->_setActiveMenu('sugarcrm_settings/form');
		$this->_addBreadcrumb(Mage::helper('sugarcrm')->__('form'), Mage::helper('sugarcrm')->__('form'));
		$this->renderLayout();
    }
	
	public function ModulelistAction()
	{
		$this->loadLayout();	
		$this->_setActiveMenu('sugarcrm_settings/form');
		$this->renderLayout();
	}
	
	public function UseroperationsAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('sugarcrm_settings/form');
		$this->renderLayout();
	}
	
	public function AccesssaveAction()
	{
		require_once( OPEL_MODULE_PATH . 'oepl.SugarCRM.cls.php');
		$test = new ClassOEPL();
		
		$modules = $test->ModuleList;
		$data = $this->getRequest()->getPost();
		//echo "<pre>"; print_r($data); exit;
 		$read 	= Mage::getSingleton('core/resource')->getConnection('core_read');
		$write  = Mage::getSingleton('core/resource')->getConnection('core_write');
		$fields = array();
		$read->query('truncate table oepl_module_access_list');
		foreach ($modules as $key=>$val)
		{
			$fields['module_name'] = $val;
			$fields['is_insert'] = $data[$val."Insert"];
			$fields['is_update'] = $data[$val."Update"];
			$fields['is_delete'] = $data[$val."Delete"];
			$write->insert('oepl_module_access_list', $fields);
		}
		if($data['guest_order_sync'] == 'Y'){
			$fields['module_name'] = 'Guest_Order_Sync';
			$fields['is_insert'] = 'Y';
			$fields['is_update'] = 'N';
			$fields['is_delete'] = 'N';
			$write->insert('oepl_module_access_list', $fields);
		} else {
			$fields['module_name'] = 'Guest_Order_Sync';
			$fields['is_insert'] = 'N';
			$fields['is_update'] = 'N';
			$fields['is_delete'] = 'N';
			$write->insert('oepl_module_access_list', $fields);
		}
		Mage::getSingleton('core/session')->addSuccess('Data Saved successfully');
		$this->_redirect('*/*/Useroperations');
	}
	
	public function saveAction()
	{
		if($this->getRequest()->getPost())
		{
			$data = $this->getRequest()->getPost();
			require_once( OPEL_MODULE_PATH . 'oepl.SugarCRM.cls.php');
			$test = new ClassOEPL();
					
			$test->SugarURL = $data['url'];
			$test->SugarUser = $data['username'];
			$test->SugarPass = md5($data['password']);
			if($test->LoginToSugar())
			{
				$read = Mage::getSingleton('core/resource')->getConnection('core_read');
				$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
				$connection->beginTransaction();
				
				$password = $read->fetchAll("select * from oepl_sugar where meta_key = 'password'");
				$read->query('truncate table oepl_sugar');
				$__fields = array();
				$skiparray = array('form_key','password');
				foreach($data as $key=>$value)
				{
					$flag = false;
					if(!in_array($key,$skiparray))
					{
						$__fields['meta_key'] = $key;
						$__fields['meta_value'] = $value;
						$flag = true;		
					}
					if($key == 'password')
					{
						if($value != '' && $value != NULL)
						{
							$flag = true;
							$__fields['meta_key'] = $key;
							$__fields['meta_value'] = md5($value);			
						}
						else
						{
							$flag = true;
							$__fields['meta_key'] = $key;
							$__fields['meta_value'] = $password[0]['meta_value'];						
						}
					}
					if($flag == true)
					{
						$connection->insert('oepl_sugar', $__fields);
					}
				}
				Mage::getSingleton('core/session')->addSuccess('Configuration saved successfully');
				$connection->commit();
				$this->_redirect('*/*/');
			} else {
				Mage::getSingleton('core/session')->addError('Invalid login details. Please try again');
				$this->_redirect('*/*/');
			}			
		}		
	}
	
	public function testAction()
	{
		if($this->getRequest()->getPost())
		{
			$data = $this->getRequest()->getPost();
			require_once( OPEL_MODULE_PATH . 'oepl.SugarCRM.cls.php');
			$test = new ClassOEPL();
					
			$test->SugarURL = $data['url'];
			$test->SugarUser = $data['username'];
			$test->SugarPass = md5($data['password']);
			if($test->LoginToSugar())
			{
				echo "true";
			} else {
				echo "false";
			}
		}
	}	
	
	public function SavesortedAction()
	{
		$data = json_decode($_POST[data]); 
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');

		$pid = array();
		$id  = array();
		$module = $data->module;
		$pid1   = $data->pid1;
		$pid2   = $data->pid2;
		$limit1 = count($pid1);
		$limit2 = count($pid2);
		
	### Col 1 filler add START
		if($pid1 == -1 && $pid2 == -1)
		{
			$query = "INSERT INTO oepl_map_fields 
					  SET module='".$module."' , wp_meta_label='Filler' ,field_type='filler' ,display_order='0' ,is_show='Y' ,show_column='1'";
			$write->query($query);
		}
	### Col 1 filler add END
	### Col 2 filler add START
		else if($pid1 == -2 && $pid2 == -2)
		{
			$query = "INSERT INTO oepl_map_fields 
					  SET module='".$module."' ,wp_meta_label='Filler' ,field_type='filler' ,display_order='0' ,is_show='Y' ,show_column='2'";
			$write->query($query);
		}
	### Col 2 filler add END
	
	### Sorting Column 1-2 START
		else
		{
			$query = "UPDATE oepl_map_fields 
					  SET is_show ='N' 
					  WHERE module='".$module."'";
			$write->query($query);
			### sorting Column1
			for ($i=0;$i<$limit1;$i++)
			{
				$query1 = "UPDATE oepl_map_fields 
						   SET display_order=".$i.",is_show ='Y',show_column='1' 
						   WHERE pid=".$pid1[$i]." and module='".$module."'";
				$write->query($query1);
			}
			### sorting Column2
			for ($j=0;$j<$limit2;$j++)
			{
				$query2 = "UPDATE oepl_map_fields 
						   SET display_order=".$j.",is_show ='Y',show_column='2' 
						   WHERE pid=".$pid2[$j]." and module='".$module."'";
				$write->query($query2);
			}
		}
	###Sorting Column 1-2 END
		
		$where = "field_type = 'filler' AND module = '".$module."' AND is_show ='N'"; 
		
		$write->delete('oepl_map_fields', $where);
	}
}
?>