<?php

class Other_Model_DbTable_DbOther extends Zend_Db_Table_Abstract
{

    public function getUserId(){
    	$session_user=new Zend_Session_Namespace(SYSTEM_SES);
    	return $session_user->user_id;
    }
    function tableIndex($type=1){
    	if ($type==1){
    		return "crm_other";
    	}else if ($type==2){
    		return "crm_floor";
    	}else if ($type==3){
    		return "crm_line";
    	}else if ($type==4){
    		return "crm_side";
    	}else if ($type==5){
    		return "crm_start_direction";
    	}else if ($type==6){
    		return "crm_verification";
    	}
    }
	public function addOther($_data,$type){
		$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$this->_name = $this->tableIndex($type);
			$_arr=array(
					'title'	  => $_data['title'],
					'note'	  => $_data['note'],
					'modify_date' => date('Y-m-d H:i:s'),
					'user_id'	  => $this->getUserId()
			);
			if(!empty($_data['id'])){
				$id = $_data['id'];
				$_arr['status'] = $_data['status'];
				$where = 'id = '.$id;
				$this->update($_arr, $where);
			}else{
				$_arr['status'] = 1;
				$_arr['create_date'] = date('Y-m-d H:i:s');
				$id =  $this->insert($_arr);
			}
			$db->commit();
			return $id;
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		$db->rollBack();
		}
	}
	public function getOtherById($id,$type){
		$db = $this->getAdapter();
		$this->_name = $this->tableIndex($type);
		$sql=" SELECT * FROM $this->_name WHERE
			  id = ".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	function getAllOther($search=null,$type){
		$this->_name = $this->tableIndex($type);
		$db = $this->getAdapter();
		$sql = "SELECT
				id,
				title,
				modify_date
				 ";
		$dbp = new Application_Model_DbTable_DbGlobal();
		$sql.=$dbp->caseStatusShowImage("status");
		$sql.=",(SELECT first_name FROM rms_users WHERE id=user_id LIMIT 1) As user_name
				FROM $this->_name";
		$where = ' WHERE title!="" ';
		if($search['search_status']>-1){
			$where.= " AND status = ".$search['search_status'];
		}
		if(!empty($search['adv_search'])){
			$s_where = array();
			$search = addslashes(trim($search['adv_search']));
			$s_where[] = " title LIKE '%{$search}%'";
			$where.=' AND ('.implode(' OR ',$s_where).')';
		}
		$order = " ORDER BY id DESC";
		return $db->fetchAll($sql.$where.$order);	
	}	
}

