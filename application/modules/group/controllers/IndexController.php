<?php
class Group_indexController extends Zend_Controller_Action {
	const REDIRECT_URL = '/group/index';
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Group_Model_DbTable_DbClient();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}else{
				$search = array(
					'category'=>0,
					'service_id'=>0,
					'product_id'=>0,
					'adv_search' => '',
					'status' => -1,
					'province'=>0,
					'district_id'=>'',
					'comm_id'=>'',
					'village'=>'',
					'start_date'=> date('Y-m-d'),
					'end_date'=>date('Y-m-d'));
			}
			
			$rs_rows= $db->getAllClients($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("CLIENT_NUM","CATEGORY","CUSTOMER_NAME","SEX","CONTACT","SERVICE","PRODUCT","STANDARD"
					,"STREET","ZONE"
					,"DISTRICT","COMMUNE","VILLAGE","SIDE","START_DIRECTION",
					"DATE","BY_USER","STATUS");
			$link=array(
					'module'=>'group','controller'=>'index','action'=>'edit',);
			$link1=array(
					'module'=>'group','controller'=>'index','action'=>'view',);
			$this->view->list=$list->getCheckList(10, $collumns, $rs_rows,array());
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	
		$frm = new Application_Form_FrmAdvanceSearch();
		$frm = $frm->AdvanceSearch();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
		
		$this->view->result=$search;	
	}
	public function addAction(){
		
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		if($this->getRequest()->isPost()){
			try{
				// Check Session Expire
				$dbgb = new Application_Model_DbTable_DbGlobal();
				$checkses = $dbgb->checkSessionExpire();
				if (empty($checkses)){
					$dbgb->reloadPageExpireSession();
					exit();
				}
				
				$data = $this->getRequest()->getPost();
				$db = new Group_Model_DbTable_DbClient();
				$id= $db->addClient($data);
				if(!empty($data['save_new'])){
					Application_Form_FrmMessage::Sucessfull($tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL.'/add');
				}else{
					Application_Form_FrmMessage::Sucessfull($tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL);
				}
				Application_Form_FrmMessage::message($tr->translate("INSERT_SUCCESS"));
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$fm = new Group_Form_FrmCustomer();
		$frm = $fm->FrmAddCustomer();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_client = $frm;
		
		$dbpop = new Application_Form_FrmPopupGlobal();
		$this->view->frm_popup_village = $dbpop->frmPopupVillage();
		$this->view->frm_popup_comm = $dbpop->frmPopupCommune();
		$this->view->frm_popup_district = $dbpop->frmPopupDistrict();
		$this->view->frm_popupOther = $dbpop->frmPopupOther();
		
		$this->view->frmPopupService = $dbpop->frmPopupService();
		$this->view->frmPopupProduct = $dbpop->frmPopupProduct();
		$this->view->frmPopupStandard = $dbpop->frmPopupStandard();
		$this->view->frmPopupMadeBy = $dbpop->frmPopupMadeBy();
		$this->view->frmPopupStreet = $dbpop->frmPopupStreet();
		$this->view->frmPopupZone = $dbpop->frmPopupZone();
		$this->view->frmPopupCate = $dbpop->frmPopupCate();
		
		$dbgb = new Application_Model_DbTable_DbGlobal();
		$this->view->other = $dbgb->getAllOther(1,1);
		$this->view->floor = $dbgb->getAllOther(2,1);
		$this->view->line = $dbgb->getAllOther(3,1);
		$this->view->side = $dbgb->getAllOther(4,1);
		$this->view->start_direction = $dbgb->getAllOther(5,1);
		$this->view->verification = $dbgb->getAllOther(6,1);
		
		$this->view->service = $dbgb->getAllService(1);
		$this->view->product = $dbgb->getAllProduct(1);
		$this->view->standard = $dbgb->getAllStandard(1);
		$this->view->madeby = $dbgb->getAllMadeBy(1);
		$this->view->street = $dbgb->getAllStreet(1);
		$this->view->zone = $dbgb->getAllZone(1);
	}
	public function editAction(){
		$db = new Group_Model_DbTable_DbClient();
		$id = $this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			try{
				// Check Session Expire
				$dbgb = new Application_Model_DbTable_DbGlobal();
				$checkses = $dbgb->checkSessionExpire();
				if (empty($checkses)){
					$dbgb->reloadPageExpireSession();
					exit();
				}
				
				$data = $this->getRequest()->getPost();
				$db->addClient($data);
				Application_Form_FrmMessage::Sucessfull('EDIT_SUCCESS',"/group");
			}catch (Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAILE");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$id = $this->getRequest()->getParam("id");
		$id = empty($id)?0:$id;
		$row = $db->getClientById($id);
	    $this->view->row=$row;
	    $this->view->document=$db->getDocumentClientById($id);
	    $this->view->movinghist=$db->getMovingClientById($id);
	    
		$this->view->photo = $row['photo_name'];
		if(empty($row)){
			Application_Form_FrmMessage::Sucessfull('NO_RECORD',"/group");
			exit();
		}
		$fm = new Group_Form_FrmCustomer();
		$frm = $fm->FrmAddCustomer($row);
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_client = $frm;
		
		$dbpop = new Application_Form_FrmPopupGlobal();
		$this->view->frm_popup_village = $dbpop->frmPopupVillage();
		$this->view->frm_popup_comm = $dbpop->frmPopupCommune();
		$this->view->frm_popup_district = $dbpop->frmPopupDistrict();
		$this->view->frm_popupOther = $dbpop->frmPopupOther();
		
		$this->view->frmPopupService = $dbpop->frmPopupService();
		$this->view->frmPopupProduct = $dbpop->frmPopupProduct();
		$this->view->frmPopupStandard = $dbpop->frmPopupStandard();
		$this->view->frmPopupMadeBy = $dbpop->frmPopupMadeBy();
		$this->view->frmPopupStreet = $dbpop->frmPopupStreet();
		$this->view->frmPopupZone = $dbpop->frmPopupZone();
		$this->view->frmPopupCate = $dbpop->frmPopupCate();
		
		$dbgb = new Application_Model_DbTable_DbGlobal();
		$this->view->other = $dbgb->getAllOther(1,1);
		$this->view->floor = $dbgb->getAllOther(2,1);
		$this->view->line = $dbgb->getAllOther(3,1);
		$this->view->side = $dbgb->getAllOther(4,1);
		$this->view->start_direction = $dbgb->getAllOther(5,1);
		$this->view->verification = $dbgb->getAllOther(6,1);
		
		$this->view->service = $dbgb->getAllService(1);
		$this->view->product = $dbgb->getAllProduct(1);
		$this->view->standard = $dbgb->getAllStandard(1);
		$this->view->madeby = $dbgb->getAllMadeBy(1);
		$this->view->street = $dbgb->getAllStreet(1);
		$this->view->zone = $dbgb->getAllZone(1);
		
	}
	
	function moveAction(){
		$db = new Group_Model_DbTable_DbClient();
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		if($this->getRequest()->isPost()){
			try{
				// Check Session Expire
				$dbgb = new Application_Model_DbTable_DbGlobal();
				$checkses = $dbgb->checkSessionExpire();
				if (empty($checkses)){
					$dbgb->reloadPageExpireSession();
					exit();
				}
		
				$data = $this->getRequest()->getPost();
				$id= $db->addMoving($data);
				Application_Form_FrmMessage::Sucessfull($tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL);
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$id = $this->getRequest()->getParam("id");
		$id = empty($id)?0:$id;
		$row = $db->getClientById($id);
		$this->view->row=$row;
		$this->view->movinghist=$db->getMovingClientById($id);
		if(empty($row)){
			$this->_redirect("/group");
		}
		
		$fm = new Group_Form_FrmCustomer();
		$frm = $fm->FrmMovingLocation();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_client = $frm;
		
		$dbpop = new Application_Form_FrmPopupGlobal();
		$this->view->frm_popup_village = $dbpop->frmPopupVillage();
		$this->view->frm_popup_comm = $dbpop->frmPopupCommune();
		$this->view->frm_popup_district = $dbpop->frmPopupDistrict();
		$this->view->frm_popupOther = $dbpop->frmPopupOther();
		
		$this->view->frmPopupService = $dbpop->frmPopupService();
		$this->view->frmPopupProduct = $dbpop->frmPopupProduct();
		$this->view->frmPopupStandard = $dbpop->frmPopupStandard();
		$this->view->frmPopupMadeBy = $dbpop->frmPopupMadeBy();
		$this->view->frmPopupStreet = $dbpop->frmPopupStreet();
		$this->view->frmPopupZone = $dbpop->frmPopupZone();
		$this->view->frmPopupCate = $dbpop->frmPopupCate();
		
		$dbgb = new Application_Model_DbTable_DbGlobal();
		$this->view->other = $dbgb->getAllOther(1,1);
		$this->view->floor = $dbgb->getAllOther(2,1);
		$this->view->line = $dbgb->getAllOther(3,1);
		$this->view->side = $dbgb->getAllOther(4,1);
		$this->view->start_direction = $dbgb->getAllOther(5,1);
		
		$this->view->street = $dbgb->getAllStreet(1);
		$this->view->zone = $dbgb->getAllZone(1);
		
		
	}
	function viewAction(){
		$id = $this->getRequest()->getParam("id");
		$db = new Group_Model_DbTable_DbClient();
		$this->view->client_list = $db->getClientDetailInfo($id);
	}
	
	function insertDistrictAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db_district = new Other_Model_DbTable_DbDistrict();
			$district=$db_district->addDistrictByAjax($data);
			print_r(Zend_Json::encode($district));
			exit();
		}
	}
	function insertcommuneAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db_commune = new Other_Model_DbTable_DbCommune();
			$commune=$db_commune->addCommunebyAJAX($data);
			print_r(Zend_Json::encode($commune));
			exit();
		}
	}
	function addVillageAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db_village = new Other_Model_DbTable_DbVillage();
			$village=$db_village->addVillage($data);
			print_r(Zend_Json::encode($village));
			exit();
		}
	}
	function insertDocumentTypeAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Group_Model_DbTable_DbClient();
			$id = $db->addViewType($data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	
	function getClientNoAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$dataclient=$db->getNewClientIdByBranch();
			print_r(Zend_Json::encode($dataclient));
			exit();
		}
	}
}