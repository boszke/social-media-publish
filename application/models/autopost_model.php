<?php
/**
 * @author     Michał Boszke <boszkem@gmail.com>
 * @copyright  2016
 * @version    v 1.0
 */

namespace Modules\Autopost\Model;

use Model\Model;
use Session\Session;
use PDO;

class AutopostModel extends Model
{
    public function __construct()
    {
		parent::__construct();
    }
	
	//Pobieranie wszystkich danych z bazy
	public function data_from_DB()
	{
		$sql = "SELECT * 
				FROM autopost
    			WHERE id = 1";
        $query = $this->db->prepare($sql);
        $query->execute();
		
		return $query->fetch();
	}

   /**
     * Edycja zmiennych odnoszących się do ustawień Facebook
     * @param array $POST - tablica zmiennych z formularza
     */
    public function changeSettingFB($POST)
    {
        $fb_app_id = $POST['fb_app_id'];
        $fb_app_secret = $POST['fb_app_secret'];

        $sql = "UPDATE autopost SET
    			fb_app_id = :fb_app_id,
    			fb_app_secret = :fb_app_secret
    			WHERE id = 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':fb_app_id' => $fb_app_id,
            ':fb_app_secret' => $fb_app_secret
			));

        $count =  $query->rowCount();
        if ($count == 1) {
            $_SESSION["feedback_positive"][] = 'Application ID i Application Secret zostały zapisane!';
            return true;
        } /*else {
            $_SESSION["feedback_negative"][] = 'Application ID i Application Secret nie zostały zapisane lub dane są takie same!';
            return false;
        } */
    }
	
	/**
     * Edycja access tokenu FB
     * @param array $POST - tablica zmiennych z formularza
     */
    public function access_token_to_db($POST)
    {
        $access_token = $POST['radio_list_page'];

        $sql = "UPDATE autopost SET
    			fb_app_token = :access_token
    			WHERE id = 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':access_token' => $access_token
			));

        $count =  $query->rowCount();
		
        if ($count == 1) {
            $_SESSION["feedback_positive"][] .= ' Access Token został zapisany!';
            return true;
        } /*else {
            $_SESSION["feedback_negative"][] .= 'Access Token nie został zapisany!';
            return false;
        }*/
    }
	
	
	
	/**
     * Edycja zmiennych odnoszących się do ustawień Twittera
     * @param array $POST - tablica zmiennych z formularza
     */
    public function changeSettingTT($POST)
    {
        $tt_consumer_key = $POST['tt_consumer_key'];
        $tt_consumer_secret = $POST['tt_consumer_secret'];
		$tt_access_token = $POST['tt_access_token'];
		$tt_access_token_secret = $POST['tt_access_token_secret'];

        $sql = "UPDATE autopost SET
    			tt_consumer_key = :tt_consumer_key,
    			tt_consumer_secret = :tt_consumer_secret,
				tt_access_token = :tt_access_token,
				tt_access_token_secret = :tt_access_token_secret
    			WHERE id = 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':tt_consumer_key' => $tt_consumer_key,
            ':tt_consumer_secret' => $tt_consumer_secret,
			':tt_access_token' => $tt_access_token,
			':tt_access_token_secret' => $tt_access_token_secret
			));

        $count =  $query->rowCount();
        if ($count == 1) {
            $_SESSION["feedback_positive"][] = 'Dane Twittera zostały zapisane!';
            return true;
        } /*else {
            $_SESSION["feedback_negative"][] = 'Dane Twittera nie mogły zostać zapisane!';
            return false;
        }*/
    }
	
	public function changeAccesstoken()
	{
		$fb_app_token = $_SESSION['fb_app_token'];
		$fb_page_list = $_SESSION['fb_page_list'];
		
		$sql1 = "UPDATE autopost 
					SET fb_app_token='$fb_app_token'
					WHERE id = 1";
		$sql2 = "UPDATE autopost 
					SET fb_page_list='$fb_page_list'
					WHERE id = 1";
		$query1 = $this->db->prepare($sql1);
		$query2 = $this->db->prepare($sql2);
		$query1->execute();
		$query2->execute();
		
		$count1 =  $query1->rowCount();
        if ($count1 == 1) {
            $_SESSION["feedback_positive"][] = 'AccessToken FB odnowiony!';
            return true;
        } else {
            $_SESSION["feedback_negative"][] = 'AccessToken FB nie został odnowiony!';
            return false;
        }
	}
	
}
