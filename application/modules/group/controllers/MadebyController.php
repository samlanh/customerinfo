<?php
class Group_MadebyController extends Zend_Controller_Action {
	const REDIRECT_URL='/group';
	protected $tr;
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Group_Model_DbTable_DbMadeBy();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
						'adv_search' => '',
						'search_status' => -1);
			}
			$rs_rows= $db->getAllMadeBy($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("TITLE","DATE","STATUS","BY");
			$link=array(
					'module'=>'group','controller'=>'madeby','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('title'=>$link,'modify_date'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
			$frm = new Group_Form_FrmMadeBy();
   			$frm_co=$frm->FrmaddMadeBy();
   			Application_Model_Decorator::removeAllDecorator($frm_co);
   			$this->view->frm = $frm_co;
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
   			$db = new Group_Model_DbTable_DbMadeBy();
   			$db->addMadeBy($_data);
   			if(!empty($_data['save_new'])){
   				Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL.'/madeby/add');
   			}else{
   				Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL.'/madeby/index');
   			}
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	$frm = new Group_Form_FrmMadeBy();
   	$frm_co=$frm->FrmaddMadeBy();
   	Application_Model_Decorator::removeAllDecorator($frm_co);
   	$this->view->frm_zone = $frm_co;
   }
   function editAction(){
   	$db = new Group_Model_DbTable_DbMadeBy();
	   	if($this->getRequest()->isPost()){
	   		// Check Session Expire
	   		$dbgb = new Application_Model_DbTable_DbGlobal();
	   		$checkses = $dbgb->checkSessionExpire();
	   		if (empty($checkses)){
	   			$dbgb->reloadPageExpireSession();
	   			exit();
	   		}
	   		
	   		try{
	   			$_data = $this->getRequest()->getPost();
	   			$db->addMadeBy($_data);
	   			Application_Form_FrmMessage::Sucessfull($this->tr->translate('EDIT_SUCCESS'),self::REDIRECT_URL.'/madeby/index');
	   		}catch(Exception $e){
	   			Application_Form_FrmMessage::message($this->tr->translate('EDIT_FAIL'));
	   			$err =$e->getMessage();
	   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
	   		}
	   	}
	   	$id=$this->getRequest()->getParam('id');
	   	$id = empty($id)?0:$id;
	   	$row = $db->getCategoryById($id);
	   	if(empty($row)){
	   		Application_Form_FrmMessage::Sucessfull($this->tr->translate('NO_RECORD'),self::REDIRECT_URL.'/madeby/index');
	   		exit();
	   	}
	   	$frm = new Group_Form_FrmMadeBy();
	   	$frm_co=$frm->FrmaddMadeBy($row);
	   	Application_Model_Decorator::removeAllDecorator($frm_co);
	   	$this->view->frm_zone = $frm_co;
   }
   public function addajaxAction(){
   	if($this->getRequest()->isPost()){
   		$data = $this->getRequest()->getPost();
   		$data['status']=1;
   		$db_co = new Group_Model_DbTable_DbMadeBy();
   		$data['title']=$data['title_madeby'];
   		$data['note']=$data['note_madeby'];
   		$id = $db_co->addMadeBy($data);
   		print_r(Zend_Json::encode($id));
   		exit();
   	}
   }
}

