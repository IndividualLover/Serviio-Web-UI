<?php

class ServiioService extends RestRequest
{

    protected $host;
    protected $port;

    public $error;
    public $warning;

    public $searchHiddenFiles;
    public $searchForUpdates;
    public $automaticLibraryUpdate;
    public $automaticLibraryUpdateInterval;
    public $maxNumberOfItemsForOnlineFeeds;
    public $onlineFeedExpiryInterval;
    public $onlineContentPreferredQuality;
    public $repository;

    public $profiles;
    public $renderers;
    public $boundNICName;
	public $defaultAccessGroupId;
	public $rendererEnabledByDefault;
    public $audioLocalArtExtractorEnabled;
    public $videoLocalArtExtractorEnabled;
    public $videoOnlineArtExtractorEnabled;
    public $videoGenerateLocalThumbnailEnabled;
    public $imageGenerateLocalThumbnailEnabled;
    public $metadataLanguage;
    public $descriptiveMetadataExtractor;
    public $retrieveOriginalTitle;

    public $descriptiveMetadataExtractors;
    public $browsingCategoriesLanguages;

    public $audioDownmixing;
    public $threadsNumber;
    public $transcodingFolderLocation;
    public $transcodingEnabled;
    public $bestVideoQuality;
	public $subtitlesEnabled;
	public $embeddedSubtitlesExtractionEnabled;
	public $hardSubsEnabled;
	public $hardSubsForced;
	public $hardSubsCharacterEncoding;

    public $numberOfCPUCores;

    public $presentationLanguage;
    public $showParentCategoryTitle;
    public $numberOfFilesForDynamicCategories;

    public $remoteUserPassword;
    public $preferredRemoteDeliveryQuality;
	public $portMappingEnabled;
	public $externalAddress;

    public $language;
    public $securityPin;
    public $checkForUpdates;

    public $licenseEdition;

    /**
     */
    public function __construct ($host, $port)
    {
        parent::flush();
        $this->host = $host;
        $this->port = $port;
    }

