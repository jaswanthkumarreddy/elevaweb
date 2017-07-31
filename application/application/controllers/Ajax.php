<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * All  > PUBLIC <  AJAX functions should go in here
 *
 * CSRF protection has been disabled for this controller in the config file
 *
 * IMPORTANT: DO NOT DO ANY WRITEBACKS FROM HERE!!! For retrieving data only.
 */
class Ajax extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }


    /**
	 * Change session language - user selected
     */
	function set_session_language()
	{
        $language = $this->input->post('language');
        $this->session->language = $language;
        $results['success'] = TRUE;
        echo json_encode($results);
        die();
	}
 
	 
	/**
     * Make sure username is available     
     * @param  string $username
     * @param  string|null $current
     * @return int|boolean
    */
    function check_username()
    {
		$this->load->model('users_model');
		$username = $this->input->post("username");
		$current = $this->input->post("current");
		
        if (trim($username) != trim($current) && $this->users_model->username_exists($username,$current))
        { 
            echo "1";
        }
        else
        {
            echo "0";
        }
    }
	
	/**
     * Make sure email is available
     *
     * @param  string $email
     * @param  string|null $current
     * @return int|boolean
     */
    function check_email()
    {
		$this->load->model('users_model');
		$email = $this->input->post("email_id");
		$current = $this->input->post("current");
		
        if (trim($email) != trim($current) && $this->users_model->email_exists($email,$current))
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
    } 
	/*  End Check unique username and email  */
	
	
	/**
     * Make sure email is available
     *
     * @param  string $email
     * @param  string|null $current
     * @return int|boolean
     */
    function check_customeremail()
    {
		$this->load->model('customers_model');
		$email = $this->input->post("email_id");
		$current = $this->input->post("current");		
        if ($this->customers_model->email_exists($email,$current))
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
    }  
	 
	/*  End Check unique username and email  */
	
	
	/*
		Get list of menu with view, add, delete, edit checkbox
	*/
	function menu_list(){
		
		$this->load->model('menus_model');
		$meenuList = $this->menus_model->menuDropDownList();
		
		$level_id = $this->input->post("level_id");
		$permissionList = $this->menus_model->getPermissionData($level_id);
		
	
		
		// set content data
        $content_data = array( 
			'meenuList'			=> $meenuList,
			'permissionList'	=> $permissionList			
        );

        // load views
         echo $this->load->view('admin/menus/ajax_menu', $content_data, TRUE);
         
	}	
	
	/*
		End Get list of menu with view, add, delete, edit checkbox
	*/
		
		
   function feed_add_category() {   	
   	 
	 if($this->session->userdata('cart_feeds'))
		 $cart_feeds = $this->session->userdata('cart_feeds');
		 	 
	 $cart_feeds[] = array(
	 		'feed_path'=>$this->input->post("feed_path"),
			'feed_category'=>$this->input->post("feed_category")
		);
	 $this->session->set_userdata('cart_feeds', $cart_feeds);  
	 echo "true";
   }
   
   function feed_path_category_list_in_session(){
    	$cart_feeds = $this->session->userdata('cart_feeds'); 
		$tmp = '';
		$count=1;
		foreach($cart_feeds as $fKey=>$fVal){
			$tmp .= '<tr>
					 <td>'.$count++.'</td>
                     <td>'.$cart_feeds[$fKey]['feed_path'].'</td>
                     <td>'.$cart_feeds[$fKey]['feed_category'].'</td>
                     <td> <a class="btn btn-default" href="javascript://" onclick="deleteFeed('.$fKey.')"> Delete </a>
				</tr>';
		}
	 echo $tmp;
   }   
   
   function delete_feed_in_session(){
   	  $dId = $this->input->post("dId");
	  if($this->session->userdata('cart_feeds')){
		 $cart_feeds = $this->session->userdata('cart_feeds');
		 unset($cart_feeds[$dId]);
		 $this->session->set_userdata('cart_feeds', $cart_feeds); 
	  }  
   } 
		
}
