<?php
/**
 * @author     Michał Boszke <boszkem@gmail.com>
 * @copyright  2016
 * @version    v 1.0
 */

namespace Modules\Autopost;

use Controller\Controller;
use Auth\Auth;
use Modules\Autopost\Model\AutopostModel;
use Facebook;

class Autopost extends Controller
{
    function __construct()
    {
        parent::__construct();
		Auth::handleLogin();
    }

    /**
     * Wczytywanie strony autopostu (autopost/index)
	*/
    function index()
    {
		//pobranie z bd danych FB i Twittera
        $pobieranie_model = new AutopostModel();
        $this -> model = $pobieranie_model;
        $this->view->daneFBiTT = $pobieranie_model->data_from_DB();
		
		//krótkie nazwy zmiennych FB
		$this->view->fb_app_id = $app_id = $this->view->daneFBiTT->fb_app_id;
		$this->view->fb_app_secret = $app_secret = $this->view->daneFBiTT->fb_app_secret;
		//do sprawdzania gdzie aktualnie wysyłane są posty na FB (który fan page)
		$this->view->fb_app_token = $this->view->daneFBiTT->fb_app_token;
		
		//zmienna z listą fan page'ow
		$page_list = $this->view->daneFBiTT->fb_page_list;
		$this->view->page_list = unserialize(base64_decode($page_list));
		
		//krótkie nazwy zmiennych Twitter
		$this->view->tt_consumer_key = $this->view->daneFBiTT->tt_consumer_key;
		$this->view->tt_consumer_secret = $tt_consumer_secret = $this->view->daneFBiTT->tt_consumer_secret;
		$this->view->tt_access_token = $tt_access_token = $this->view->daneFBiTT->tt_access_token;
		$this->view->tt_access_token_secret = $tt_access_token_secret = $this->view->daneFBiTT->tt_access_token_secret;
		
		//sprawdzenie czy dane FB istnieją i uzyskanie loginbu callback
		if (!empty($app_id) && !empty($app_secret))
		{
			$fb = new Facebook\Facebook([
						'app_id' => $app_id,
						'app_secret' => $app_secret,
						'default_graph_version' => 'v2.5',
						]);

			$helper = $fb->getRedirectLoginHelper();
			$permissions = ['email', 'user_likes', 'manage_pages', 'publish_actions', 'publish_pages']; // optional
			$loginUrl = $helper->getLoginUrl(URL.'autopost/login_callback', $permissions);
			
			//przekazanie loginCallback do widoku
			$this->view->loginUrl = $loginUrl;
			//flaga sprawdzajaca czy istnieją dane FB
			$this->view->daneIstnieja = true;
		}
		else
		{
			//echo '<p>Uzupełnij dane FB!</p>';
			$this->view->daneIstnieja = false;
			$_SESSION["feedback_negative"][] = 'Uzupełnij dane Facebooka!';
		}
		
		//wyświetlanie widoku strony
        $this->view->render('autopost/index');
		
    }
	     
	
	 /**
     * Zapisywanie danych FB
     */
	function changeSettingFB()
    {
        $autopost_model = new AutopostModel();
        //Zapis ustawień FB
        $autopost_model->changeSettingFB($_POST);
		
		if (!empty($_POST['radio_list_page']))
		{
			$access_token_to_db = new AutopostModel();
			//Zapis accesstokenu fan page do bazy
			$access_token_to_db->access_token_to_db($_POST);
		}

        header('location: '.URL.'autopost/index');
    }
	
	/**
     * Zapisywanie danych FanPage
     */
	function changeFanpage()
    {
		if (!empty($_POST['radio_list_page']))
		{
			$access_token_to_db = new AutopostModel();
			//Zapis accesstokenu fan page do bazy
			$access_token_to_db->access_token_to_db($_POST);
		}

        header('location: '.URL.'autopost/index');
    }
	
	 /**
     * Zapisywanie danych Twitter
     */
	function changeSettingTT()
    {
        $autopost_model = new AutopostModel();
        //Zapis ustawień TT
        $autopost_model->changeSettingTT($_POST);

        header('location: '.URL.'autopost/index');
    }
	
