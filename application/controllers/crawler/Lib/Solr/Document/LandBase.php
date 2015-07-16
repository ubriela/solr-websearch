<?php

abstract class Solr_Document_LandBase {

    abstract public static function getDocuments($docid);

    /*
     * The base document includes three fields: docid, title, and description
     */

    public static function getBaseDocument($docid) {
	try {
	    $document = array();
	    Log::dumpLog("Geting document = $docid");
	    $objProfile = new Land_Profile();
	    $profile = $objProfile->getProfile($docid);
	    if (empty($profile))
		throw new Exception("GET DOCUMENT EMPTY !!!!!");
	    $profile = $profile[0];
	    $document["docid"] = $profile["docid"];
	    $document["title"] = $profile["title"];
	    $document["description"] = $profile["description"];
	    $document["estate_type"] = $profile["estate_type"];
	    $document["new_id"] = $profile["new_id"];
	    $document["new_source"] = $profile["new_source"];
	    $document["new_link"] = $profile["new_link"];
	    $document["sale"] = $profile["sale"];

	    return $document;
	} catch (Exception $ex) {
	    throw new Exception("Error when get document :" . $ex->getMessage());
	}
    }

}

?>
