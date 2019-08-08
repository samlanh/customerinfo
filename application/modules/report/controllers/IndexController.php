<?php
class Report_indexController extends Zend_Controller_Action {
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
  function indexAction(){
  }
  function rptClientAction(){
  	$db  = new Report_Model_DbTable_DbReport();
  	if($this->getRequest()->isPost()){
  		$search = $this->getRequest()->getPost();
  	}
  	else{
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
  				
  				'street_id'=>0,
  				'zone_id'=>0,
  				'side'=>0,
  				'line'=>0,
  				'floor'=>0,
  				'start_direction'=>0,
  				'verification'=>0,
  				'made_by'=>0,
  				'standard'=>0,
  				
			'start_date'=> date('Y-m-d'),
			'end_date'=>date('Y-m-d'));
  	}
  	$this->view->search = $search;
  	$this->view->rs =$db->getAllClients($search);
  		
  	$frm = new Application_Form_FrmAdvanceSearch();
	$frm = $frm->AdvanceSearch();
	Application_Model_Decorator::removeAllDecorator($frm);
	$this->view->frm_search = $frm;
  		
  	$key = new Application_Model_DbTable_DbKeycode();
  	$this->view->data=$key->getKeyCodeMiniInv(TRUE);
  
  	$frmpopup = new Application_Form_FrmPopupGlobal();
  	$this->view->footerReport = $frmpopup->getFooterReport();
  }
  
  function clientProfileAction(){
  	$id = $this->getRequest()->getParam("id");
  	$id = empty($id)?0:$id;
  	$db  = new Report_Model_DbTable_DbReport();
  	$row = $db->getClientsProfile($id);
  	if(empty($row)){
  		Application_Form_FrmMessage::Sucessfull('NO_RECORD',"/group");
			exit();
  	}
  	$this->view->row =$row;
  }
}