<?php
class Solr_Document_Occupation extends Solr_Document_Base {
	public static function getDocuments($userid) {
		try {

			// get school name
			Log::dumpLog("Geting company name name");
			$obj_occupation = new Zing_Me_Business_User_Occupation();
			$occupations = $obj_occupation->getUserOccupation($userid);

			if(empty($occupations) || !is_array($occupations))
				return false;

			$document = self::getBaseDocument($userid);

			$documents = array();
			foreach($occupations as $occupation) {
				$item = $document;
				$item['userid_occupationid'] = $userid."_".$occupation['user_occupation_id'];
				$item['occupationname'] = $occupation['occupationname'];
				$item['companycityid'] = $occupation['cityid'];
				$item['companycountryid'] = $occupation['countryid'];
				$item['companyname'] = $occupation['companyname'];
				$item["starttime"] = "";
				$item["endtime"] = "";
				if(!empty($occupation['starttime']) && $occupation['starttime'] != "0000-00-00 00:00:00")
					$item["starttime"] = date("Y-m-d\TH:i:s.000\Z",strtotime($occupation["starttime"]));
				else
					$item["starttime"] =  "1890-01-01T20:12:06.000Z";
				if(!empty($occupation['endtime']) && $occupation['endtime']!= "0000-00-00 00:00:00")
					$item["endtime"] = date("Y-m-d\TH:i:s.000\Z",strtotime($occupation["endtime"]));
				else
					$item["endtime"] = "2099-01-01T00:00:00.000Z";
				$item = preg_replace('@[\x00-\x08\x0B\x0C\x0E-\x1F]@', '', $item);
				$documents[] = $item;
				
			}
			return $documents;
		}
		catch(Exception $ex) {
			throw new Exception("Error when get knowlegde document :".$ex->getMessage());
		}
	}
}

?>
