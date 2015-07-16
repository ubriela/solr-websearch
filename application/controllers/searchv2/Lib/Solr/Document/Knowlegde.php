<?php
class Solr_Document_Knowlegde extends Solr_Document_Base {
	public static function getDocuments($userid) {
		try {

			// get school name
			Log::dumpLog("Geting school name");
			$obj_knowledge = new Zing_Me_Business_User_Knowledge();
			$knowledges = $obj_knowledge->getUserKnowledge($userid);
			if(empty($knowledges) || !is_array($knowledges))
				return false;

			$document = self::getBaseDocument($userid);
			// get company name
			Log::dumpLog("Geting company name");
			$obj_occupation = new Zing_Me_Business_User_Occupation();
			$document["companyname"] = $obj_occupation->getLatestCompanyNameByUser($userid);

			$documents = array();
			foreach($knowledges as $knowledge) {
				
				$item = $document;
				$item['userid_knowledgeid'] = $userid."_".$knowledge['user_knowledge_id'];
				$item['schoolname'] = $knowledge['schoolname'];
				$item['schoolcityid'] = $knowledge['cityid'];
				$item['schoolcountryid'] = $knowledge['countryid'];
				$item['schoolcateid'] = $knowledge['schoolcateid'];
				$item['specialization'] = $knowledge['speciality'];
				if(!empty($knowledge['starttime']))
					$item["starttime"] = date("Y-m-d\TH:i:s.000\Z",strtotime($knowledge['starttime']));
				else
					$item["starttime"] =  "1890-01-01T20:12:06.000Z";
				if(!empty($knowledge['endtime']))
					$item["endtime"] = date("Y-m-d\TH:i:s.000\Z",strtotime($knowledge['endtime']));
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
