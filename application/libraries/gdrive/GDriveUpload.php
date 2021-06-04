<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

session_start();
include 'vendor/autoload.php';

class GDriveUpload
{
    var $ci;
    function __construct()
    {
        $this->ci = &get_instance();
        // upload google drive
        $this->client = new Google_Client();
        $this->client->setAuthConfig(dirname(__FILE__).'/vivere-upload-credential.json');
        $this->client->setScopes(Google_Service_Drive::DRIVE);
        $this->service_drive = new Google_Service_Drive($this->client);

        // google sheet
        $this->client2 = new Google_Client();
        $this->client2->setAccessType('offline');
        $this->client2->setAuthConfig(dirname(__FILE__).'/vivere-upload-credential.json');
        $this->client2->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $this->service_sheet = new Google_Service_Sheets($this->client2);

		// development
		$this->folder_id = '1qUJNdNWk-vI8Yk9TriF80UmNAUX7qT3Q'; // folder development
        $this->folder_template_id = '1AK0NYMLSaP3VRUjEW3fH2S5BfaGh5g2N';  // folder template
        $this->template_non_1600 = '1FfPjhPwgr62mVfyWsToBsxsVHbdSlNeiRSqpOG1OyvM';
        $this->template_1600 = '1dEUJfukm72CUMKjTzxfuFy34EnP46bhUegxfKVu_HCk';

		// production
		// $this->folder_id = '1pFSD2ifG-kJJIECsqMdm-GVCq8thQL9M';
    }

	function uploadToServer($args, $folder_id)
	{
		return $this->createFile($args, $folder_id);
	}

