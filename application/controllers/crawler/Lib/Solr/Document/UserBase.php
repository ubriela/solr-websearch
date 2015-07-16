<?php
abstract class Solr_Document_UserBase {

    abstract public static function getDocuments($userid);

    /*
     * The estate based document includes three fields: userid, title, and description
     */

    public static function getBaseDocument($userid) {
	try {
	    $document = array();
	    Log::dumpLog("Geting document = $userid");
	    $objProfile = new User_Profile();
	    $profile = $objProfile->getProfile($userid);
	    if (empty($profile))
		throw new Exception("GET DOCUMENT EMPTY !!!!!");
	    $profile = $profile[0];
	    $document["userid"] = $profile["userid"];
	    $document["contact_name"] = $profile["contact_name"];

	    return $document;
	} catch (Exception $ex) {
	    throw new Exception("Error when get document :" . $ex->getMessage());
	}
    }
}


?>
