<?php

class Solr_Document_LandPrice extends Solr_Document_LandBase {

    public static function getDocuments($docid) {
	try {
	    $document = self::getBaseDocument($docid);

	    // get user contact
	    $obj_date = new Land_Price();
	    $contact = $obj_news->getLandPrice($docid);
	    $document["area"] = $obj_date[0]["area"];
	    $document["area_unit"] = $obj_date[0]["district"];
	    $document["price"] = $obj_date[0]["price"];
	    $document["price_unit"] = $obj_date[0]["price_unit"];
	    $document["number_of_bedroom"] = $obj_date[0]["number_of_bedroom"];
	    
	    return array($document);
	} catch (Exception $ex) {
	    throw new Exception("Error when get profile document :" . $ex->getMessage());
	}
    }

}

?>
