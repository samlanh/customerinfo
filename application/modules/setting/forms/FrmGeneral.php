<?php 
Class Setting_Form_FrmGeneral extends Zend_Dojo_Form {
	protected $tr;
	protected $tvalidate ;//text validate
	protected $filter;
	protected $t_date;
	protected $t_num;
	protected $text;
	protected $check;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->t_date = 'dijit.form.DateTextBox';
		$this->t_num = 'dijit.form.NumberTextBox';
		$this->text = 'dijit.form.TextBox';
		$this->check='dijit.form.CheckBox';
	}
	public function FrmGeneral($data=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		
		
		$_branchAddClient = new Zend_Dojo_Form_Element_TextBox('footer_branch');
		$_branchAddClient->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("Branch Address Client")
		));
		$_telClient = new Zend_Dojo_Form_Element_TextBox('telClient');
		$_telClient->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("Tel Client")
		));
		$_client_website = new Zend_Dojo_Form_Element_TextBox('client_website');
		$_client_website->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("Client Website")
		));
		$_email_client = new Zend_Dojo_Form_Element_TextBox('email_client');
		$_email_client->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("Email Client")
		));
		
		
		$_website = new Zend_Dojo_Form_Element_TextBox('website');
		$_website->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("Website")
		));
		
		
		if($data!=null){
			
			$_branchAddClient->setValue($data['footer_branch']['keyValue']);
			$_telClient->setValue($data['tel-client']['keyValue']);
			$_client_website->setValue($data['client_website']['keyValue']);
			$_email_client->setValue($data['email_client']['keyValue']);
			
			$_website->setValue($data['website']['keyValue']);
		}
		$this->addElements(array(
				$_branchAddClient,
				$_telClient,
				$_client_website,
				$_email_client,
				$_website,
				));
		
		return $this;
		
	}
	public function FrmTruncate($data=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
	
		$_type_fomate=  new Zend_Dojo_Form_Element_FilteringSelect('type_fomate');
		$_type_fomate->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside'));
		$_penalty_type_opt = array(
				0=>$this->tr->translate("FULL"),
				1=>$this->tr->translate("PROPERTY"),
				2=>$this->tr->translate("Customer,Supplier,Staff agency.."),
				3=>$this->tr->translate("Investment Module"),
				4=>$this->tr->translate("Other Income/Expense"),
				5=>$this->tr->translate("Plong"),
				6=>$this->tr->translate("Sale & Payment,Change House/Owner"),
				);
		$_type_fomate->setMultiOptions($_penalty_type_opt);
		
		$this->addElements(array(
				$_type_fomate,
		));
		
		return $this;
	}
}