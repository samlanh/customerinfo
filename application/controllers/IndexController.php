<?php

class IndexController extends Zend_Controller_Action
{

	const REDIRECT_URL = '/home';
	
    public function init()
    {
        /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');  
    }
    public function indexAction()
    {
    	$session_user=new Zend_Session_Namespace(SYSTEM_SES);
    	$username = $session_user->first_name;
    	$user_id = $session_user->user_id;
    	if (!empty($user_id)){
    		$this->_redirect("/home");
    	}
    	$this->_helper->layout()->disableLayout();
		$form=new Application_Form_FrmLogin();				
		$form->setAction('index');		
		$form->setMethod('post');
		$form->setAttrib('accept-charset', 'utf-8');
		$this->view->form=$form;
		$key = new Application_Model_DbTable_DbKeycode();
		$this->view->data=$key->getKeyCodeMiniInv(TRUE);		
		$vdgb = new Application_Model_DbTable_DbGlobal();
		$this->view->alllang =  $vdgb->getLaguage();
        
		if($this->getRequest()->isPost())		
		{
			if(date('Y-m-d')>='2020-01-15'){
 				$this->view->msg = 'System Expired';
 				return false;
			}
			$formdata=$this->getRequest()->getPost();
			if($form->isValid($formdata))
			{
				$session_lang=new Zend_Session_Namespace('lang');
				$session_lang->lang_id=$formdata["lang"];//for creat session
				Application_Form_FrmLanguages::getCurrentlanguage($session_lang->lang_id);//for choose lang for when login
				$user_name=$form->getValue('txt_user_name');
				$password=$form->getValue('txt_password');
				$db_user=new Application_Model_DbTable_DbUsers();
				if($db_user->userAuthenticate($user_name,$password)){					
					
					$session_user=new Zend_Session_Namespace(SYSTEM_SES);
					$user_id=$db_user->getUserID($user_name);
					$user_info = $db_user->getUserInfo($user_id);
					
					$arr_acl=$db_user->getArrAcl($user_info['user_type']);
					//$session_user->url_report=$db_user->getArrAclReport($user_info['user_type']);
					$session_user->user_id=$user_id;
					$session_user->user_name=$user_name;
					$session_user->pwd=$password;		
					$session_user->level= $user_info['user_type'];
					$session_user->last_name= $user_info['last_name'];
					$session_user->first_name= $user_info['first_name'];
					$session_user->branch_list= $user_info['branch_list'];
					//$session_user->theme_style=$db_user->getThemeByUserId($user_id);
					$session_user->timeout= time();
					$a_i = 0;
					$arr_actin = array();	
					for($i=0; $i<count($arr_acl);$i++){
						$arr_module[$i]=$arr_acl[$i]['module'];
					}
					$arr_module=(array_unique($arr_module));
					$arr_actin=(array_unique($arr_actin));
					$arr_module=$this->sortMenu($arr_module);
					
					$session_user->arr_acl = $arr_acl;
					$session_user->arr_module = $arr_module;
					$session_user->arr_actin = $arr_actin;
						
					$session_user->lock();
					$dbgb = new Application_Model_DbTable_DbGlobal();
					$_datas = array('description'=>'Login to System');
					$dbgb->addActivityUser($_datas);
					
					$log=new Application_Model_DbTable_DbUserLog();
					$log->insertLogin($user_id);	
					foreach ($arr_module AS $i => $d){
						if($d !== 'transfer'){
							$url = '/' . $arr_module[0];
						}
						else{
							$url = self::REDIRECT_URL;
							break;
						}
					}
					if(!empty($user_info['staff_id'])){
						Application_Form_FrmMessage::redirectUrl("/home/index/dashboard");
						exit();
					}
					Application_Form_FrmMessage::redirectUrl("/home");	
					exit();
				}
			else{					
					$this->view->msg = 'ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ ';
				}
			}
			else{				
				$this->view->msg = 'លោកអ្នកមិនមានសិទ្ធិប្រើប្រាស់ទេ!';
			}				
		}	
		$session_lang=new Zend_Session_Namespace('lang');
		$this->view->rslang = $session_lang->lang_id;
    }
    protected function sortMenu($menus){
    	$menus_order = Array ( 'home','project','group','loan','incexp','property','invest','other','report','rsvacl','setting');
    	$temp_menu = Array();
    	$menus=array_unique($menus);
    	foreach ($menus_order as $i => $val){
    		foreach ($menus as $k => $v){
    			if($val == $v){
    				$temp_menu[] = $val;
    				unset($menus[$k]);
    				break;
    			}
    		}
    	}
    	return $temp_menu;    	
    }
    public function logoutAction()
    {
        if($this->getRequest()->getParam('value')==1){        	
        	$aut=Zend_Auth::getInstance();
        	$aut->clearIdentity();  
        	$dbgb = new Application_Model_DbTable_DbGlobal();
        	$_datas = array('description'=>'Log out From System');
        	$dbgb->addActivityUser($_datas);
        	
        	$session_user=new Zend_Session_Namespace(SYSTEM_SES);
        	$log=new Application_Model_DbTable_DbUserLog();
			$log->insertLogout($session_user->user_id);
			
        	$session_user->unsetAll();       	
        	Application_Form_FrmMessage::redirectUrl("/");
        	exit();
        } 
    }

    public function changepasswordAction()
    {
        // action body
        if ($this->getRequest()->isPost()){ 
			$session_user=new Zend_Session_Namespace(SYSTEM_SES);    		
    		$pass_data=$this->getRequest()->getPost();
    		if ($pass_data['password'] == $session_user->pwd){
    			    			 
				$db_user = new Application_Model_DbTable_DbUsers();				
				try {
					$db_user->changePassword($pass_data['new_password'], $session_user->user_id);
					$session_user->unlock();	
					$session_user->pwd=$pass_data['new_password'];
					$session_user->lock();
				Application_Form_FrmMessage::Sucessfull('ការផ្លាស់ប្តូរដោយជោគជ័យ', self::REDIRECT_URL);
				} catch (Exception $e) {
					Application_Form_FrmMessage::message('ការផ្លាស់ប្តូរត្រូវបរាជ័យ');
					Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
				}				
    		}
    		else{
    			Application_Form_FrmMessage::message('ការផ្លាស់ប្តូរត្រូវបរាជ័យ');
    		}
        }   
    }
    public function reloadrAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$session_user=new Zend_Session_Namespace(SYSTEM_SES);
    		$session_user->timeout= time();
    		print_r(Zend_Json::encode($session_user->timeout));
    		exit();
    	}
    }
    public function errorAction()
    {
    }
    public static function start(){
    	return ($this->getRequest()->getParam('limit_satrt',0));
    }
    function changelangeAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$session_lang=new Zend_Session_Namespace('lang');
    		$session_lang->lang_id=$data['lange'];
    		Application_Form_FrmLanguages::getCurrentlanguage($data['lange']);
    		print_r(Zend_Json::encode(2));
    		exit();
    	}
    }
}