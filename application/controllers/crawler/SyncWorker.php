<?php
class SyncWorker {
	protected $_configuration=null;
	protected $_profile_service = null;
	protected $_knowlegde_service = null;
	protected $_occupation_service = null;


	function __construct($config_path,$environment) {
		$this->loadConfig($config_path,$environment);
		$this->_profile_service = new Solr_Services($this->_configuration->solr->profile->toArray());
		$this->_knowlegde_service = new Solr_Services($this->_configuration->solr->knowlegde->toArray());
		$this->_occupation_service = new Solr_Services($this->_configuration->solr->occupation->toArray());
		Log::dumpLog("START PROCESS");
	}

	private function loadConfig($config_path,$environment) {

		$this->_configuration = new Zend_Config_Ini($config_path,$environment);
		Zend_Registry::set('configuration', $this->_configuration);
		if (!$this->_configuration) {
			Log::dumpLog("Config file not found or enviroment not found!!!",Zend_Log::ERR);
		}
	}

	public function run() {
		try {
			$gserver = $this->_configuration->gearman->server;
			$gport = $this->_configuration->gearman->port;
			$worker = new GearmanWorker();
			$worker->addServer($gserver, $gport);
			$jobs = $this->_configuration->job;
			$worker->addFunction($jobs->sync_search, array($this, "syncSearch"));
			while($worker->work());

		}
		catch(Exception $ex) {
			Log::dumpLog("Uncaught exception: "  . $ex->getMessage(), Log::ERR);
		}
	}
	public function syncSearch($job)
	{
		Log::dumpLog("======".$job->handle());
		$data = json_decode($job->workload(),true);
		if(empty($data))
			return;
		switch($data["flag"])
		{
			case Zing_Solr_Synchronize::SYNC_PROFILE:
				$this->updateProfile($data["userid"]);
			break;
			case Zing_Solr_Synchronize::SYNC_DELETE_PROFILE:
				$this->deleteProfile($data["userid"]);
			break;
			case Zing_Solr_Synchronize::SYNC_KNOWLEDGE:
				$this->updateKnowlegde($data["userid"]);
			break;
			case Zing_Solr_Synchronize::SYNC_DELETE_KNOWLEDGE:
				$this->deleteKnowlegde($data["userid"],$data["itemid"]);
			break;
			case Zing_Solr_Synchronize::SYNC_OCCUPATION:
				$this->updateOccupation($data["userid"]);
			break;
			case Zing_Solr_Synchronize::SYNC_DELETE_OCCUPATION:
				$this->deleteOccupation($data["userid"],$data["itemid"]);
			break;
			
		}
	}
	function updateProfile($userid) {
		Log::dumpLog("UPDATE PROFILE userid=$userid");
		$start_time = microtime(true);
		try {
			$start_getdoc = microtime(true);
			$document = Solr_Document_Profile::getDocuments($userid);
			$end_getdoc = microtime(true);
			Log::dumpLog("Submiting document to solr");
			$start_submit = microtime(true);
			$response = $this->_profile_service->addDocuments($document);
			$end_submit = microtime(true);
			if($response instanceof Apache_Solr_Response) {
				Log::dumpLog("Response from sorl Status = " .$response->getHttpStatus(). ", Message = ".$response->getHttpStatusMessage());
			}
			else {
				Log::dumpLog("Response from sorl Status = $response");
			}

			$this->unsetGlobal();
		}
		catch(Exception $ex) {
			$this->unsetGlobal();
			Log::dumpLog($ex->getMessage(). "\n Trace :" . $ex->getTraceAsString(),Zend_Log::ERR);
		}
		$end_time = microtime(true);
		Log::dumpLog("Get document time : ".($end_getdoc - $start_getdoc));
		Log::dumpLog("Submit Time       : ".($end_submit - $start_submit));
		Log::dumpLog("Total Time        : ".($end_time - $start_time));
		Log::dumpLog("==== End UPDATE PROFILE");

	}

	function deleteProfile($userid,$ocupationid) {
		Log::dumpLog("DELETE PROFILE userid=$userid");
		$userid = $data["userid"];
		try {
			Log::dumpLog("Deleting document from solr, userid= $userid");
			$response = $this->_profile_service->removeDocument($userid.$ocupationid);
			if($response instanceof Apache_Solr_Response) {
				Log::dumpLog("Response from sorl Status = " .$response->getHttpStatus(). ", Message = ".$response->getHttpStatusMessage());
			}
			else {
				Log::dumpLog("Response from sorl Status = $response");
			}

			$this->unsetGlobal();
		}
		catch(Exception $ex) {
			$this->unsetGlobal();
			Log::dumpLog($ex->getMessage(). "\n Trace :" . $ex->getTraceAsString(),Zend_Log::ERR);
		}
		Log::dumpLog("===End DELETE PROFILE");
	}