    /**
     */
    public function getStatus()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/status');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get status";
            return false;
        }
        $serverStatus = (string)$xml->serverStatus;
        $boundNICName = (string)$xml->boundNICName;
		$defaultAccessGroupId = (string)$xml->defaultAccessGroupId;
		$rendererEnabledByDefault = (string)$xml->rendererEnabledByDefault;
        $this->renderers = array();
        foreach ($xml->renderers->renderer as $item) {
            $uuid = (string)$item->uuid;
            $ipAddress = (string)$item->ipAddress;
            $name = (string)$item->name;
            $profileId = (string)$item->profileId;
            $status = (string)$item->status;
            $enabled = (string)$item->enabled;
            $accessGroupId = (string)$item->accessGroupId;
			
            $this->renderers[$uuid] = array($ipAddress, $name, $profileId, $status, $enabled, $accessGroupId);
        }
        //return array("serverStatus"=>$serverStatus, "renderers"=>$this->renderers, "bound_nic"=>$bound_nic);
        return array("serverStatus"=>$serverStatus, "renderers"=>$this->renderers, "boundNICName"=>$boundNICName,"defaultAccessGroupId"=>$defaultAccessGroupId,"rendererEnabledByDefault"=>$rendererEnabledByDefault);
    }
	
	public function postdefault($id,$render)
    {
		parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/status');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get status";
            return false;
        }
		
		$defaultAccessGroupId = (string)$xml->defaultAccessGroupId;
		
		$bul=$xml->defaultAccessGroupId=$id;
		$bul2=$xml->rendererEnabledByDefault=$render;

      

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        return $requestBody;
        */
		parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/status');
        parent::setVerb('PUT');
        parent::setRequestBody($xml->saveXML());
        parent::execute();
		
        return print_r($bul);
		
    }

    /**
     */
    public function getRemoteAccess()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/remote-access');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get remote-access";
            return false;
        }
        $remoteUserPassword = (string)$xml->remoteUserPassword;
        $preferredRemoteDeliveryQuality = (string)$xml->preferredRemoteDeliveryQuality;
		$portMappingEnabled = (string)$xml->portMappingEnabled;
		$externalAddress = (string)$xml->externalAddress;
        return array("remoteUserPassword"=>$remoteUserPassword, "preferredRemoteDeliveryQuality"=>$preferredRemoteDeliveryQuality, "portMappingEnabled"=>$portMappingEnabled, "externalAddress"=>$externalAddress);
    }

    /**
     */
    public function getConsoleSettings()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/console-settings');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get console-settings";
            return false;
        }
        $language = (string)$xml->language;
        // had to set default just in case
        if ($language == "") {
            $language = "en";
        }
        //$securityPin = (string)$xml->securityPin;
        $checkForUpdates = (string)$xml->checkForUpdates;
        //return array("language"=>$language, "securityPin"=>$securityPin, "checkForUpdates"=>$checkForUpdates);
        return array("language"=>$language, "checkForUpdates"=>$checkForUpdates);
    }

    /**
     */
    public function getSystemStatus() {
        $arr = $this->getStatus();
        return $this->getLibraryStatus() + array("serverStatus"=>$arr["serverStatus"]);
    }

    /**
     */
    public function getServiceStatus()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/service-status');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get service status";
            return false;
        }
        $serviceStarted = (string)$xml->serviceStarted;
        return array("serviceStarted"=>$serviceStarted);
    }

    /**
     */
    public function getLibraryStatus()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/library-status');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get library status";
            return false;
        }
        $libraryUpdatesCheckerRunning = (string)$xml->libraryUpdatesCheckerRunning;
        $libraryAdditionsCheckerRunning = (string)$xml->libraryAdditionsCheckerRunning;
        $lastAddedFileName = (string)$xml->lastAddedFileName;
        $numberOfAddedFiles = (string)$xml->numberOfAddedFiles;
        return array("libraryUpdatesCheckerRunning"=>$libraryUpdatesCheckerRunning,
                     "libraryAdditionsCheckerRunning"=>$libraryAdditionsCheckerRunning,
                     "lastAddedFileName"=>$lastAddedFileName,
                     "numberOfAddedFiles"=>$numberOfAddedFiles);
    }

    /**
     */
    public function getReferenceData($property)
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/refdata/'.$property);
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get ".$property." data";
            return false;
        }
        $numberOfCPUCores = 1;
		
		$result = array();
		
        foreach ($xml->values->item as $item) {
            $value = (string)$item->value;
            $name = (string)$item->name;
            $result["${name}"] = $value;
        }
        
		switch ($property) {
			case "cpu-cores":
				$this->numberOfCPUCores = $result;				//delivery.php
				break;
			case "profiles":
				$this->profiles = $result;						//status.php
				break;
			case "metadataLanguages":
				$this->metadataLanguages = $result;				//metadata.php
				break;
			case "browsingCategoriesLanguages":
				$this->browsingCategoriesLanguages = $result;	//presentation.php
				break;
			case "descriptiveMetadataExtractors":
				$this->descriptiveMetadataExtractors = $result;	//metadata.php
				break;
			case "categoryVisibilityTypes":
				$this->categoryVisibilityTypes = $result;		//presentation.php
				break;
			case "onlineRepositoryTypes":
				$this->onlineRepositoryTypes = $result;			//library.php
				break;
			case "onlineContentQualities":
				$this->onlineContentQualities = $result;		//library.php
				break;
			case "accessGroups":
				$this->accessGroups = $result;					//status.php, library.php
				break;
			case "remoteDeliveryQualities":
				$this->remoteDeliveryQualities = $result;		//remote.php
				break;
			case "networkInterfaces":
				$this->boundNICName = $result;					//status.php
				break;
		}
		
        return $result;

        //$this->pvalues = array();
        //foreach ($xml->pvalues->pvalue as $item) {
        //    $name = (string)$item->name;
        //    $value = (string)$item->value;
        //    $this->pvalues[$name] = array($name, $value);
        //}
        //return array($this->pvalues);
    }

    /**
     */
    public function getPing()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/ping');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get ping";
            return false;
        }
        if ($xml->errorCode == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     */
    public function getApplication()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/application');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get application";
            return false;
        }
        $currentVersion = (string)$xml->version;
        $updateVersionAvailable = (string)$xml->updateVersionAvailable;

        $edition = (string)$xml->edition;
        $this->lic = array();
        // need to init these variables - thanks Strupniveral
        $id = "";
        $type = "";
        $name = "";
        $email = "";
        $expiresInMinutes = "";
        foreach ($xml->license as $licenseDetail) {
            $id = (string)$licenseDetail->id;
            $type = (string)$licenseDetail->type;
            $name = (string)$licenseDetail->name;
            $email = (string)$licenseDetail->email;
            $expiresInMinutes = (string)$licenseDetail->expiresInMinutes;
        }

        // record license type as global
        $this->licenseEdition = $edition;

        return array(
                     "version"=>$currentVersion,
                     "updateVersionAvailable"=>$updateVersionAvailable,
                     "edition"=>$edition,
                     "licenseID"=>$id,
                     "licenseType"=>$type,
                     "licenseName"=>$name,
                     "licenseEmail"=>$email,
                     "licenseExpiresInMinutes"=>$expiresInMinutes
                     );
    }

    /**
     */
    public function getRepository()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/repository');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get repository";
            return false;
        }
        $repo = array();
        $sf = array();
        $or = array();

        foreach ($xml->sharedFolders as $sharedFolders) {
            foreach ($sharedFolders as $item) {
                $id = (string)$item->id;
                $folderPath = (string)$item->folderPath;
                $supportedFileTypes = array();
                foreach ($item->supportedFileTypes as $types) {
                    foreach ($types as $type) {
                        $supportedFileTypes[] = (string)$type;
                    }
                }
                $descriptiveMetadataSupported = (string)$item->descriptiveMetadataSupported;
                $scanForUpdates = (string)$item->scanForUpdates;
                $accessGroupIds = array();
                if ($this->licenseEdition != "FREE") {
                    foreach ($item->accessGroupIds as $accessGroupId) {
                        foreach ($accessGroupId as $grpId) {
                            $accessGroupIds[] = (string)$grpId;
                        }
                    }
                }
                $sf[$id] = array($folderPath, $supportedFileTypes, $descriptiveMetadataSupported, $scanForUpdates, $accessGroupIds);
            }
        }
        $repo[0] = $sf;

        $this->searchHiddenFiles = (string)$xml->searchHiddenFiles;
        $this->searchForUpdates = (string)$xml->searchForUpdates;
        $this->automaticLibraryUpdate = (string)$xml->automaticLibraryUpdate;
        $this->automaticLibraryUpdateInterval = (string)$xml->automaticLibraryUpdateInterval;

        // onlineRepositories
        foreach ($xml->onlineRepositories as $onlineRepositories) {
            foreach ($onlineRepositories as $item) {
                $id = (string)$item->id;
                $repositoryType = (string)$item->repositoryType;
                $contentUrl = (string)$item->contentUrl;
                $fileType = (string)$item->fileType;
                $thumbnailUrl = (string)$item->thumbnailUrl;
                $repositoryName = (string)$item->repositoryName;
                $enabled = (string)$item->enabled;
                $ORaccessGroupIds = array();
                if ($this->licenseEdition != "FREE") {
                    foreach ($item->accessGroupIds as $accessGroupId) {
                        foreach ($accessGroupId as $grpId) {
                            $ORaccessGroupIds[] = (string)$grpId;
                        }
                    }
                }
                $or[$id] = array($repositoryType, $contentUrl, $fileType, $thumbnailUrl, $repositoryName, $enabled, $ORaccessGroupIds);
            }
        }
        $repo[1] = $or;
        $this->maxNumberOfItemsForOnlineFeeds = (string)$xml->maxNumberOfItemsForOnlineFeeds;
        $this->onlineFeedExpiryInterval = (string)$xml->onlineFeedExpiryInterval;
        $this->onlineContentPreferredQuality = (string)$xml->onlineContentPreferredQuality;

        $this->repository = $repo;
        return $repo;
    }

    /**
     */
    public function getMetadata()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/metadata');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get metadata";
            return false;
        }
        $audioLocalArtExtractorEnabled = (string)$xml->audioLocalArtExtractorEnabled;
        $videoLocalArtExtractorEnabled = (string)$xml->videoLocalArtExtractorEnabled;
        $videoOnlineArtExtractorEnabled = (string)$xml->videoOnlineArtExtractorEnabled;
        $videoGenerateLocalThumbnailEnabled = (string)$xml->videoGenerateLocalThumbnailEnabled;
        $imageGenerateLocalThumbnailEnabled = (string)$xml->imageGenerateLocalThumbnailEnabled;
        $metadataLanguage = (string)$xml->metadataLanguage;
        $retrieveOriginalTitle = (string)$xml->retrieveOriginalTitle;
        $descriptiveMetadataExtractor = (string)$xml->descriptiveMetadataExtractor;
        $this->audioLocalArtExtractorEnabled = $audioLocalArtExtractorEnabled;
        $this->videoLocalArtExtractorEnabled = $videoLocalArtExtractorEnabled;
        $this->videoOnlineArtExtractorEnabled = $videoOnlineArtExtractorEnabled;
        $this->videoGenerateLocalThumbnailEnabled = $videoGenerateLocalThumbnailEnabled;
        $this->imageGenerateLocalThumbnailEnabled = $imageGenerateLocalThumbnailEnabled;
        $this->metadataLanguage = $metadataLanguage;
        $this->retrieveOriginalTitle = $retrieveOriginalTitle;
        $this->descriptiveMetadataExtractor = $descriptiveMetadataExtractor;
        return array($audioLocalArtExtractorEnabled, $videoLocalArtExtractorEnabled, $videoOnlineArtExtractorEnabled, $videoGenerateLocalThumbnailEnabled, $imageGenerateLocalThumbnailEnabled, $metadataLanguage, $descriptiveMetadataExtractor, $retrieveOriginalTitle);
    }

    /**
     */
    public function getDelivery()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/delivery');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get delivery";
            return false;
        }
        
        $transcoding = $xml->transcoding;
        
        $audioDownmixing = (string)$transcoding->audioDownmixing;
        $threadsNumber = (string)$transcoding->threadsNumber;
        $transcodingFolderLocation = (string)$transcoding->transcodingFolderLocation;
        $transcodingEnabled = (string)$transcoding->transcodingEnabled;
        $bestVideoQuality = (string)$transcoding->bestVideoQuality;
        $this->audioDownmixing = $audioDownmixing;
        $this->threadsNumber = $threadsNumber;
        $this->transcodingFolderLocation = $transcodingFolderLocation;
        $this->transcodingEnabled = $transcodingEnabled;
        $this->bestVideoQuality = $bestVideoQuality;
		
		$subtitles = $xml->subtitles;
		
		$subtitlesEnabled = (string)$subtitles->subtitlesEnabled;
        $embeddedSubtitlesExtractionEnabled = (string)$subtitles->embeddedSubtitlesExtractionEnabled;
        $hardSubsEnabled = (string)$subtitles->hardSubsEnabled;
        $hardSubsForced = (string)$subtitles->hardSubsForced;
		$hardSubsCharacterEncoding = (string)$subtitles->hardSubsCharacterEncoding;
		$preferredLanguage = (string)$subtitles->preferredLanguage;
		$this->subtitlesEnabled = $subtitlesEnabled;
        $this->embeddedSubtitlesExtractionEnabled = $embeddedSubtitlesExtractionEnabled;
        $this->hardSubsEnabled = $hardSubsEnabled;
        $this->hardSubsForced = $hardSubsForced;
        $this->preferredLanguage = $preferredLanguage;
		$this->hardSubsCharacterEncoding=$hardSubsCharacterEncoding;
        
        return array($audioDownmixing, $threadsNumber, $transcodingFolderLocation, $bestVideoQuality, $transcodingEnabled, $subtitlesEnabled, $embeddedSubtitlesExtractionEnabled, $hardSubsEnabled, $hardSubsForced, $preferredLanguage,$hardSubsCharacterEncoding);
    }

    /**
     */
    public function getPresentation()
    {
        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/presentation');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get presentation";
            return false;
        }
        $categories = array();
        foreach ($xml->categories->browsingCategory as $entry) {
            $id = (string)$entry->id; // => A,I,V
            $title = (string)$entry->title; // => Audio,Image,Video
            $visibility = (string)$entry->visibility; // => DISPLAYED,CONTENT_DISPLAYED,DISABLED
            $subCategories = array();
            foreach ($entry->subCategories->browsingCategory as $item) {
                $subId = (string)$item->id; // => A_F
                $subTitle = (string)$item->title; // => Folders
                $subVisibility = (string)$item->visibility; // => DISPLAYED
                $subCategories[$subId] = array($subTitle, $subVisibility);
            }
            $categories[$id] = array($title, $visibility, $subCategories);
        }
        $presentationLanguage = (string)$xml->language;
        $showParentCategoryTitle = (string)$xml->showParentCategoryTitle;
        $numberOfFilesForDynamicCategories = (string)$xml->numberOfFilesForDynamicCategories;
        $this->presentationLanguage = $presentationLanguage;
        $this->showParentCategoryTitle = $showParentCategoryTitle;
        $this->numberOfFilesForDynamicCategories = $numberOfFilesForDynamicCategories;
        return $categories;
    }

    /**
     */
