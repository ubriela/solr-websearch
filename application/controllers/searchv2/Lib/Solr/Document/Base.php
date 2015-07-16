<?php
abstract class Solr_Document_Base
{
	abstract public static function getDocuments($userid);

	/*
	 * get profile infomation
	 */
	public static function getBaseDocument($userid) {
		try {
			$document = array();
			Log::dumpLog("Geting profile userid = $userid");
			$objProfile = new Zing_Me_Business_User_Profile();
			$profile = $objProfile->getProfile( $userid );
			if(empty($profile))
				throw new Exception("GET PROFILE EMPTY !!!!!");
			$profile = $profile[0];
			$document["userid"] = $profile["userid"];
			$document["avatarversion"] = $profile["avatarversion"] > 0 ? $profile["avatarversion"] : -1;
			$document["username"] = $profile["username"];
			$document["email"] = $profile["email"];
			$document["firstname"] = $profile["firstname"];
			$document["fullname"] = trim($profile["lastname"]  .' '. $profile["firstname"]);
			$document["fullname"] = !empty($document["fullname"]) ? $document["fullname"] : $document["username"];
			$document["gender"] = $profile["gender"];
			if(!empty($profile['dob']))
					$document["dob"] = date("Y-m-d\TH:i:s.000\Z",strtotime($profile['dob']));
				else
					$document["dob"] =  "1984-01-01T20:12:06.000Z";
			// get number of friend
			Log::dumpLog("Geting number of friend");
			$obj_friend = new Zing_Me_Business_Relationship_Friends();
			$document["totalfriend"] = intval($obj_friend->getTotalFriend($userid));

			// get total point - so diem cho hoan tat profile
			Log::dumpLog("Geting total point");
			$obj_point = new Zing_Me_Profile_Point();
			$document["totalpoint"] = intval($obj_point->getTotalPointByUser($userid));

			return $document;
		}
		catch(Exception $ex) {
			throw new Exception("Error when get profile document :".$ex->getMessage());
		}
	}
}

?>
