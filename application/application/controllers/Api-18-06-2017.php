<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Api extends API_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Default
     */
    public function index()
    {
        $this->lang->load('core');
        $results['error'] = lang('core error no_results');
        display_json($results);
        exit;
    }
	
	public function _check_email($email, $current)
    { 
        if (trim($email) != trim($current) && $this->customers_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('customers error email_exists'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }
	
	/**
     * Registration 
     */
	public function registration(){
		
		//get login status
		$results = array();
		
		// load the language files
        $this->lang->load('customers');

        // load the customers model
        $this->load->model('customers_model'); 
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			 $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
		 
			$this->form_validation->set_rules('full_name', lang('customers input full_name'), 'required|trim|min_length[2]|max_length[50]'); 
			
			 $this->form_validation->set_rules('email_id', lang('customers input email'), 'required|trim|valid_email|min_length[2]|max_length[100]|callback__check_email[]');
			  
			$this->form_validation->set_rules('password', lang('customers input password'), 'required|trim|min_length[8]|max_length[16]');  
			  
			
			if ($this->form_validation->run())
			{ 
				// save the new customer
				$saved = $this->customers_model->add_customer($this->input->post());
					
				if ($saved) {
					$results['success'] = true;
					$results['message'] = 'Record saved successfully.!';
				} else {
					$results['success'] = false;
					$results['message'] = 'Please enter proper value in Name and Password.!';
				}					 
			}
			else {
				$results['error'] = false;
				$results['message'] = validation_errors();
			}
		
		} else {
		 	$results['error'] = false;
			$results['message'] = 'Please select POST method.!';
		} 
	   display_json($results);
       exit;
	}
	
	
	/**
     * Validate login credentials
     */
   public function login()  { 
		//get login status
		$results = array();
			
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			// load the users model and admin language file
			$this->load->model('customers_model');
			$this->lang->load('customers'); 
			  	
			// set form validation rules
			$this->form_validation->set_rules('email_id', lang('customers input email'), 'required|trim|max_length[256]');
			$this->form_validation->set_rules('password', lang('customers input password'), 'required|trim|max_length[72]|callback__check_login');
			
			if ($this->form_validation->run() == TRUE) {
				$login = $this->customers_model->login($this->input->post('email_id'), $this->input->post('password'));
				$results['success'] = true;
				$results['data'] = $login;				
				$results['message'] = 'User login successful.!';
			} else {
				$results['error'] = false;
				$results['message'] = 'Username and Password not match.!';
			}
		} else {
			$results['error'] = false;
			$results['message'] = 'Please select POST method.!';
		}
        display_json($results);
        exit;
    }
	
	/**
     * Verify the login credentials
     *
     * @param  string $password
     * @return boolean
     */
    function _check_login($password) { 
        $login = $this->customers_model->login($this->input->post('email_id', TRUE), $password);
		if ($login) {
             return TRUE;
         }
        return FALSE;
    }
	
	/**
     * Verify the login credentials
     *
     * @param  string $password
     * @return boolean
     */
    function _check_changepass() { 
        $login = $this->customers_model->changePassword($this->input->post()); 
		if ($login) {             
             return TRUE;
         }
        return FALSE;
    }
	
    /**
     * Users API - DO NOT LEAVE THIS ACTIVE IN A PRODUCTION ENVIRONMENT !!! - for demo purposes only
     */
    public function users() {
        // load the users model and admin language file
        $this->load->model('users_model');
        $this->lang->load('admin');

        // get user data
        $users = $this->users_model->get_all();
        $results['data'] = NULL;

        if ($users)
        {
            // build usable array
            foreach($users['results'] as $user)
            {
                $results['data'][$user['user_id']] = array(
                    'username'   => $user['username'], // . " " . $user['last_name'],
                    'email'  => $user['email_id'],
                    'status' => ($user['is_admin']) ? lang('admin input active') : lang('admin input inactive')
                );
            }
            $results['total'] = $users['total'];
        }
        else
            $results['error'] = lang('core error no_results');

        // display results using the JSON formatter helper
        display_json($results);
        exit;
    }
	
	 /**
	 * Forgot password
     */
	public function forgot()
	{
	
		 // load the language files
        $this->lang->load('customers');
		$this->lang->load('users');
			
        // load the customers model
        $this->load->model('customers_model'); 
		
		//set arrary
		 $results['data'] = NULL;
		 
        // validators                
		$this->form_validation->set_rules('email_id', lang('customers input email'), 'required|trim|max_length[256]');
        if ($this->form_validation->run() == TRUE)
        {
		 	
            // save the changes
            $resultsData = $this->customers_model->reset_password($this->input->post());
			 
            if ($resultsData)
            {
			    $this->load->library('email'); 
				
                // build email
                //$reset_url  = base_url('login');
                //$email_msg  = lang('core email start');
                //$email_msg .= sprintf(lang('users msg email_password_reset'), $this->settings->site_name, $resultsData['new_password'], $reset_url, $reset_url);
               // $email_msg .= lang('core email end');
			   
			    $email_msg = '<p>Your password at Elevaweb has been reset. Click the link below to log in with your new password:</p>
<p>'.$resultsData['new_password'].'</p><p><a href="'.$resultsData['website'].'" target="_blank">'.$resultsData['website'].'</a> Once logged in, be sure to change your password to something you can remember.</p>';
 
            	$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'ssl://md-2.webhostbox.net';
				$config['smtp_port'] = 465;
				$config['smtp_user'] = 'info@nitcowholesale.com';
				$config['smtp_pass'] = 'I==Ppr#i%Mrr';
				$config['mailtype'] = 'html';
				$config['charset'] = 'iso-8859-1';
                //$config['mailpath'] = '/usr/sbin/sendmail -f' . $this->settings->site_email;
				
                $this->email->initialize($config);
				
                $this->email->clear();
                $this->email->from($this->settings->site_email, $this->settings->site_name);
                $this->email->reply_to($this->settings->site_email, $this->settings->site_name);
                $this->email->to($this->input->post('email_id', TRUE));				 
                $this->email->subject(sprintf(lang('users msg email_password_reset_title'), $resultsData['first_name']));
                $this->email->message($email_msg); 
				if($this->email->send()){ 
					$results['success'] = true;
					$results['message'] = 'Your password has been reset,Please check your email for your new temporary password.!'; 	
					
				} else {
					$results['error'] = false;
					$results['message'] = $this->email->print_debugger();
				}  
            }
            else
            {
                $results['error'] = false;
				$results['message'] = 'Email id is not registered.!';
            } 
			
        }
		else 
		{
			$results['error'] = false;
			$results['message'] = 'Please select POST method.!';
		}
   		
		display_json($results);
        exit;
    }
	
	
	/**
     * Change password
     */
   public function changepassword()  { 
		//get login status
		$results = array();
			
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			// load the users model and admin language file
			$this->load->model('customers_model');
			$this->lang->load('customers'); 
			  	
			// set form validation rules
			$this->form_validation->set_rules('customerId','Please enter customer id', 'required|trim');
			$this->form_validation->set_rules('new_password','Please enter new password', 'required|trim|max_length[72]');
			$this->form_validation->set_rules('password', lang('customers input password'), 'required|trim|max_length[72]|callback__check_changepass');
			
			if ($this->form_validation->run() == TRUE) {
				$results['success'] = true;
				$results['message'] = 'Password changed successfully!';
			} else {
				$results['error'] = false;
				$results['message'] = 'Old password not match.!';
			}
		} else {
			$results['error'] = false;
			$results['message'] =  'Old password not match.!';
		}
        display_json($results);
        exit;
    } 
	
	public function getFeedList(){
	
		//get login status
		$results = '';
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			// load the feeds model
			$this->load->model('feeds_model');
			$feeds = $this->feeds_model->get_all(); 
			
			foreach($feeds['results'] as $kFeed=>$kFeed){
			 
				$feedsPathCategorys = $this->feeds_model->getFeedPathCategory($feeds['results'][$kFeed]['feed_id']);
				
				$results['data'][]  = array( 
					'feed_id' => $feeds['results'][$kFeed]['feed_id'],
					'feed_name' => $feeds['results'][$kFeed]['feed_name'],
					'website_url' => $feeds['results'][$kFeed]['website_url'],
					'rule1_type' => $feeds['results'][$kFeed]['rule1_type'],
					'rule1_type_value' => $feeds['results'][$kFeed]['rule1_type_value'],
					'rule1_is_single' => $feeds['results'][$kFeed]['rule1_is_single'],
					'rule1_is_inner' => $feeds['results'][$kFeed]['rule1_is_inner'],
					'rule2_type' => $feeds['results'][$kFeed]['rule2_type'],
					'rule2_type_value' => $feeds['results'][$kFeed]['rule2_type_value'],
					'rule2_is_single' => $feeds['results'][$kFeed]['rule2_is_single'],
					'rule2_is_inner' => $feeds['results'][$kFeed]['rule2_is_inner'],					
					'is_strip_parts' => $feeds['results'][$kFeed]['is_strip_parts'],
					'strip1_type' => $feeds['results'][$kFeed]['strip1_type'],
					'strip1_value' => $feeds['results'][$kFeed]['strip1_value'],
					'strip2_type' => $feeds['results'][$kFeed]['strip2_type'],
					'strip2_value' => $feeds['results'][$kFeed]['strip2_value'],
					'feed_template' => $feeds['results'][$kFeed]['feed_template'],
					'feeds_path_category' => $feedsPathCategorys 
					
				);
			}  
		}
	    display_json($results);
        exit;
	}
	
	
}

