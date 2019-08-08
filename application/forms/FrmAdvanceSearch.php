<?php

class Application_Form_FrmAdvanceSearch extends Zend_Dojo_Form
{
	protected $tr;
	protected $tvalidate =null;//text validate
	protected $filter=null;
	protected $t_num=null;
	protected $text=null;
	protected $tarea=null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->tarea = 'dijit.form.SimpleTextarea';
	}
	public function AdvanceSearch($data=null,$type=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		
		$_title = new Zend_Dojo_Form_Element_TextBox('adv_search');
		$_title->setAttribs(array('dojoType'=>$this->text,
				'onkeyup'=>'this.submit()',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("ADVANCE_SEARCH")
				));
		$_title->setValue($request->getParam("adv_search"));
		
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside'));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DEACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status"));
		
		
		$db = new Application_Model_DbTable_DbGlobal(); 
		
		
		$_btn_search = new Zend_Dojo_Form_Element_SubmitButton('btn_search');
		$_btn_search->setAttribs(array(
				'dojoType'=>'dijit.form.Button',
				'iconclass'=>'dijitIconSearch',
				'label'=>'Search'
				
				));
		
		
		
		
		$opt_type=$db->getVewOptoinTypeByType(7,1);
		$type=new Zend_Dojo_Form_Element_FilteringSelect('type');
		$type->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'required'=>true,
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
		));
		$type->setMultiOptions($opt_type);
		$type->setValue($request->getParam("type"));
		
		$from_date = new Zend_Dojo_Form_Element_DateTextBox('start_date');
		$from_date->setAttribs(array('dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'onchange'=>'CalculateDate();'));
		$_date = $request->getParam("start_date");
		
		if(empty($_date)){
			//$_date = date("Y-m-d");
		}
		$from_date->setValue($_date);
		
		
		$to_date = new Zend_Dojo_Form_Element_DateTextBox('end_date');
		$to_date->setAttribs(array(
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'dojoType'=>'dijit.form.DateTextBox','required'=>'true','class'=>'fullside',
		));
		$_date = $request->getParam("end_date");
		
		if(empty($_date)){
			$_date = date("Y-m-d");
		}
		$to_date->setValue($_date);
		
		
		
		$user = new Zend_Dojo_Form_Element_FilteringSelect('user');
		$rows = $db ->getAllUser();
		$options=array(''=>$this->tr->translate("SELECT_USER"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['id']]=$row['by_user'];
		$user->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
		));
		$user->setMultiOptions($options);
		$user->setValue($request->getParam('user'));
		
		$dbgb = new Application_Model_DbTable_DbGlobal();
		$_category=  new Zend_Dojo_Form_Element_FilteringSelect('category');
		$_category->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_category_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_CATEGORY"));
		$cate = $dbgb->getAllCategory();
		if (!empty($cate)) foreach ($cate as $ct){
			$_category_opt[$ct['id']]=$ct['name'];
		}
		$_category->setMultiOptions($_category_opt);
		$_category->setValue($request->getParam('category'));
		
		$_service_id=  new Zend_Dojo_Form_Element_FilteringSelect('service_id');
		$_service_id->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_service_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_SERVICE"));
		$ser = $dbgb->getAllService();
		if (!empty($ser)) foreach ($ser as $ct){
			$_service_opt[$ct['id']]=$ct['name'];
		}
		$_service_id->setMultiOptions($_service_opt);
		$_service_id->setValue($request->getParam('service_id'));
		
		$_product_id=  new Zend_Dojo_Form_Element_FilteringSelect('product_id');
		$_product_id->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_product_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_PRODUCT"));
		$pro = $dbgb->getAllProduct();
		if (!empty($pro)) foreach ($pro as $ct){
			$_product_opt[$ct['id']]=$ct['name'];
		}
		$_product_id->setMultiOptions($_product_opt);
		$_product_id->setValue($request->getParam('product_id'));
		
		$_standard=  new Zend_Dojo_Form_Element_FilteringSelect('standard');
		$_standard->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_standard_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_STANDARD"));
		$stan = $dbgb->getAllStandard();
		if (!empty($stan)) foreach ($stan as $ct){
			$_standard_opt[$ct['id']]=$ct['name'];
		}
		$_standard->setMultiOptions($_standard_opt);
		$_standard->setValue($request->getParam('standard'));
		
		$_made_by=  new Zend_Dojo_Form_Element_FilteringSelect('made_by');
		$_made_by->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_made_by_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_MADE_BY"));
		$_madeby = $dbgb->getAllMadeBy();
		if (!empty($_madeby)) foreach ($_madeby as $ct){
			$_made_by_opt[$ct['id']]=$ct['name'];
		}
		$_made_by->setMultiOptions($_made_by_opt);
		$_made_by->setValue($request->getParam('made_by'));
		
		$_floor=  new Zend_Dojo_Form_Element_FilteringSelect('floor');
		$_floor->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_FLOOR"));
		$_row= $dbgb->getAllOther(2);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_floor->setMultiOptions($_line_opt);
		$_floor->setValue($request->getParam('floor'));
		
		$_line=  new Zend_Dojo_Form_Element_FilteringSelect('line');
		$_line->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_LINE"));
		$_row= $dbgb->getAllOther(3);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_line->setMultiOptions($_line_opt);
		$_line->setValue($request->getParam('line'));
		
		$_side=  new Zend_Dojo_Form_Element_FilteringSelect('side');
		$_side->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_SIDE"));
		$_row= $dbgb->getAllOther(4);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_side->setMultiOptions($_line_opt);
		$_side->setValue($request->getParam('side'));
		
		$_start_direction=  new Zend_Dojo_Form_Element_FilteringSelect('start_direction');
		$_start_direction->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_START_DIRECTION"));
		$_row= $dbgb->getAllOther(5);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_start_direction->setMultiOptions($_line_opt);
		$_start_direction->setValue($request->getParam('start_direction'));
		
		$_verification=  new Zend_Dojo_Form_Element_FilteringSelect('verification');
		$_verification->setAttribs(array('dojoType'=>$this->filter,
				'style'=>"width:50%;",
				'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_VERIFICATION"));
		$_row= $dbgb->getAllOther(6);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_verification->setMultiOptions($_line_opt);
		$_verification->setValue($request->getParam('verification'));
		
		$_other=  new Zend_Dojo_Form_Element_FilteringSelect('other');
		$_other->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside','queryExpr'=>'*${0}*','autoComplete'=>'false',));
		$_line_opt = array(
				0=>$this->tr->translate("PLEASE_SELECT_OTHER"));
		$_row= $dbgb->getAllOther(1);
		if (!empty($_row)) foreach ($_row as $ct){
			$_line_opt[$ct['id']]=$ct['name'];
		}
		$_other->setMultiOptions($_line_opt);
		$_other->setValue($request->getParam('other'));
		
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
		$_province->setValue($request->getParam('province'));
		
		$_street_id = new Zend_Dojo_Form_Element_FilteringSelect('street_id');
		$_street_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
		));
		
		$rows =  $dbgb->getAllStreet();
		$options=array($this->tr->translate("SELECT_STREET"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['id']]=$row['name'];
		$_street_id->setMultiOptions($options);
		$_street_id->setValue($request->getParam('street_id'));
		
		$_zone_id = new Zend_Dojo_Form_Element_FilteringSelect('zone_id');
		$_zone_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'autoComplete'=>'false',
				'queryExpr'=>'*${0}*',
		));
		$rows =  $dbgb->getAllZone();
		$options=array($this->tr->translate("SELECT_ZONE"));
		if(!empty($rows))foreach($rows AS $row) $options[$row['id']]=$row['name'];
		$_zone_id->setMultiOptions($options);
		$_zone_id->setValue($request->getParam('zone_id'));
		$this->addElements(array($from_date,$to_date,$type,$_title,$_status,$_btn_search,$user,
				$_category,
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
				));
		return $this;
	}
	
}