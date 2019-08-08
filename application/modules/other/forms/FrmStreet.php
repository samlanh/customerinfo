<?php 
Class Other_Form_FrmStreet extends Zend_Dojo_Form {
	protected $tr;
	protected $tvalidate ;//text validate
	protected $filter;
	protected $text;
	protected $tarea=null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->tarea = 'dijit.form.SimpleTextarea';
	}
	public function FrmAddStreet($_data=null){
	
		$request=Zend_Controller_Front::getInstance()->getRequest();
		
		$_adv_search = new Zend_Dojo_Form_Element_TextBox('adv_search');
		$_adv_search->setAttribs(array('dojoType'=>$this->text,
				'onkeyup'=>'this.submit()',
				'class'=>"fullside",
				'placeholder'=>$this->tr->translate("SEARCH_ZONE_INFO")
		));
		$_adv_search->setValue($request->getParam("adv_search"));
		
		
		$_status_search=  new Zend_Dojo_Form_Element_FilteringSelect('search_status');
		$_status_search->setAttribs(array('dojoType'=>$this->filter,'class'=>"fullside",));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DEACTIVE"));
		$_status_search->setMultiOptions($_status_opt);
		$_status_search->setValue($request->getParam("search_status"));
		
		$_btn_search = new Zend_Dojo_Form_Element_SubmitButton('btn_search');
		$_btn_search->setAttribs(array(
				'dojoType'=>'dijit.form.Button',
				'iconclass'=>'dijitIconSearch',
		
		));
		
		$_str_no= new Zend_Dojo_Form_Element_TextBox('str_no');
		$_str_no->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_str_title= new Zend_Dojo_Form_Element_TextBox('str_title');
		$_str_title->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_note = new Zend_Dojo_Form_Element_Textarea('note');
		$_note->setAttribs(array('dojoType'=>'dijit.form.Textarea','class'=>'fullside',
				'style'=>'width:99%;min-height:50px;'));
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DEACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_id = new Zend_Form_Element_Hidden('id');
		
		if(!empty($_data)){
			$_id->setValue($_data['id']);
			$_str_no->setValue($_data['str_no']);
			$_str_title->setValue($_data['str_title']);
			$_note->setValue($_data['note']);
			$_status->setValue($_data['status']);
		}
		$this->addElements(array($_btn_search,$_status_search,$_adv_search,
				$_id,$_str_no,$_str_title,$_note,$_status));
		return $this;
		
	}
	
}