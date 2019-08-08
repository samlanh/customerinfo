<?php

class RsvAcl_UserController extends Zend_Controller_Action
{
	const REDIRECT_URL = '/rsvacl';
	const MAX_USER = 150;
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	private $user_typelist = array();
	
    public function init()
    {
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	
    	$db=new Application_Model_DbTable_DbGlobal();
    	$sql = "SELECT u.user_type_id as id,u.user_type as name FROM `rms_acl_user_type` u where u.`status`=1";
    	$this->user_typelist = $db->getGlobalDb($sql);
// 		foreach ($results as $key => $r){
// 			$this->user_typelist[$r['user_type_id']] = $r['user_type'];    
// 		}		
    }

    public function indexAction()
    {
		$db_user=new Application_Model_DbTable_DbUsers();
        $this->view->activelist =$this->activelist;       
        $this->view->user_typelist =$this->user_typelist;   
        $this->view->active =-1;
        
        $_data = array(
        	'active'=>-1,
        	'user_type'=>-1,
        	'txtsearch'=>''
        );
        if($this->getRequest()->isPost()){     	
        	$_data=$this->getRequest()->getPost();
        }
        $rs_rows = $db_user->getUserList($_data);
        $_rs = array();
        foreach ($rs_rows as $key =>$rs){
        	$_rs[$key] =array(
        	'id'=>$rs['id'],
        	'name'=>$rs['last_name'].' '.$rs['name'],
        	'user_name'=>$rs['user_name'],
        	'user_type'=>$rs['users_type'],
        	'status'=>$rs['status']);
        }
        $list = new Application_Form_Frmtable();
        if(!empty($_rs)){
        	$glClass = new Application_Model_GlobalClass();
        	$rs_rows = $glClass->getImgActive($_rs, BASE_URL, true);
        }
        else{
        	$result = Application_Model_DbTable_DbGlobal::getResultWarning();
        }
        $collumns = array("LASTNAME_FIRSTNAME","USER_NAME","USER_TYPE","STATUS");
        $link=array(
        		'module'=>'rsvacl','controller'=>'user','action'=>'edit',
        );
        $this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('user_name'=>$link,'name'=>$link));
    }
	public function addAction()
	{
			// action body
			$db_user=new Application_Model_DbTable_DbUsers();
			 
			if ($db_user->getMaxUser() > self::MAX_USER) {
				Application_Form_FrmMessage::Sucessfull('អ្នក​ប្រើ​ប្រាស់​របស់​អ្នក​បាន​ត្រឹម​តែ '.self::MAX_USER.' នាក់ ទេ!', self::REDIRECT_URL);
			}
			$this->view->user_typelist =$this->user_typelist;
			if($this->getRequest()->isPost()){
				$userdata=$this->getRequest()->getPost();
				try {
					$sms="INSERT_SUCCESS";
					$_user = $db_user->insertUser($userdata);
					if($_user==-1){
						$sms = "RECORD_EXIST";
					}
					Application_Form_FrmMessage::Sucessfull($sms, self::REDIRECT_URL);
				} catch (Exception $e) {
					Application_Form_FrmMessage::message("INSERT_FAIL");
				}
			}
			$db  = new Application_Model_DbTable_DbGlobal();
// 			$this->view->rs_branch = $db->getAllBranch();
			$user_type = $this->user_typelist;
			$this->view->user_typelist =$user_type;
				
			array_unshift($user_type, array('id'=>-1,'name'=>'Add New'));
			$this->view->user_type = $user_type;
			
	}
	public function editAction()
	    {
	        // action body
	        $us_id = $this->getRequest()->getParam('id');
	    	$us_id = (empty($us_id))? 0 : $us_id;
	    	
	        $db_user=new Application_Model_DbTable_DbUsers();
	        $this->view->user_edit = $db_user->getUserEdit($us_id);
	
	        $this->view->user_typelist =$this->user_typelist;  
	        
	    	if($this->getRequest()->isPost()){
				$userdata=$this->getRequest()->getPost();	
				try {
					$sms="UPDATE_SUCESS";
					$_user = $db_user->updateUser($userdata);
					if($_user==-1){
						$sms = "RECORD_EXIST";
					}				
					Application_Form_FrmMessage::Sucessfull($sms, self::REDIRECT_URL);		
				} catch (Exception $e) {
					Application_Form_FrmMessage::Sucessfull("UPDATE_FAIL", self::REDIRECT_URL);
				}
			}
			$db  = new Application_Model_DbTable_DbGlobal();
		$user_type = $this->user_typelist;
		$this->view->user_typelist =$user_type;
		array_unshift($user_type, array('id'=>-1,'name'=>'Add New'));
		$this->view->user_type = $user_type;
    }
    

}