	public function createFile($args, $parent_id = null){
		$name = $args['name'];
		$content = $args['content'];
		$mimeType = $args['mimeType'];
		$description = $args['description'];
        $folder_id = $parent_id ? $parent_id : ($this->folder_id) ? $this->folder_id : 'root';
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' 		  => $name,
			'description' => $description,
            'parents' 	  => array($folder_id)
        ]);

        $file = $this->service_drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $file->id;
    }

	public function findFolderByName($folder_name)
    {
        $data = array();
        try {
            $result = $this->service_drive->files->listFiles(array(
                "q" => array(
					"'" . $this->folder_id ."' in parents",
                ),
                'fields' => 'files(id, name)',
            ));

            foreach ($result->getFiles() as $folder) {
                if($folder->getName()==$folder_name)
                {
                    $data['folder_id']=$folder->getId();
                    $data['folder_name']=$folder->getName();
                    break;
                }
            }
        } catch (Exception $e) {
            return $data;
        }

        return $data;
    }

	public function createFolder($folder_name){
		$exists_folder = $this->findFolderByName($folder_name);
		if(count($exists_folder)==0)
		{
			$folder_meta = new Google_Service_Drive_DriveFile(array(
				'name' 	   => $folder_name,
				'mimeType' => 'application/vnd.google-apps.folder',
				'parents'  => array($this->folder_id)
			));
			$folder = $this->service_drive->files->create($folder_meta, array(
				'fields' => 'id'));
			$folder_id = $folder->id;
		} else {
			$folder_id = $exists_folder['folder_id'];
		}
        
        return $folder_id;
    }

    public function createGoogleSheet($file_name)
    {
        $spreadsheet = new Google_Service_Sheets_Spreadsheet([
            'properties'  => [
                'title'   => $file_name,
            ]
        ]);
        $spreadsheet = $this->service_sheet->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId'
        ]);

        return $spreadsheet->spreadsheetId;
    }

    public function appendRow($plant, $values)
    {
        if($plant!='1600') 
            $spreadsheet_id = $this->template_non_1600;
        else $spreadsheet_id = $this->template_1600;
        $range = 'Sheet1';
        $val = [$values];

        $body = new Google_Service_Sheets_ValueRange([
            'values' => $val
        ]);
        
        $params = [
            'valueInputOption' => 'RAW'
        ];
        $insert = [
            "insertDataOption" => "INSERT_ROWS"
        ];
        $result = $this->service_sheet->spreadsheets_values->append(
            $spreadsheet_id,
            $range,
            $body,
            $params,
            $insert
        );
        return $result;
    }

    public function duplicateFile($plant, $new_file_name)
    {
        if($plant!='1600') 
            $spreadsheet_id = $this->template_non_1600;
        else $spreadsheet_id = $this->template_1600;

        $file = new Google_Service_Drive_DriveFile([
            'name' => $new_file_name,
        ]);

        $cloned = $this->service_drive->files->copy($spreadsheet_id, $file);
        $fileId = $cloned->id;
        
        $params = array(
            'addParents'    => $this->folder_id,
            'removeParents' => $this->folder_template_id,
            'fields'        => 'id, parents',
        );
        $empty_meta_file = new Google_Service_Drive_DriveFile();
        $updated = $this->service_drive->files->update($fileId, $empty_meta_file, $params);
        return $updated;
    }

    public function clearValues($plant)
    {
        if($plant!='1600') 
            $spreadsheet_id = $this->template_non_1600;
        else $spreadsheet_id = $this->template_1600;

        $range = 'Sheet1!A3:BQ1000';
        $requestBody = new Google_Service_Sheets_ClearValuesRequest();
        return $this->service_sheet->spreadsheets_values->clear($spreadsheet_id, $range, $requestBody);
    }

    function setToken($code)
    {
        $client = $this->getClient($code);
        $redirect_uri = base_url('/report/index');
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }

    function uploadToClient($filesToSend, $folder_name, $file_name) {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);
        
        $folder_exists = $this->findFolderByNameInClient($folder_name);

        if (count($folder_exists)==0) {
            $folderId = $this->createFolderInClient($folder_name);
        } else {
            $folderId = $folder_exists['folder_id']; 
        }

        $fileMetadata = new Google_Service_Drive_DriveFile();
        $fileMetadata->setName($file_name);
        $fileMetadata->setParents(array($folderId));
        $fileMetadata->setMimeType('application/vnd.ms-excel');
        
        $content = file_get_contents($filesToSend);
        $file = $service->files->create($fileMetadata, array(
                'data'       => $content,
                'uploadType' => 'multipart',
                'fields'     => 'id'));
    }

    function createFolderInClient($folder_name)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $fileMetadata = new Google_Service_Drive_DriveFile();
        $fileMetadata->setName($folder_name);
        $fileMetadata->setMimeType("application/vnd.google-apps.folder");
        $file = $service->files->create($fileMetadata, array(
            'fields' => 'id'
        ));
        return $file->getId();
    }

    function findFolderByNameInClient($folder_name)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $data = array();
        try {
            $result = $service->files->listFiles(array(
                "q" => array(
                    "mimeType='application/vnd.google-apps.folder'", 
                ),
                'spaces' => 'drive',
                'fields' => 'files(id, name)',
            ));
            
            foreach ($result->getFiles() as $folder) {
                if($folder->getName()==$folder_name)
                {
                    $data['folder_id']=$folder->getId();
                    $data['folder_name']=$folder->getName();
                    break;
                }
            }
        } catch (Exception $e) {}
        
        return $data;
    }

    function getClient($code=null)
    {
        $client = new Google_Client();
        $client->setScopes(Google_Service_Drive::DRIVE);
        $client->setAuthConfig(dirname(__FILE__).'/oauth-credential.json');
        $client->setAccessType('offline');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $token_access = $this->ci->session->userdata('access_token');
        if($token_access)
        {
            $accessToken = json_decode(json_encode($token_access), true);
            $client->setAccessToken($accessToken);
        }
        // $tokenPath = dirname(__FILE__).'/token.json';
        // if (file_exists($tokenPath)) {
        //     $accessToken = json_decode(file_get_contents($tokenPath), true);
        //     $client->setAccessToken($accessToken);
        // }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                header("Location:".filter_var($authUrl, FILTER_SANITIZE_URL));
            }

            if($code)
            {
                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($code);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }

                // Save the token to a file.
                if(!$this->ci->session->userdata('access_token'))
                {
                    $this->ci->session->set_userdata('access_token', json_encode($client->getAccessToken()));
                }
                // if (!file_exists(dirname($tokenPath))) {
                //     mkdir(dirname($tokenPath), 0700, true);
                // }
                // file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }
            
        }
        return $client;
    }
}