	function login_callback()
	{	
		$autopostmodel = new AutopostModel();
        $this -> model = $autopostmodel;
        $this->view->daneFBiTT = $autopostmodel->data_from_DB();
		
		$app_id = $this->view->daneFBiTT->fb_app_id;
		$app_secret = $this->view->daneFBiTT->fb_app_secret;
							
		$fb = new Facebook\Facebook([
			  'app_id' => $app_id,
			  'app_secret' => $app_secret,
			  'default_graph_version' => 'v2.5',
			  ]);

		$helper = $fb->getRedirectLoginHelper();	
					
		try 
		{
			$accessToken = $helper->getAccessToken();  
		} 
		catch(Facebook\Exceptions\FacebookResponseException $e) 
		{
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} 
		catch(Facebook\Exceptions\FacebookSDKException $e) 
		{
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
							
		if (! isset($accessToken)) 
		{
			if ($helper->getError()) 
			{
				header('HTTP/1.0 401 Unauthorized');
								echo "Error: " . $helper->getError() . "\n";
								echo "Error Code: " . $helper->getErrorCode() . "\n";
								echo "Error Reason: " . $helper->getErrorReason() . "\n";
								echo "Error Description: " . $helper->getErrorDescription() . "\n";
			} 
			else 
			{
				header('HTTP/1.0 400 Bad Request');
				echo 'Bad request';
			}
		exit;
		}
							
							
		//Wyświetlanie shortLived accessTokenu admina
		//echo '<h3>Access Token</h3>';
		//var_dump($accessToken->getValue());
							
		// The OAuth 2.0 client handler helps us manage access tokens
		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();

		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		//echo '<h3>Metadata</h3>';
		//var_dump($tokenMetadata);	

		// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId($app_id); // Replace {app-id} with your app id
							
		// If you know the user ID this access token belongs to, you can validate it here
		//$tokenMetadata->validateUserId('123');
							
		//NIE WIEM O CO CHODZI Z TĄ LINIĄ ALE SIĘ WYSYPUJE!!!!!
		//$tokenMetadata->validateExpiration();

		if (! $accessToken->isLongLived()) 
		{
			// Exchanges a short-lived access token for a long-lived one
			try 
			{
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			} 
			catch (Facebook\Exceptions\FacebookSDKException $e) 
			{
				echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
				exit;
			}

			//wyświetlanie longlived accesstokenu admina
			//echo '<h3>Long-lived Admin</h3>';
			//var_dump($accessToken->getValue());
		}
				
		//ustawienie zmiennej accestoken
		$app_token = (string) $accessToken;
							
			//
		   // TOKEN FAN PAGE
		  //

		//powiązane konta 'fanpage'
		$response = $fb->get('/me/accounts?access_token='.$app_token);

		//zamiana na tablicę
		$posts = $response->getGraphEdge();
		$json = json_decode($posts, true);
		$posts = array_chunk($json, 2);
	
		//wyciąganie danych z tablicy
		$licznik = 0;
		foreach ($posts[0] as $post) 
		{
			//dodanie do tablicy odpowiednich zmiennych
			$page_list[$licznik][0] = $post['name'];
			$page_list[$licznik][1] = $post['access_token'];
			$licznik++;
		}						
								
		//serializacja listy stron na string
		//$serialize_page_list = serialize($page_list);
		@$serialize_page_list = base64_encode(serialize($page_list));
		
		//zmienna za pomocą której pokazywany jest przycisk
		$this->view->daneIstnieja = true;
		//zmienna z listą fan page'ow
		$page_list = $this->view->daneFBiTT->fb_page_list;
		$this->view->page_list = unserialize(base64_decode($page_list));
		//do sprawdzania gdzie aktualnie wysyłane są posty na FB (który fan page)
		$this->view->fb_app_token = $this->view->daneFBiTT->fb_app_token;
		//krótkie nazwy zmiennych
		$this->view->fb_app_id = $app_id = $this->view->daneFBiTT->fb_app_id;
		$this->view->fb_app_secret = $app_secret = $this->view->daneFBiTT->fb_app_secret;
								
		$_SESSION['fb_app_token'] = $app_token;
		$_SESSION['fb_page_list'] = $serialize_page_list;
		
		//zapis tokenu do bazy
		$change = new AutopostModel();
		$change->changeAccesstoken();
		
		
		//wyświetlanie widoku strony
        $this->view->render('autopost/fanpage');
		
	}

	

	
}
