<?php

class Setting_Model_DbTable_DbGeneral extends Zend_Db_Table_Abstract
{

    protected $_name = 'rms_setting';
    
    public function geLabelByKeyName($keyName){
    	$db = $this->getAdapter();
    	$sql = " SELECT s.`code`,s.keyName,s.keyValue 
				FROM `rms_setting` AS s
				WHERE s.status=1 
				AND s.`keyName` ='$keyName' LIMIT 1";
    	return $db->fetchRow($sql);
    }
	public function updateWebsitesetting($data){
		try{
			$dbg = new Application_Model_DbTable_DbGlobal();
			
			
			
			$arr = array('keyValue'=>$data['footer_branch'],);
			$where=" keyName= 'footer_branch'";
			$this->update($arr, $where);
			
			$arr = array('keyValue'=>$data['telClient'],);
			$where=" keyName= 'tel-client'";
			$this->update($arr, $where);
			
			$arr = array('keyValue'=>$data['email_client'],	);
			$where=" keyName= 'email_client'";
			$this->update($arr, $where);
			
			$arr = array('keyValue'=>$data['website'],	);
			$where=" keyName= 'website'";
			$this->update($arr, $where);
			
			
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
}

