<?php
class Solr_Document_Profile extends Solr_Document_SellerBase {
	public static function getDocuments($userid) {
		try {

			$document = self::getBaseDocument($userid);

			// get user contact
			$obj_contact = new Zing_Me_Business_User_Contacts();
			$contact = $obj_contact->getUserContact($userid);
			$document["countryid"] = $contact[0]["countryid"];
			$document["cityid"] = $contact[0]["cityid"];
			return array($document);
		}
		catch(Exception $ex) {
			throw new Exception("Error when get profile document :".$ex->getMessage());
		}
	}
}

?>