	public function updateKnowlegde($userid) {
		Log::dumpLog("UPDATE KNOWLEGDE userid=$userid");
		$start_time = microtime(true);
		try {
			$start_getdoc = microtime(true);
			$documents = Solr_Document_Knowlegde::getDocuments($userid);
			$end_getdoc = microtime(true);
			if(empty($documents))
			{
				Log::dumpLog("User have not update Knowlegde yet");
				$this->unsetGlobal();
				return;
			}
			Log::dumpLog("Submiting document to solr");
			$start_submit = microtime(true);
			$response = $this->_knowlegde_service->addDocuments($documents);
			$end_submit = microtime(true);
			if($response instanceof Apache_Solr_Response) {
				Log::dumpLog("Response from sorl Status = " .$response->getHttpStatus(). ", Message = ".$response->getHttpStatusMessage());
			}
			else {
				Log::dumpLog("Response from sorl Status = $response");
			}
			$this->unsetGlobal();
		}
		catch(Exception $ex) {
			$this->unsetGlobal();
			Log::dumpLog($ex->getMessage(). "\n Trace :" . $ex->getTraceAsString(),Zend_Log::ERR);
		}
		$end_time = microtime(true);
		Log::dumpLog("Get document time : ".($end_getdoc - $start_getdoc));
		Log::dumpLog("Submit Time       : ".($end_submit - $start_submit));
		Log::dumpLog("Total Time        : ".($end_time - $start_time));
		Log::dumpLog("===End UPDATE KNOWLEGDE");
	}

	public function deleteKnowlegde($userid,$knowledgeid)
	{
		Log::dumpLog("DELETE KNOWLEGDE userid=$userid");
		$start_time = microtime(true);
		try {
			Log::dumpLog("Deleting document from solr, userid= $userid");
			$response = $this->_knowlegde_service->removeDocument($userid.$knowledgeid);
			if($response instanceof Apache_Solr_Response) {
				Log::dumpLog("Response from sorl Status = " .$response->getHttpStatus(). ", Message = ".$response->getHttpStatusMessage());
			}
			else {
				Log::dumpLog("Response from sorl Status = $response");
			}
			$this->unsetGlobal();
		}
		catch(Exception $ex) {
			$this->unsetGlobal();
			Log::dumpLog($ex->getMessage(). "\n Trace :" . $ex->getTraceAsString(),Zend_Log::ERR);
		}
		Log::dumpLog("===End DELETE KNOWLEGDE");
	}

	public function updateOccupation($userid) {
		$start_time = microtime(true);
		Log::dumpLog("UPDATE OCCUPATION userid=$userid");
		try {
			$start_getdoc = microtime(true);
			$documents = Solr_Document_Occupation::getDocuments($userid);
			$end_getdoc = microtime(true);
			if(empty($documents))
			{
				Log::dumpLog("User have not update Occupation yet");
				$this->unsetGlobal();
				return;
			}
			Log::dumpLog("Submiting document to solr");
			$start_submit = microtime(true);
			$response = $this->_occupation_service->addDocuments($documents);
			$end_submit = microtime(true);
			if($response instanceof Apache_Solr_Response) {
				Log::dumpLog("Response from sorl Status = " .$response->getHttpStatus(). ", Message = ".$response->getHttpStatusMessage());
			}
			else {
				Log::dumpLog("Response from sorl Status = $response");
			}
			$this->unsetGlobal();
		}
		catch(Exception $ex) {
			$this->unsetGlobal();
			Log::dumpLog($ex->getMessage(). "\n Trace :" . $ex->getTraceAsString(),Zend_Log::ERR);
		}
		$end_time = microtime(true);
		Log::dumpLog("Get document time : ".($end_getdoc - $start_getdoc));
		Log::dumpLog("Submit Time       : ".($end_submit - $start_submit));
		Log::dumpLog("Total Time        : ".($end_time - $start_time));
		Log::dumpLog("===End UPDATE OCCUPATION");
	}

	public function deleteOccupation($userid)
	{
		Log::dumpLog("===DELETE OCCUPATION userid = userid ");
		try {
			Log::dumpLog("Deleting document from solr, userid= $userid");
			$response = $this->_knowlegde_service->removeDocument($userid);
			if($response instanceof Apache_Solr_Response) {
				Log::dumpLog("Response from sorl Status = " .$response->getHttpStatus(). ", Message = ".$response->getHttpStatusMessage());
			}
			else {
				Log::dumpLog("Response from sorl Status = $response");
			}
			$this->unsetGlobal();
		}
		catch(Exception $ex) {
			$this->unsetGlobal();
			Log::dumpLog($ex->getMessage(). "\n Trace :" . $ex->getTraceAsString(),Zend_Log::ERR);
		}
		Log::dumpLog("===End DELETE OCCUPATION");
	}

	private function unsetGlobal() {
		Globals::closeAllDbConnection();
		GlobalCache::flushLocalCache("default");
		GlobalCache::flushLocalCache("profile");
	}
}
?>