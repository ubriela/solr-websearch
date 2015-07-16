<?php

class Solr_Document_LandLocation extends Solr_Document_LandBase {

    public static function getDocuments($docid) {
	try {
	    $document = self::getBaseDocument($docid);

	    // get user contact
	    $obj_date = new Land_Location();
	    $contact = $obj_news->getLandLocation($docid);
	    $document["address"] = $obj_date[0]["address"];
	    $document["district"] = $obj_date[0]["district"];
	    $document["street_width"] = $obj_date[0]["street_width"];
	    $document["front_width"] = $obj_date[0]["front_width"];
	    $document["direction"] = $obj_date[0]["direction"];
	    
	    return array($document);
	} catch (Exception $ex) {
	    throw new Exception("Error when get profile document :" . $ex->getMessage());
	}
    }

}

?>
