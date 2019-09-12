<?php

class Group_Model_DbTable_DbClient extends Zend_Db_Table_Abstract
{
    protected $_name = 'ln_client';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace(SYSTEM_SES);
    	return $session_user->user_id;
    }
	public function addClient($_data){
		try{
// 			if(!empty($_data['id'])){
// 				$oldClient_Code = $this->getClientById($_data['id']);
// 				$client_code = $oldClient_Code['client_number'];
// 			}else{
// // 				$db = new Application_Model_DbTable_DbGlobal();
// // 				$client_code = $db->getNewClientIdByBranch();
// 				$client_code = empty($_data['client_number'])?"":$_data['client_number'];
// 			}
			$client_code = empty($_data['client_number'])?"":$_data['client_number'];
			$record = $this->recordhistory($_data);
			$activityold = $record['activityold'];
			$after_edit_info = $record['after_edit_info'];
			$labelDescribtion = 'Add New Customer';
			
		    $_arr=array(
				'client_number'=> $client_code,
		    	'category'			=>$_data['category'],
				'name_kh'	  	=> $_data['name_kh'],
				'name_en'	  	=> $_data['name_en'],
				'sex'	      	=> $_data['sex'],
		    		
	    		'service_id'      	=> $_data['service_id'],
	    		'product_id'     	=> $_data['product_id'],
	    		'standard'  	=> $_data['standard'],
	    		'made_by'	  	=> $_data['made_by'],
		    		
				'contact'     	=> $_data['contact'],
				'street_id'	      	=> $_data['street_id'],
				'zone_id'		=>$_data['zone_id'],
		    	'village_id'	=>$_data['village'],
		    	'com_id' => $_data['commune'],
		    	'dis_id' => $_data['district'],
				'pro_id'	      	=> $_data['province'],
		    	'map'	      	=> $_data['map'],
		    		
		    	'note'	  	=> $_data['noted'],
		    	'user_id'	  	=> $this->getUserId(),
				 
		    	'modify_date' 	=> date("Y-m-d H:i:s"),
				
				'line' => $_data['line'],
		    	'floor'      => $_data['floor'],
		    	'side'	=> $_data['side'],
		    	'start_direction' => $_data['start_direction'],
		    	'verification'      	=> $_data['verification'],
		    		'other'      	=> $_data['other'],
		    		'ordering'      	=> $_data['ordering'],
			); 
		    
		    $part= PUBLIC_PATH.'/images/photo/';
		    $photo_name = $_FILES['photo']['name'];
		    if (!empty($photo_name)){
		    	$tem =explode(".", $photo_name);
		    	$image_name_stu = "profile_".date("Y").date("m").date("d").time().".".end($tem);
		    	$tmp = $_FILES['photo']['tmp_name'];
		    	if(move_uploaded_file($tmp, $part.$image_name_stu)){
		    		move_uploaded_file($tmp, $part.$image_name_stu);
		    		$photo = $image_name_stu;
		    		$_arr['photo_name']=$photo;
		    	}
		    }		    
			if(!empty($_data['id'])){
				$customer_id =  $_data['id'];
				$_arr['status'] = $_data['status'];
				$where = 'id = '.$customer_id;
				$this->update($_arr, $where);
				$labelDescribtion = 'Edit Customer '.$client_code;
			}else{
				$_arr['create_date'] = date("Y-m-d H:i:s");
				$_arr['status'] = 1;
				$customer_id = $this->insert($_arr);
			}
		
			$part= PUBLIC_PATH.'/images/document/';
			if (!file_exists($part)) {
				mkdir($part, 0777, true);
			}
		 
			if(!empty($_data['id'])){//only edit (delete only)
				$this->_name = "ln_client_document";
				$where1 =" client_id=".$_data['id'];
				$this->delete($where1);
			}
			
			if (!empty($_data['identity'])){
				$identity = $_data['identity'];
				$ids = explode(',', $identity);
				$image_name="";
				$photo="";
				$this->_name='ln_client_document';
				$detailId="";
				foreach ($ids as $i){
					$name = $_FILES['attachment'.$i]['name'];
					if (!empty($name)){
						$ss = 	explode(".", $name);
						$file_new = "document_".date("Y").date("m").date("d").time().$i.".".end($ss);
						$tmp = $_FILES['attachment'.$i]['tmp_name'];
						if(move_uploaded_file($tmp, $part.$file_new)){
							$photo_new = $file_new;
							$arr_new = array(
								'client_id'=>$customer_id,
								'document_name'=>$photo_new,
							);
							$this->insert($arr_new);
						}
					}else{
						$photo = $_data['old_file'.$i];
						$arr = array(
							'client_id'=>$customer_id,
							'document_name'=>$photo,
						);
						$this->insert($arr);
					}
				}
			}
			
			
			$dbgb = new Application_Model_DbTable_DbGlobal();
			$_datas = array('description'=>$labelDescribtion,'activityold'=>$activityold,'after_edit_info'=>$after_edit_info);
			$dbgb->addActivityUser($_datas);
			
			return true;
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	function recordhistory($_data){
		$arr=array();
		$stringold="";
		$string="";
		if (!empty($_data['id'])){
	
			$row=$this->getClientById($_data['id']);
			$sex = "M";
			if ($row['sex']==2){ $sex = "F";}
			$stringold="Customer Name : ".$row['name_kh']." - ".$row['name_en']."<br />";
			$stringold.="sex : ".$sex."<br />";
			$stringold.="Contact : ".$row['contact']."<br />";
	
			
			$sex = "M";
			if ($_data['sex']==2){
				$sex = "F";
			}
			$string="Customer Name : ".$_data['name_kh']." - ".$_data['name_en']."<br />";
			$string.="sex : ".$sex."<br />";
			$string.="Contact : ".$_data['contact']."<br />";
	
	
		}else{
			$string="";
			$sex = "M";
			if ($_data['sex']==2){ $sex = "F";}
			$stringold="Customer Name : ".$_data['name_kh']." - ".$_data['name_en']."<br />";
			$stringold.="sex : ".$sex."<br />";
			$stringold.="Contact : ".$_data['contact']."<br />";
		}
		
		$arr['activityold']=$stringold;
		$arr['after_edit_info']=$string;
		return $arr;
	}
	public function getClientById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM $this->_name WHERE id = ".$db->quote($id);
		$sql.=" LIMIT 1 ";
			$row=$db->fetchRow($sql);
			return $row;
	}
	public function getDocumentClientById($client_id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM ln_client_document WHERE client_id = ".$client_id;
		return $db->fetchAll($sql);
	}
	public function getMovingClientById($client_id){
		$db = $this->getAdapter();
		$dbp = new Application_Model_DbTable_DbGlobal();
		$currentLang = $dbp->currentlang();
		$column = "title";
		$village = "village_namekh";
		$district = "district_namekh";
		$commune = "commune_namekh";
		$province = "province_kh_name";
		if ($currentLang==1){
			$column = "title";
			//$province = "province_en_name";
			//$commune = "commune_name";
			//$village = "ln_village";
			//$district = "district_name";
		}
		$sql = "SELECT m.*,
			(SELECT CONCAT( COALESCE(d.str_no,''),'-',COALESCE(d.str_title,'') ) FROM `crm_street` AS d WHERE d.id= m.street_id LIMIT 1) AS street,
			(SELECT d.$column FROM `crm_zone` AS d WHERE d.id= m.zone_id LIMIT 1) AS zone,
			(SELECT d.$column FROM `crm_line` AS d WHERE d.id= m.line LIMIT 1) AS line,
			(SELECT d.$column FROM `crm_floor` AS d WHERE d.id=m.floor LIMIT 1) AS floor,
			
			(SELECT d.$column FROM `crm_side` AS d WHERE d.id= m.side LIMIT 1) AS side,
			(SELECT d.$column FROM `crm_start_direction` AS d WHERE d.id= m.start_direction LIMIT 1) AS start_direction,
			(SELECT d.$column FROM `crm_other` AS d WHERE d.id= m.other LIMIT 1) AS other,
			
			(SELECT d.$province FROM `ln_province` AS d WHERE d.province_id= m.pro_id LIMIT 1) AS province,
			(SELECT d.$district FROM `ln_district` AS d WHERE d.dis_id= m.dis_id LIMIT 1) AS district,
			(SELECT e.$commune FROM `ln_commune` AS e WHERE e.com_id= m.com_id LIMIT 1) AS commnune,
			(SELECT v.$village FROM `ln_village` AS v WHERE v.vill_id= m.village_id LIMIT 1) AS village_name,
			
			(SELECT CONCAT( COALESCE(d.str_no,''),'-',COALESCE(d.str_title,'') ) FROM `crm_street` AS d WHERE d.id= m.new_street_id LIMIT 1) AS new_street,
			(SELECT d.$column FROM `crm_zone` AS d WHERE d.id= m.new_zone_id LIMIT 1) AS new_zone,
			(SELECT d.$column FROM `crm_line` AS d WHERE d.id= m.new_line LIMIT 1) AS new_line,
			(SELECT d.$column FROM `crm_floor` AS d WHERE d.id=m.new_floor LIMIT 1) AS new_floor,
			
			(SELECT d.$column FROM `crm_side` AS d WHERE d.id= m.new_side LIMIT 1) AS new_side,
			(SELECT d.$column FROM `crm_start_direction` AS d WHERE d.id= m.new_start_direction LIMIT 1) AS new_start_direction,
			(SELECT d.$column FROM `crm_other` AS d WHERE d.id= m.new_other LIMIT 1) AS new_other,
			
			(SELECT d.$province FROM `ln_province` AS d WHERE d.province_id= m.new_pro_id LIMIT 1) AS new_province,
			(SELECT d.$district FROM `ln_district` AS d WHERE d.dis_id= m.new_dis_id LIMIT 1) AS new_district,
			(SELECT e.$commune FROM `ln_commune` AS e WHERE e.com_id= m.new_com_id LIMIT 1) AS new_commnune,
			(SELECT v.$village FROM `ln_village` AS v WHERE v.vill_id= m.new_village_id LIMIT 1) AS new_village_name
			
		 FROM ln_client_moving AS m  WHERE m.client_id = ".$client_id;
		return $db->fetchAll($sql);
	}
	function getAllClients($search = null){		
		try{	
			$db = $this->getAdapter();
			
			$dbp = new Application_Model_DbTable_DbGlobal();
			$currentLang = $dbp->currentlang();
			$column = "title";
			$sexCol = "name_en";
			$village = "village_namekh";
			$district = "district_namekh";
			$commune = "commune_namekh";
			if ($currentLang==1){
				$column = "title";
				$sexCol = "name_kh";
// 				$village = "ln_village";
// 				$district = "district_name";
			}
			$sql = "
			SELECT c.id,
			c.client_number,
			(SELECT d.$column FROM `crm_category` AS d WHERE d.id= c.category LIMIT 1) AS category,
			CONCAT( COALESCE(c.name_kh,''),'/',COALESCE(c.name_en,'') ) as name,
			(SELECT $sexCol FROM `ln_view` WHERE TYPE =11 AND c.sex=key_code LIMIT 1) AS sex,
			c.contact,
			(SELECT d.$column FROM `crm_service` AS d WHERE d.id= c.service_id LIMIT 1) AS service,
			(SELECT d.$column FROM `crm_product` AS d WHERE d.id= c.product_id LIMIT 1) AS product,
			(SELECT d.$column FROM `crm_standard` AS d WHERE d.id= c.standard LIMIT 1) AS standard,
			
			(SELECT CONCAT( COALESCE(d.str_no,''),'-',COALESCE(d.str_title,'') ) FROM `crm_street` AS d WHERE d.id= c.street_id LIMIT 1) AS street,
			(SELECT d.$column FROM `crm_zone` AS d WHERE d.id= c.zone_id LIMIT 1) AS zone,
			
			(SELECT d.$district FROM `ln_district` AS d WHERE d.dis_id= c.dis_id LIMIT 1) AS district, 
			(SELECT e.$commune FROM `ln_commune` AS e WHERE e.com_id= c.com_id LIMIT 1) AS commnune, 
			(SELECT v.$village FROM `ln_village` AS v WHERE v.vill_id= village_id LIMIT 1) AS village_name,
			
			(SELECT d.$column FROM `crm_side` AS d WHERE d.id= c.side LIMIT 1) AS side,
			(SELECT d.$column FROM `crm_start_direction` AS d WHERE d.id= c.start_direction LIMIT 1) AS start_direction,
			
			c.create_date,
			(SELECT  CONCAT(first_name) FROM rms_users WHERE id=c.user_id LIMIT 1 ) AS user_name ";
			
			$sql.=$dbp->caseStatusShowImage("c.status");
			$sql.=" FROM $this->_name AS c ";
			
			$from_date =(empty($search['start_date']))? '1': "c.create_date >= '".$search['start_date']." 00:00:00'";
			$to_date = (empty($search['end_date']))? '1': "c.create_date <= '".$search['end_date']." 23:59:59'";
			$where = " WHERE  ".$from_date." AND ".$to_date;
			
			if(!empty($search['adv_search'])){
				$s_where = array();
				$s_search = addslashes(trim($search['adv_search']));
				$s_search = str_replace(' ', '', addslashes(trim($search['adv_search'])));
				$s_where[] = " REPLACE(c.client_number,' ','') LIKE '%{$s_search}%'";
				$s_where[] = " REPLACE(c.name_kh,' ','') LIKE '%{$s_search}%'";
				$s_where[] = " REPLACE(c.name_en,' ','') LIKE '%{$s_search}%'";
				$s_where[] = " REPLACE(c.contact,' ','')  LIKE '%{$s_search}%'";
				
				$s_where[] = " REPLACE((SELECT d.$column FROM `crm_service` AS d WHERE d.id= c.service_id LIMIT 1),' ','') LIKE '%{$s_search}%'";
				$s_where[] = " REPLACE((SELECT d.$column FROM `crm_product` AS d WHERE d.id= c.product_id LIMIT 1),' ','') LIKE '%{$s_search}%'";
				$s_where[] = " REPLACE((SELECT d.$column FROM `crm_verification` AS d WHERE d.id= c.verification LIMIT 1),' ','') LIKE '%{$s_search}%'";
				$s_where[] = " REPLACE((SELECT d.$column FROM `crm_other` AS d WHERE d.id= c.other LIMIT 1),' ','') LIKE '%{$s_search}%'";
				
// 				$s_where[] = " REPLACE((SELECT d.$province FROM `ln_province` AS d WHERE d.province_id= c.pro_id LIMIT 1),' ','')  LIKE '%{$s_search}%'";
					$s_where[] = " REPLACE((SELECT d.$district FROM `ln_district` AS d WHERE d.dis_id= c.dis_id LIMIT 1),' ','')  LIKE '%{$s_search}%'";
					$s_where[] = " REPLACE((SELECT e.$commune FROM `ln_commune` AS e WHERE e.com_id= c.com_id LIMIT 1),' ','')  LIKE '%{$s_search}%'";
					$s_where[] = " REPLACE((SELECT v.$village FROM `ln_village` AS v WHERE v.vill_id= village_id LIMIT 1),' ','') LIKE '%{$s_search}%'";
					$s_where[] = " REPLACE((SELECT d.$column FROM `crm_zone` AS d WHERE d.id= c.zone_id LIMIT 1),' ','') LIKE '%{$s_search}%'";
					$s_where[] = " REPLACE((SELECT CONCAT( COALESCE(d.str_no,''),'-',COALESCE(d.str_title,'') ) FROM `crm_street` AS d WHERE d.id= c.street_id LIMIT 1),' ','') LIKE '%{$s_search}%'";
				
				$where .=' AND ('.implode(' OR ',$s_where).')';
			}
			if(!empty($search['category'])){
				$where.=" AND c.category= ".$search['category'];
			}
			if(!empty($search['service_id'])){
				$where.=" AND c.service_id= ".$search['service_id'];
			}
			if(!empty($search['product_id'])){
				$where.=" AND c.product_id= ".$search['product_id'];
			}
			if($search['status']>-1){
				$where.= " AND c.status = ".$search['status'];
			}
			if($search['province']>0){
				$where.=" AND c.pro_id= ".$search['province'];
			}
			if(!empty($search['district_id'])){
				$where.=" AND c.dis_id= ".$search['district_id'];
			}
			if(!empty($search['comm_id'])){
				$where.=" AND c.com_id= ".$search['comm_id'];
			}
			if(!empty($search['village'])){
				$where.=" AND c.village_id= ".$search['village'];
			}
			
			if(!empty($search['standard'])){
				$where.=" AND c.standard= ".$search['standard'];
			}
			if(!empty($search['made_by'])){
				$where.=" AND c.made_by= ".$search['made_by'];
			}
			if(!empty($search['street_id'])){
				$where.=" AND c.street_id= ".$search['street_id'];
			}
			if(!empty($search['zone_id'])){
				$where.=" AND c.zone_id= ".$search['zone_id'];
			}
			if(!empty($search['line'])){
				$where.=" AND c.line= ".$search['line'];
			}
			if(!empty($search['floor'])){
				$where.=" AND c.floor= ".$search['floor'];
			}
			if(!empty($search['side'])){
				$where.=" AND c.side= ".$search['side'];
			}
			if(!empty($search['start_direction'])){
				$where.=" AND c.start_direction= ".$search['start_direction'];
			}
			if(!empty($search['verification'])){
				$where.=" AND c.verification= ".$search['verification'];
			}
			
			$order=" ORDER BY c.ordering ASC ";
			return $db->fetchAll($sql.$where.$order);
			
		}catch (Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}	
	}
	
	public function addMoving($_data){
		try{
			$row = $this->getClientById($_data['client_id']);
			$_arr=array(
					'client_id'			=>$_data['client_id'],
					'moving_date'	  	=> $_data['moving_date'],
	
					'street_id'	      	=> $row['street_id'],
					'zone_id'		=>$row['zone_id'],
					'village_id'	=>$row['village_id'],
					'com_id' => $row['com_id'],
					'dis_id' => $row['dis_id'],
					'pro_id'	      	=> $row['pro_id'],
					'side'	=> $row['side'],
					'start_direction' => $row['start_direction'],
					'map'	      	=> $row['map'],
					'line' => $row['line'],
					'floor'      => $row['floor'],
					'other'      => $row['other'],
					
					'new_street_id'	      	=> $_data['street_id'],
					'new_zone_id'		=>$_data['zone_id'],
					'new_village_id'	=>$_data['village'],
					'new_com_id' => $_data['commune'],
					'new_dis_id' => $_data['district'],
					'new_pro_id'	      	=> $_data['province'],
					'new_side'	=> $_data['side'],
					'new_start_direction' => $_data['start_direction'],
					'new_map'	      	=> $_data['map'],
					'new_line' => $_data['line'],
					'new_floor'      => $_data['floor'],
					'new_other'      => $_data['other'],
					
					'note'	  	=> $_data['noted'],
					'user_id'	  	=> $this->getUserId(),
					'modify_date' 	=> date("Y-m-d H:i:s"),
					'create_date' 	=> date("Y-m-d H:i:s"),
					
			);
			$this->_name = "ln_client_moving";
			$moving_id = $this->insert($_arr);
				
			$_arr=array(
					'street_id'	      	=> $_data['street_id'],
					'zone_id'		=>$_data['zone_id'],
					'village_id'	=>$_data['village'],
					'com_id' => $_data['commune'],
					'dis_id' => $_data['district'],
					'pro_id'	      	=> $_data['province'],
					'map'	      	=> $_data['map'],
					'side'	=> $_data['side'],
					'start_direction' => $_data['start_direction'],
					
					'line' => $_data['line'],
					'floor'      => $_data['floor'],
					'other'      => $_data['other'],
					
			);
			$this->_name = "ln_client";
			$where = "id = ".$_data['client_id'];
			$this->update($_arr, $where);
			return true;
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	
}