<?php
class Other_VerificationController extends Zend_Controller_Action {
	const REDIRECT_URL='/other';
	protected $tr;
	protected $typetable;
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	$this->typetable=6;//crm_verification
	}
	public function indexAction(){
		try{
			$db = new Other_Model_DbTable_DbOther();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
						'adv_search' => '',
						'search_status' => -1);
			}
			$rs_rows= $db->getAllOther($search,$this->typetable);
			$list = new Application_Form_Frmtable();
			$collumns = array("TITLE","DATE","STATUS","BY");
			$link=array(
					'module'=>'other','controller'=>'verification','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('title'=>$link,'modify_date'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
			$frm = new Other_Form_FrmOther();
   			$frm_co=$frm->FrmaddOther();
   			Application_Model_Decorator::removeAllDecorator($frm_co);
   			$this->view->frm_zone = $frm_co;
	}
   function addAction(){
   	if($this->getRequest()->isPost()){
   		try{
   			// Check Session Expire
   			$dbgb = new Application_Model_DbTable_DbGlobal();
   			$checkses = $dbgb->checkSessionExpire();
   			if (empty($checkses)){
   				$dbgb->reloadPageExpireSession();
   				exit();
   			}
   			
   			$_data = $this->getRequest()->getPost();
   			$db = new Other_Model_DbTable_DbOther();
   			$db->addOther($_data,$this->typetable);
   			if(!empty($_data['save_new'])){
   				Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL.'/verification/add');
   			}else{
   				Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL.'/verification/index');
   			}
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	$frm = new Other_Form_FrmOther();
   	$frm_co=$frm->FrmaddOther();
   	Application_Model_Decorator::removeAllDecorator($frm_co);
   	$this->view->frm_zone = $frm_co;
   }
   function editAction(){
   	$db = new Other_Model_DbTable_DbOther();
	   	if($this->getRequest()->isPost()){
	   		try{
	   			// Check Session Expire
	   			$dbgb = new Application_Model_DbTable_DbGlobal();
	   			$checkses = $dbgb->checkSessionExpire();
	   			if (empty($checkses)){
	   				$dbgb->reloadPageExpireSession();
	   				exit();
	   			}
	   			
	   			$_data = $this->getRequest()->getPost();
	   			$db->addOther($_data,$this->typetable);
	   			Application_Form_FrmMessage::Sucessfull($this->tr->translate('EDIT_SUCCESS'),self::REDIRECT_URL.'/verification/index');
	   		}catch(Exception $e){
	   			Application_Form_FrmMessage::message($this->tr->translate('EDIT_FAIL'));
	   			$err =$e->getMessage();
	   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
	   		}
	   	}
	   	$id=$this->getRequest()->getParam('id');
	   	$id = empty($id)?0:$id;
	   	$row = $db->getOtherById($id,$this->typetable);
	   	if(empty($row)){
	   		Application_Form_FrmMessage::Sucessfull($this->tr->translate('NO_RECORD'),self::REDIRECT_URL.'/verification/index');
	   		exit();
	   	}
	   	$frm = new Other_Form_FrmOther();
	   	$frm_co=$frm->FrmaddOther($row);
	   	Application_Model_Decorator::removeAllDecorator($frm_co);
	   	$this->view->frm_zone = $frm_co;
   }
   public function addNewzoneAction(){
   	if($this->getRequest()->isPost()){
   		$data = $this->getRequest()->getPost();
   		$db_co = new Other_Model_DbTable_DbOther();
   		$id = $db_co->addOther($data,$this->typetable);
   		print_r(Zend_Json::encode($id));
   		exit();
   	}
   }
}

