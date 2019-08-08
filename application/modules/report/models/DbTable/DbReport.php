<?php
class Report_Model_DbTable_DbReport extends Zend_Db_Table_Abstract
{
	function getAllClients($search = null){
		try{
			$db = $this->getAdapter();
			$this->_name = "ln_client";
			$dbp = new Application_Model_DbTable_DbGlobal();
			$currentLang = $dbp->currentlang();
			$column = "title";
			$sexCol = "name_en";
			$village = "village_namekh";
			$district = "district_namekh";
			$commune = "commune_namekh";
			$province = "province_kh_name";
			if ($currentLang==1){
				$column = "title";
				$sexCol = "name_kh";
 				//$province = "province_en_name";
				//$commune = "commune_name";
				//$village = "ln_village";
				//$district = "district_name";
			}
			$sql = "
			SELECT 
			c.*,
			(SELECT d.$column FROM `crm_category` AS d WHERE d.id= c.category LIMIT 1) AS category,
			CONCAT( COALESCE(c.name_kh,''),'/',COALESCE(c.name_en,'') ) as name,
			(SELECT $sexCol FROM `ln_view` WHERE TYPE =11 AND c.sex=key_code LIMIT 1) AS sex,
			(SELECT d.$column FROM `crm_service` AS d WHERE d.id= c.service_id LIMIT 1) AS service,
			(SELECT d.$column FROM `crm_product` AS d WHERE d.id= c.product_id LIMIT 1) AS product,
			(SELECT d.$column FROM `crm_standard` AS d WHERE d.id= c.standard LIMIT 1) AS standard,
			(SELECT d.$column FROM `crm_made_by` AS d WHERE d.id= c.made_by LIMIT 1) AS madeby,
			
			(SELECT CONCAT( COALESCE(d.str_no,''),'-',COALESCE(d.str_title,'') ) FROM `crm_street` AS d WHERE d.id= c.street_id LIMIT 1) AS street,
			(SELECT d.$column FROM `crm_zone` AS d WHERE d.id= c.zone_id LIMIT 1) AS zone,
			(SELECT d.$column FROM `crm_line` AS d WHERE d.id= c.line LIMIT 1) AS line,
			(SELECT d.$column FROM `crm_floor` AS d WHERE d.id= c.floor LIMIT 1) AS floor,
			
			(SELECT d.$column FROM `crm_side` AS d WHERE d.id= c.side LIMIT 1) AS side,
			(SELECT d.$column FROM `crm_start_direction` AS d WHERE d.id= c.start_direction LIMIT 1) AS start_direction,
			(SELECT d.$column FROM `crm_verification` AS d WHERE d.id= c.verification LIMIT 1) AS verification,
			(SELECT d.$column FROM `crm_other` AS d WHERE d.id= c.other LIMIT 1) AS other,
			
			(SELECT d.$province FROM `ln_province` AS d WHERE d.province_id= c.pro_id LIMIT 1) AS province,
			(SELECT d.$district FROM `ln_district` AS d WHERE d.dis_id= c.dis_id LIMIT 1) AS district,
			(SELECT e.$commune FROM `ln_commune` AS e WHERE e.com_id= c.com_id LIMIT 1) AS commnune,
			(SELECT v.$village FROM `ln_village` AS v WHERE v.vill_id= village_id LIMIT 1) AS village_name,
			(SELECT  CONCAT(first_name) FROM rms_users WHERE id=c.user_id LIMIT 1 ) AS user_name ";
			$sql.=" FROM $this->_name AS c ";
				
			$from_date =(empty($search['start_date']))? '1': "c.create_date >= '".$search['start_date']." 00:00:00'";
			$to_date = (empty($search['end_date']))? '1': "c.create_date <= '".$search['end_date']." 23:59:59'";
			$where = " WHERE c.status=1 AND ".$from_date." AND ".$to_date;
				
			if(!empty($search['adv_search'])){
					$s_where = array();
					$s_search = addslashes(trim($search['adv_search']));
					$s_search = str_replace(' ', '', addslashes(trim($search['adv_search'])));
					$s_where[] = " c.client_number LIKE '%{$s_search}%'";
					$s_where[] = " c.name_kh LIKE '%{$s_search}%'";
					$s_where[] = " c.name_en LIKE '%{$s_search}%'";
					$s_where[] = " c.contact LIKE '%{$s_search}%'";
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
	function getClientsProfile($id){
		try{
			$db = $this->getAdapter();
			$this->_name = "ln_client";
			$dbp = new Application_Model_DbTable_DbGlobal();
			$currentLang = $dbp->currentlang();
			$column = "title";
			$sexCol = "name_en";
			$village = "village_namekh";
			$district = "district_namekh";
			$commune = "commune_namekh";
			$province = "province_kh_name";
			if ($currentLang==1){
				$column = "title";
				$sexCol = "name_kh";
				//$province = "province_en_name";
				//$commune = "commune_name";
				//$village = "ln_village";
				//$district = "district_name";
			}
			$sql = "
			SELECT
			c.*,
			(SELECT d.$column FROM `crm_category` AS d WHERE d.id= c.category LIMIT 1) AS category,
			CONCAT( COALESCE(c.name_kh,''),'/',COALESCE(c.name_en,'') ) as name,
			(SELECT $sexCol FROM `ln_view` WHERE TYPE =11 AND c.sex=key_code LIMIT 1) AS sex,
			(SELECT d.$column FROM `crm_service` AS d WHERE d.id= c.service_id LIMIT 1) AS service,
			(SELECT d.$column FROM `crm_product` AS d WHERE d.id= c.product_id LIMIT 1) AS product,
			(SELECT d.$column FROM `crm_standard` AS d WHERE d.id= c.standard LIMIT 1) AS standard,
			(SELECT d.$column FROM `crm_made_by` AS d WHERE d.id= c.made_by LIMIT 1) AS madeby,
				
			(SELECT CONCAT( COALESCE(d.str_no,''),'-',COALESCE(d.str_title,'') ) FROM `crm_street` AS d WHERE d.id= c.street_id LIMIT 1) AS street,
			(SELECT d.$column FROM `crm_zone` AS d WHERE d.id= c.zone_id LIMIT 1) AS zone,
			(SELECT d.$column FROM `crm_line` AS d WHERE d.id= c.line LIMIT 1) AS line,
			(SELECT d.$column FROM `crm_floor` AS d WHERE d.id= c.floor LIMIT 1) AS floor,
				
			(SELECT d.$column FROM `crm_side` AS d WHERE d.id= c.side LIMIT 1) AS side,
			(SELECT d.$column FROM `crm_start_direction` AS d WHERE d.id= c.start_direction LIMIT 1) AS start_direction,
			(SELECT d.$column FROM `crm_verification` AS d WHERE d.id= c.verification LIMIT 1) AS verification,
			(SELECT d.$column FROM `crm_other` AS d WHERE d.id= c.other LIMIT 1) AS other,
				
			(SELECT d.$province FROM `ln_province` AS d WHERE d.province_id= c.pro_id LIMIT 1) AS province,
			(SELECT d.$district FROM `ln_district` AS d WHERE d.dis_id= c.dis_id LIMIT 1) AS district,
			(SELECT e.$commune FROM `ln_commune` AS e WHERE e.com_id= c.com_id LIMIT 1) AS commnune,
			(SELECT v.$village FROM `ln_village` AS v WHERE v.vill_id= village_id LIMIT 1) AS village_name,
			(SELECT  CONCAT(first_name) FROM rms_users WHERE id=c.user_id LIMIT 1 ) AS user_name ";
			$sql.=" FROM $this->_name AS c WHERE c.status=1 AND c.id = $id ";
			return $db->fetchRow($sql);
	
		}catch (Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
}