public function getPlugins()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/plugins');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get plugins";
            return false;
        }

$i = 0;

$onlinePlugin = array();
foreach ($xml->onlinePlugin as $entry) {
$name = (string)$entry->name; // Plugin name
$version = (string)$entry->version; // Plugin version
$onlinePlugin[$i] = array($name, $version);
$i = $i + 1;
}
               
        return $onlinePlugin;
    }

/**
*/
    public function putStatus($profiles, $bound_nic,$er,$defaultid)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("status"));
		
		$root->appendChild($xmlDoc->createElement("boundNICName", $bound_nic));
		$root->appendChild($xmlDoc->createElement("rendererEnabledByDefault", $er));
		$root->appendChild($xmlDoc->createElement("defaultAccessGroupId", $defaultid));
        // create sub element
        $Rends = $root->appendChild($xmlDoc->createElement("renderers"));

        foreach ($profiles as $renderer) {
            $Rend = $Rends->appendChild($xmlDoc->createElement("renderer"));
            $Rend->appendChild($xmlDoc->createElement("uuid", $renderer[0]));
            $Rend->appendChild($xmlDoc->createElement("ipAddress", $renderer[1]));
            $Rend->appendChild($xmlDoc->createElement("name", $renderer[2]));
            $Rend->appendChild($xmlDoc->createElement("profileId", $renderer[3]));
            $Rend->appendChild($xmlDoc->createElement("enabled", $renderer[4]));
            $Rend->appendChild($xmlDoc->createElement("accessGroupId", $renderer[5]));
        }

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/status');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putRemoteAccess($passwd, $quality, $mapping, $address)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("remoteAccess"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("remoteUserPassword", $passwd));
        $root->appendChild($xmlDoc->createElement("preferredRemoteDeliveryQuality", $quality));
		$root->appendChild($xmlDoc->createElement("portMappingEnabled", $mapping));
		$root->appendChild($xmlDoc->createElement("externalAddress", $address));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/remote-access');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }
	

    /**
     */
    public function putConsoleSettings($lang, $chkForUpd)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("consoleSettings"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("language", $lang));
        $root->appendChild($xmlDoc->createElement("checkForUpdates", $chkForUpd));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/console-settings');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putLicenseUpload($data)
    {
        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/license-upload');
        parent::setVerb('PUT');
        parent::setRequestBody($data);
        parent::setContentType('Content-Type: plain/text;');
        parent::execute();
        $x = simplexml_load_string(parent::getResponseBody());
        return $x->errorCode;
    }

    /**
     */
    public function postAction($action, $params = '')
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("action"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("name", $action));

        // any params?
        if ($params != '') {
            foreach ($params as $param) {
                $root->appendChild($xmlDoc->createElement("parameter", $param));
            }
        }

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        return $requestBody;
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/action');
        parent::setVerb('POST');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }
	
	
	

    /**
     */
    public function putDelivery($transcoding, $location, $cores, $audio, $quality, $subtitles, $subtitlesextraction, $hardsubsenabled, $hardsubsforced, $language,$encode)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("delivery"));

        //create the transcoding element
        $delivery = $root->appendChild($xmlDoc->createElement("transcoding"));

        // create sub element
        $delivery->appendChild($xmlDoc->createElement("audioDownmixing", $audio));
        $delivery->appendChild($xmlDoc->createElement("threadsNumber", $cores));
        $delivery->appendChild($xmlDoc->createElement("transcodingFolderLocation", $location));
        $delivery->appendChild($xmlDoc->createElement("transcodingEnabled", $transcoding));
		$delivery->appendChild($xmlDoc->createElement("bestVideoQuality", $quality));
		
		//create the transcoding element
        $delivery1 = $root->appendChild($xmlDoc->createElement("subtitles"));

        // create sub element
        $delivery1->appendChild($xmlDoc->createElement("subtitlesEnabled", $subtitles));
        $delivery1->appendChild($xmlDoc->createElement("embeddedSubtitlesExtractionEnabled", $subtitlesextraction));
        $delivery1->appendChild($xmlDoc->createElement("hardSubsEnabled", $hardsubsenabled));
        $delivery1->appendChild($xmlDoc->createElement("hardSubsForced", $hardsubsforced));
        $delivery1->appendChild($xmlDoc->createElement("preferredLanguage", $language));
		$delivery1->appendChild($xmlDoc->createElement("hardSubsCharacterEncoding", $encode));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/delivery');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putMetadata($audioLocalArtExtractorEnabled, $videoLocalArtExtractorEnabled, $videoOnlineArtExtractorEnabled,
    $videoGenerateLocalThumbnailEnabled, $imageGenerateLocalThumbnailEnabled, $metadataLanguage, $descriptiveMetadataExtractor, $retrieveOriginalTitle)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("metadata"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("audioLocalArtExtractorEnabled", $audioLocalArtExtractorEnabled));
        $root->appendChild($xmlDoc->createElement("videoLocalArtExtractorEnabled", $videoLocalArtExtractorEnabled));
        $root->appendChild($xmlDoc->createElement("videoOnlineArtExtractorEnabled", $videoOnlineArtExtractorEnabled));
        $root->appendChild($xmlDoc->createElement("videoGenerateLocalThumbnailEnabled", $videoGenerateLocalThumbnailEnabled));
        $root->appendChild($xmlDoc->createElement("imageGenerateLocalThumbnailEnabled", $imageGenerateLocalThumbnailEnabled));
        $root->appendChild($xmlDoc->createElement("metadataLanguage", $metadataLanguage));
        $root->appendChild($xmlDoc->createElement("retrieveOriginalTitle", $retrieveOriginalTitle));
        $root->appendChild($xmlDoc->createElement("descriptiveMetadataExtractor", $descriptiveMetadataExtractor));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/metadata');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putRepository($repo)
    {
        //create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("repository"));

        //create a tutorial element
        $sharedFolders = $root->appendChild($xmlDoc->createElement("sharedFolders"));

        /* FOLDERS */
        foreach ($repo[0] as $id=>$entry) {
            $Folder = $sharedFolders->appendChild($xmlDoc->createElement("sharedFolder"));
            if ($entry[4] != "new") {
                $Folder->appendChild($xmlDoc->createElement("id", $id));
            }
            $Folder->appendChild($xmlDoc->createElement("folderPath", $entry[0]));

            $supportedFileTypes = $Folder->appendChild($xmlDoc->createElement("supportedFileTypes"));
            foreach ($entry[1] as $type) {
                $supportedFileTypes->appendChild($xmlDoc->createElement("fileType", $type));
            }

            $Folder->appendChild($xmlDoc->createElement("descriptiveMetadataSupported", $entry[2]));
            $Folder->appendChild($xmlDoc->createElement("scanForUpdates", $entry[3]));

            if (is_array($entry[5])) {
                $accessGroupIds = $Folder->appendChild($xmlDoc->createElement("accessGroupIds"));
                foreach ($entry[5] as $grpId) {
                    $accessGroupIds->appendChild($xmlDoc->createElement("id", $grpId));
                }
            }
        }
        $root->appendChild($xmlDoc->createElement("searchHiddenFiles", $this->searchHiddenFiles));
        $root->appendChild($xmlDoc->createElement("searchForUpdates", $this->searchForUpdates));
        $root->appendChild($xmlDoc->createElement("automaticLibraryUpdate", $this->automaticLibraryUpdate));
        $root->appendChild($xmlDoc->createElement("automaticLibraryUpdateInterval", $this->automaticLibraryUpdateInterval));

        /* Online Repositories */
        $sharedFolders = $root->appendChild($xmlDoc->createElement("onlineRepositories"));
        if (isset($repo[1])) {
            foreach ($repo[1] as $id=>$entry) {
                $Folder = $sharedFolders->appendChild($xmlDoc->createElement("onlineRepository"));
                if ($entry[3] != "new") {
                    $Folder->appendChild($xmlDoc->createElement("id", $id));
                }
                $Folder->appendChild($xmlDoc->createElement("repositoryType", $entry[0]));
                $Folder->appendChild($xmlDoc->createElement("contentUrl", str_replace("&", "&amp;", $entry[1])));
                $Folder->appendChild($xmlDoc->createElement("fileType", $entry[2]));
                $Folder->appendChild($xmlDoc->createElement("thumbnailUrl", $entry[6]));
                $Folder->appendChild($xmlDoc->createElement("repositoryName", $entry[4]));
                $Folder->appendChild($xmlDoc->createElement("enabled", $entry[5]));

                if (is_array($entry[7])) {
                    $accessGroupIds = $Folder->appendChild($xmlDoc->createElement("accessGroupIds"));
                    foreach ($entry[7] as $grpId) {
                        $accessGroupIds->appendChild($xmlDoc->createElement("id", $grpId));
                    }
                }
            }
        }
        $root->appendChild($xmlDoc->createElement("maxNumberOfItemsForOnlineFeeds", $this->maxNumberOfItemsForOnlineFeeds));
        $root->appendChild($xmlDoc->createElement("onlineFeedExpiryInterval", $this->onlineFeedExpiryInterval));
        $root->appendChild($xmlDoc->createElement("onlineContentPreferredQuality", $this->onlineContentPreferredQuality));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/repository');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putPresentation($categories, $presentationLanguage, $showParentCategoryTitle, $numberOfFilesForDynamicCategories)
    {
        //create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("presentation"));

        //create a tutorial element
        $Categories = $root->appendChild($xmlDoc->createElement("categories"));

        /* CATEGORIES */
        foreach ($categories as $id=>$category) {
            $id = str_replace("'", "", $id);
            $Category = $Categories->appendChild($xmlDoc->createElement("browsingCategory"));
            $Category->appendChild($xmlDoc->createElement("id", $id));
            $Category->appendChild($xmlDoc->createElement("visibility", $category[1]));

            $subCategories = $Category->appendChild($xmlDoc->createElement("subCategories"));
            foreach ($category[2] as $subId=>$subcategory) {
                $subId = str_replace("'", "", $subId);
                $subCategory = $subCategories->appendChild($xmlDoc->createElement("browsingCategory"));
                $subCategory->appendChild($xmlDoc->createElement("id", $subId));
                $subCategory->appendChild($xmlDoc->createElement("visibility", $subcategory[1]));
            }
        }
        $root->appendChild($xmlDoc->createElement("language", $presentationLanguage));
        $root->appendChild($xmlDoc->createElement("showParentCategoryTitle", $showParentCategoryTitle));
        $root->appendChild($xmlDoc->createElement("numberOfFilesForDynamicCategories", $numberOfFilesForDynamicCategories));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/presentation');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }
}

/**
 */
function getPostVar($var, $def="")
{
    return isset($_POST[$var])?$_POST[$var]:$def;
}

/**
 */
function XMLToArrayFlat($xml, &$return, $path='', $root=false)
{
    $children = array();
    if ($xml instanceof SimpleXMLElement) {
        $children = $xml->children();
        if ($root) { // we're at root
            $path .= '/'.$xml->getName();
        }
    }
    if (count($children) == 0) {
        $return[$path] = (string)$xml;
        return;
    }
    $seen=array();
    foreach ($children as $child => $value) {
        $childname = ($child instanceof SimpleXMLElement)?$child->getName():$child;
        if (!isset($seen[$childname])) {
            $seen[$childname]=0;
        }
        $seen[$childname]++;
        XMLToArrayFlat($value, $return, $path.'/'.$child.'['.$seen[$childname].']');
    }
}

/**
 */
function tr($token, $def="")
{
    global $language, $translation;
    if (strlen($language)==2 && file_exists("i18n/messages_${language}.properties")) {
        // OK
    } else {
        $language = "en";
    }
    if (!is_array($translation) || count($translation)<1) {
        // Load
        $translation = array();
        $handle = @fopen("i18n/messages_${language}.properties", "r");
        if ($handle) {
            $append = false;
            while (($buffer = fgets($handle, 4096)) !== false) {
                $buffer = trim($buffer);
                if ($append) {
                    if (substr($buffer, strlen($buffer)-1, 1)!="\\") {
                        $append=false;
                    }
                    $translation[$key].= str_replace("\\", "\n", $buffer);
                    continue;
                }
                $pos = strpos($buffer, "=");
                if ($pos!==false) {
                    $key = trim(substr($buffer, 0, $pos));
                    $val = trim(substr($buffer, $pos+1));
                    if (substr($val, strlen($val)-1, 1)=="\\") {
                        $append = true;
                    }
                    $translation[$key] = str_replace("\\", "\n", $val);
                }
            }
            if (!feof($handle)) {
                //echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
    }
    return array_key_exists($token, $translation)?$translation[$token]:($def==""?$token:$def);
}

?>
