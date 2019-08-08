<?php 
Class Other_Form_FrmSearch extends Zend_Dojo_Form{
	protected $tr = null;
	protected $btn =null;//text validate
	protected $filter = null;
	protected $text =null;
	protected $validate = null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->btn = 'dijit.form.Button';
		$this->validate = 'dijit.form.ValidationTextBox';
	}
	
	
	public function FrmSetting($_data=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$_title = new Zend_Dojo_Form_Element_TextBox('title');
		$_title->setAttribs(array('dojoType'=>$this->text,
				'placeholder'=>$this->tr->translate("INPUT_LABEL_VALUE")));
		$_title->setValue($request->getParam("title"));
	
		$this->addElements(array($_title));
		return $this;
	}
	public function FrmAddSetting($_data=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		
		$_keyname = new Zend_Dojo_Form_Element_TextBox('key_name');
		$_keyname->setAttribs(array('dojoType'=>$this->validate,
				'required'=>'true','class'=>'full',
				'placeholder'=>$this->tr->translate("INPUT_KEY_SETTING")));
	
		$_keyvalue = new Zend_Dojo_Form_Element_TextBox('key_value');
		$_keyvalue->setAttribs(array('dojoType'=>$this->validate,'class'=>'full',
				'required'=>'true',
				'placeholder'=>$this->tr->translate("INPUT_VALUE_SETTING")));
		
		$_id = new Zend_Form_Element_Hidden('id');
	
		$this->addElements(array($_keyname,$_keyvalue,$_id));
		if(!empty($_data)){
			$_id->setValue($_data['code']);
			$_keyname->setValue($_data['keyName']);
			$_keyname->setAttrib("ReadOnly", true);
			$_keyvalue->setValue($_data['keyValue']);
		}
	
		return $this;
	}
	public function searchProvinnce(){
		$request=Zend_Controller_Front::getInstance()->getRequest();
	
		$_title = new Zend_Dojo_Form_Element_TextBox('title');
		$_title->setAttribs(array('dojoType'=>$this->text,
				'class'=>"fullside",
				'placeholder'=>$this->tr->translate("SEARCH_PROVINCE_TITLE")));
		$_title->setValue($request->getParam("title"));
	
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status_search');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>"fullside",));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL_STATUS"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DEACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status_search"));
		$this->addElements(array($_title,$_status));
	
		return $this;
	}
	public function frmServiceType($_data=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
	
		$_title = new Zend_Dojo_Form_Element_TextBox('title');
		$_title->setAttribs(array('dojoType'=>$this->text,
				'placeholder'=>$this->tr->translate("SEARCH_BY_TEACHER_NAME")));
		$_title->setValue($request->getParam('title'));
	
		$_type = new Zend_Dojo_Form_Element_FilteringSelect('type');
		$_status_type = array(
				-1=>$this->tr->translate("ALL"),
				1=>$this->tr->translate("SERVICE"),
				2=>$this->tr->translate("PROGRAM"));
		$_type->setMultiOptions($_status_type);
		$_type->setAttribs(array('dojoType'=>$this->filter,
				'placeholder'=>$this->tr->translate("SEARCH_BY_TYPE")));
	
	
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status_search');
		$_status->setAttribs(array('dojoType'=>$this->filter,));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL_STATUS"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DEACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status_search"));
	
	
		$this->addElements(array($_title,$_type,$_status));
		if(!empty($_data)){
		}
	
		return $this;
	}
}