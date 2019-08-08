<?php 
Class Group_Form_FrmCustomer extends Zend_Dojo_Form {
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
	public function FrmAddCustomer($_data=null){
	
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
		
		$dbgb = new Application_Model_DbTable_DbGlobal();
		$_client_number= new Zend_Dojo_Form_Element_TextBox('client_number');
		$_client_number->setAttribs(
				array('dojoType'=>$this->tvalidate,
						'readOnly'=>'true',
						'required'=>'true',
						'class'=>'fullside',
						'style'=>'color:red; font-weight:bold;',
					));
		$ClienNo = $dbgb->getNewClientIdByBranch();
		$_client_number->setValue($ClienNo);
		
		$_category=  new Zend_Dojo_Form_Element_FilteringSelect('category');
		$_category->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*',
				'autoComplete'=>'false',
				'onChange'=>'popupCategory();'));
		$_category_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_CATEGORY"),-1=>$this->tr->translate("ADD_NEW"));
		$cate = $dbgb->getAllCategory();
		if (!empty($cate)) foreach ($cate as $ct){
			$_category_opt[$ct['id']]=$ct['name'];
		}
		$_category->setMultiOptions($_category_opt);
		
		$_name_kh= new Zend_Dojo_Form_Element_TextBox('name_kh');
		$_name_kh->setAttribs(
				array(
					'dojoType'=>$this->tvalidate,
					'required'=>'true',
					'class'=>'fullside',
					'placeholder'=>$this->tr->translate("KH_NAME"),
					)
				);
		
		$_name_en= new Zend_Dojo_Form_Element_TextBox('name_en');
		$_name_en->setAttribs(array('dojoType'=>$this->tvalidate,
				'required'=>'true',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("EN_NAME"),)
				);
		
		$_sex=  new Zend_Dojo_Form_Element_FilteringSelect('sex');
		$_sex->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_sex_opt = array(
				1=>$this->tr->translate("MALE"),
				2=>$this->tr->translate("FEMALE"));
		$_sex->setMultiOptions($_sex_opt);
		
		$_contact= new Zend_Dojo_Form_Element_TextBox('contact');
		$_contact->setAttribs(array('dojoType'=>$this->tvalidate,'required'=>'true','class'=>'fullside',));
		
		$_service_id=  new Zend_Dojo_Form_Element_FilteringSelect('service_id');
		$_service_id->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_service_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_SERVICE"));
		$ser = $dbgb->getAllService(1);
		if (!empty($ser)) foreach ($ser as $ct){
			$_service_opt[$ct['id']]=$ct['name'];
		}
		$_service_id->setMultiOptions($_service_opt);
		
		$_product_id=  new Zend_Dojo_Form_Element_FilteringSelect('product_id');
		$_product_id->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_product_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_PRODUCT"));
		$pro = $dbgb->getAllProduct(1);
		if (!empty($pro)) foreach ($pro as $ct){
			$_product_opt[$ct['id']]=$ct['name'];
		}
		$_product_id->setMultiOptions($_product_opt);
		
		$_standard=  new Zend_Dojo_Form_Element_FilteringSelect('standard');
		$_standard->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_standard_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_STANDARD"));
		$stan = $dbgb->getAllStandard(1);
		if (!empty($stan)) foreach ($stan as $ct){
			$_standard_opt[$ct['id']]=$ct['name'];
		}
		$_standard->setMultiOptions($_standard_opt);
		
		$_made_by=  new Zend_Dojo_Form_Element_FilteringSelect('made_by');
		$_made_by->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_made_by_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_MADE_BY"));
		$_madeby = $dbgb->getAllMadeBy(1);
		if (!empty($_madeby)) foreach ($_madeby as $ct){
			$_made_by_opt[$ct['id']]=$ct['name'];
		}
		$_made_by->setMultiOptions($_made_by_opt);
		
		$_floor=  new Zend_Dojo_Form_Element_FilteringSelect('floor');
		$_floor->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',
				'queryExpr'=>'*${0}*',
				'autoComplete'=>'false',
				'onChange'=>"popupFormData(2);"
				)
				);
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_FLOOR"));
		$_row= $dbgb->getAllOther(2,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_floor->setMultiOptions($_line_opt);
		
		$_line=  new Zend_Dojo_Form_Element_FilteringSelect('line');
		$_line->setAttribs(
					array(
							'dojoType'=>$this->filter,
							'class'=>'fullside',
							'queryExpr'=>'*${0}*',
							'autoComplete'=>'false',
							'onChange'=>"popupFormData(3);"
						));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_LINE"));
		$_row= $dbgb->getAllOther(3,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_line->setMultiOptions($_line_opt);
		
		$_side=  new Zend_Dojo_Form_Element_FilteringSelect('side');
		$_side->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(4);"
				));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_SIDE"));
		$_row= $dbgb->getAllOther(4,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_side->setMultiOptions($_line_opt);
		
		$_start_direction=  new Zend_Dojo_Form_Element_FilteringSelect('start_direction');
		$_start_direction->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(5);"
				));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_START_DIRECTION"));
		$_row= $dbgb->getAllOther(5,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_start_direction->setMultiOptions($_line_opt);
		
		$_verification=  new Zend_Dojo_Form_Element_FilteringSelect('verification');
		$_verification->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(6);"));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_VERIFICATION"));
		$_row= $dbgb->getAllOther(6,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_verification->setMultiOptions($_line_opt);
		
		$_other=  new Zend_Dojo_Form_Element_FilteringSelect('other');
		$_other->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(1);"));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_OTHER"));
		$_row= $dbgb->getAllOther(1,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_other->setMultiOptions($_line_opt);
		
		$_province = new Zend_Dojo_Form_Element_FilteringSelect('province');
		$_province->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
				'onchange'=>'filterDistrict();',
		));
		
		$rows =  $dbgb->getAllProvince();
		$options=array($this->tr->translate("SELECT_PROVINCE")); 
		if(!empty($rows))foreach($rows AS $row) $options[$row['province_id']]=$row['province_en_name'];
		$_province->setMultiOptions($options);
		
		$_street_id = new Zend_Dojo_Form_Element_FilteringSelect('street_id');
		$_street_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
		));
		
		$rows =  $dbgb->getAllStreet(1);
		$options=array($this->tr->translate("SELECT_STREET"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['id']]=$row['name'];
		$_street_id->setMultiOptions($options);
		
		$_zone_id = new Zend_Dojo_Form_Element_FilteringSelect('zone_id');
		$_zone_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
		));
		
		$rows =  $dbgb->getAllZone(1);
		$options=array($this->tr->translate("SELECT_ZONE"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['id']]=$row['name'];
		$_zone_id->setMultiOptions($options);
		
		$_map = new Zend_Dojo_Form_Element_Textarea('map');
		$_map->setAttribs(array('dojoType'=>'dijit.form.Textarea','class'=>'fullside',
				'style'=>'width:99%;min-height:50px;'));
		
		$_note = new Zend_Dojo_Form_Element_Textarea('noted');
		$_note->setAttribs(array('dojoType'=>'dijit.form.Textarea','class'=>'fullside',
				'style'=>'width:99%;min-height:50px;'));
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DEACTIVE"));
		$_status->setMultiOptions($_status_opt);
		
		$_ordering= new Zend_Dojo_Form_Element_NumberTextBox('ordering');
		$_ordering->setAttribs(array('dojoType'=>"dijit.form.NumberTextBox",
				'required'=>'true',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("ORDERING"),)
		);
		
		$_id = new Zend_Form_Element_Hidden('id');
		$_id->setAttribs(array('dojoType'=>$this->text,'required'=>'true','class'=>'fullside',));
		if(!empty($_data)){
			$_id->setValue($_data['id']);
			$_client_number->setValue($_data['client_number']);
			$_category->setValue($_data['category']);
			$_name_kh->setValue($_data['name_kh']);
			$_name_en->setValue($_data['name_en']);
			$_sex->setValue($_data['sex']);
			$_contact->setValue($_data['contact']);
			$_service_id->setValue($_data['service_id']);
			$_product_id->setValue($_data['product_id']);
			$_standard->setValue($_data['standard']);
			$_made_by->setValue($_data['made_by']);
			$_street_id->setValue($_data['street_id']);
			$_zone_id->setValue($_data['zone_id']);
			$_province->setValue($_data['pro_id']);
			
			$_floor->setValue($_data['floor']);
			$_line->setValue($_data['line']);
			$_side->setValue($_data['side']);
			$_start_direction->setValue($_data['start_direction']);
			$_verification->setValue($_data['verification']);
			$_other->setValue($_data['other']);
			
			$_ordering->setValue($_data['ordering']);
			$_map->setValue($_data['map']);
			$_note->setValue($_data['note']);
			$_status->setValue($_data['status']);
			$_id->setValue($_data['id']);
		}
		$this->addElements(array($_btn_search,$_status_search,$_adv_search,
				$_id,
				$_client_number,
				$_category,
				$_name_kh,
				$_name_en,
				$_sex,
				$_contact,
				$_service_id,
				$_product_id,
				$_standard,
				$_made_by,
				$_street_id,
				$_zone_id,
				
				$_floor,
				$_line,
				$_side,
				$_start_direction,
				$_verification,
				$_other,
				
				$_province,
				$_map,
				$_note,
				$_ordering,
				$_status));
		return $this;
		
	}
	public function FrmMovingLocation($_data=null){
		$dbgb = new Application_Model_DbTable_DbGlobal();
		
		$moving_date = new Zend_Dojo_Form_Element_DateTextBox('moving_date');
		$moving_date->setAttribs(array(
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'dojoType'=>'dijit.form.DateTextBox','required'=>'true','class'=>'fullside',
		));
		$_date = date("Y-m-d");
		$moving_date->setValue($_date);
		
		$_floor=  new Zend_Dojo_Form_Element_FilteringSelect('floor');
		$_floor->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',
				'queryExpr'=>'*${0}*',
				'autoComplete'=>'false',
				'onChange'=>"popupFormData(2);"
				)
				);
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_FLOOR"));
		$_row= $dbgb->getAllOther(2,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_floor->setMultiOptions($_line_opt);
		
		$_line=  new Zend_Dojo_Form_Element_FilteringSelect('line');
		$_line->setAttribs(
					array(
							'dojoType'=>$this->filter,
							'class'=>'fullside',
							'queryExpr'=>'*${0}*',
							'autoComplete'=>'false',
							'onChange'=>"popupFormData(3);"
						));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_LINE"));
		$_row= $dbgb->getAllOther(3,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_line->setMultiOptions($_line_opt);
		
		$_side=  new Zend_Dojo_Form_Element_FilteringSelect('side');
		$_side->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(4);"
				));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_SIDE"));
		$_row= $dbgb->getAllOther(4,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_side->setMultiOptions($_line_opt);
		
		$_start_direction=  new Zend_Dojo_Form_Element_FilteringSelect('start_direction');
		$_start_direction->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(5);"
				));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_START_DIRECTION"));
		$_row= $dbgb->getAllOther(5,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_start_direction->setMultiOptions($_line_opt);
		
		$_verification=  new Zend_Dojo_Form_Element_FilteringSelect('verification');
		$_verification->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(6);"));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_VERIFICATION"));
		$_row= $dbgb->getAllOther(6,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_verification->setMultiOptions($_line_opt);
		
		$_other=  new Zend_Dojo_Form_Element_FilteringSelect('other');
		$_other->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',
				'onChange'=>"popupFormData(1);"));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_OTHER"));
		$_row= $dbgb->getAllOther(1,1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_other->setMultiOptions($_line_opt);
		
		$_province = new Zend_Dojo_Form_Element_FilteringSelect('province');
		$_province->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
				'onchange'=>'filterDistrict();',
		));
		
		$rows =  $dbgb->getAllProvince();
		$options=array($this->tr->translate("SELECT_PROVINCE"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['province_id']]=$row['province_en_name'];
		$_province->setMultiOptions($options);
		
		$_street_id = new Zend_Dojo_Form_Element_FilteringSelect('street_id');
		$_street_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
		));
		
		$rows =  $dbgb->getAllStreet(1);
		$options=array($this->tr->translate("SELECT_STREET"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['id']]=$row['name'];
		$_street_id->setMultiOptions($options);
		
		$_zone_id = new Zend_Dojo_Form_Element_FilteringSelect('zone_id');
		$_zone_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
		));
		
		$rows =  $dbgb->getAllZone(1);
		$options=array($this->tr->translate("SELECT_ZONE"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['id']]=$row['name'];
		$_zone_id->setMultiOptions($options);
		
		$_map = new Zend_Dojo_Form_Element_Textarea('map');
		$_map->setAttribs(array('dojoType'=>'dijit.form.Textarea','class'=>'fullside',
				'style'=>'width:99%;min-height:50px;'));
		
		$_note = new Zend_Dojo_Form_Element_Textarea('noted');
		$_note->setAttribs(array('dojoType'=>'dijit.form.Textarea','class'=>'fullside',
				'style'=>'width:99%;min-height:50px;'));
		
		$_id = new Zend_Form_Element_Hidden('id');
		$_id->setAttribs(array('dojoType'=>$this->text,'required'=>'true','class'=>'fullside',));
		
		if(!empty($_data)){
			$_id->setValue($_data['id']);
			$_street_id->setValue($_data['street_id']);
			$_zone_id->setValue($_data['zone_id']);
			$_province->setValue($_data['pro_id']);
				
			$_floor->setValue($_data['floor']);
			$_line->setValue($_data['line']);
			$_side->setValue($_data['side']);
			$_start_direction->setValue($_data['start_direction']);
			$_verification->setValue($_data['verification']);
			$_other->setValue($_data['other']);
				
			$_map->setValue($_data['map']);
			$_note->setValue($_data['note']);
			$_id->setValue($_data['id']);
		}
		$this->addElements(array(
				$_id,
				$moving_date,
				$_street_id,
				$_zone_id,
		
				$_floor,
				$_line,
				$_side,
				$_start_direction,
				$_verification,
				$_other,
		
				$_province,
				$_map,
				$_note,
				));
		return $this;
		
	}
}