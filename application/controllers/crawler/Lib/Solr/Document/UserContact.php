<?php

class Solr_Document_UserContact extends Solr_Document_Base {
	public static function getDocuments($userid) {
		try {
			$document = self::getBaseDocument($userid);

			// get user contact
			$obj_contact = new User_Contact();
			$contact = $obj_contact->getUserContact($userid);
			$document["contact_address"] = $contact[0]["contact_address"];
			$document["contact_phone"] = $contact[0]["contact_phone"];
			$document["contact_mobile"] = $contact[0]["contact_mobile"];
			$document["contact_email"] = $contact[0]["contact_email"];

			return array($document);
		}
		catch(Exception $ex) {
			throw new Exception("Error when get profile document :".$ex->getMessage());
		}
	}
}

?>
