<?php
class Solr_Document_LandDate extends Solr_Document_LandBase {
	public static function getDocuments($docid) {
		try {
			$document = self::getBaseDocument($docid);

			// get user contact
			$obj_date = new Land_Date();
			$contact = $obj_news->getLandDate($docid);
			$document["news_publish_date"] = $obj_date[0]["news_publish_date"];
			$document["news_expire_date"] = $obj_date[0]["news_expire_date"];

			return array($document);
		}
		catch(Exception $ex) {
			throw new Exception("Error when get profile document :".$ex->getMessage());
		}
	}
}

?>
