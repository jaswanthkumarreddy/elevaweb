<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller {

    /**
     * @var string
     */
    private $_redirect_url;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the language files
        $this->lang->load('users');

        // load the users model
        $this->load->model('users_model');		 

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/users'));
        define('DEFAULT_LIMIT', $this->settings->per_page_limit);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "user_id");
        define('DEFAULT_DIR', "desc");

        // use the url in session (if available) to return to the previous filter/sorted/paginated list
        if ($this->session->userdata(REFERRER))
        {
            $this->_redirect_url = $this->session->userdata(REFERRER);
        }
        else
        {
            $this->_redirect_url = THIS_URL;
        } 
		
		/* Check user permission */   
		if($this->checkAuth(2,'u_view') == 0)
		    redirect(base_url()."admin/accessdenied");  
    }

 
    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
     * User list page
     */
    function index()
    {    
		/* Check user permission */ 
		if($this->checkAuth(2,'u_view') == 0)
		    redirect(base_url()."admin/accessdenied");
		
        // get parameters
        $limit  = $this->input->get('limit')  ? $this->input->get('limit', TRUE)  : DEFAULT_LIMIT;
        $offset = $this->input->get('offset') ? $this->input->get('offset', TRUE) : DEFAULT_OFFSET;
        $sort   = $this->input->get('sort')   ? $this->input->get('sort', TRUE)   : DEFAULT_SORT;
        $dir    = $this->input->get('dir')    ? $this->input->get('dir', TRUE)    : DEFAULT_DIR;

        // get filters
        $filters = array();

        if ($this->input->get('email_id'))
        {
            $filters['email_id'] = $this->input->get('email_id', TRUE);
        }
		
		if ($this->input->get('mobile_no'))
        {
            $filters['mobile_no'] = $this->input->get('mobile_no', TRUE);
        }

        if ($this->input->get('first_name'))
        {
            $filters['first_name'] = $this->input->get('first_name', TRUE);
        }

        if ($this->input->get('last_name'))
        {
            $filters['last_name'] = $this->input->get('last_name', TRUE);
        }
		
		if ($this->input->get('is_active'))
        {
			$statusId = '';
			if($this->input->get('is_active', TRUE) == "1"){
				$statusId = 1;
			} else if($this->input->get('is_active', TRUE) == "inactive") {
				$statusId = 0;
			}
            $filters['is_active'] = $statusId;
        }
		

        // build filter string
        $filter = "";
        foreach ($filters as $key => $value)
        {
            $filter .= "&{$key}={$value}";
        }

        // save the current url to session for returning
        $this->session->set_userdata(REFERRER, THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");

        // are filters being submitted?
        if ($this->input->post())
        {
            if ($this->input->post('clear'))
            {
                // reset button clicked
                redirect(THIS_URL);
            }
            else
            {
                // apply the filter(s)
                $filter = "";

                if ($this->input->post('mobile_no'))
                {
                    $filter .= "&mobile_no=" . $this->input->post('mobile_no', TRUE);
                }
				
			    if ($this->input->post('email_id'))
                {
                    $filter .= "&email_id=" . $this->input->post('email_id', TRUE);
                }

                if ($this->input->post('first_name'))
                {
                    $filter .= "&first_name=" . $this->input->post('first_name', TRUE);
                }

                if ($this->input->post('last_name'))
                {
                    $filter .= "&last_name=" . $this->input->post('last_name', TRUE);
                }
				
				if ($this->input->post('is_active'))
                {
                    $filter .= "&is_active=" . $this->input->post('is_active', TRUE);
                } 
				
                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $users = $this->users_model->get_all($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $users['total'],
            'per_page'   => $limit
        ));

        // setup page header data
		$this
			->set_title( lang('users title user_list') );

        $data = $this->includes;
		
		/* Status Array */
		$statuslist = array(''=>'-Select-','1'=>'Active','inactive'=>'Inactive');
		
        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'users'      => $users['results'],
            'total'      => $users['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir,
			'statuslist' => $statuslist
        );

        // load views
        $data['content'] = $this->load->view('admin/users/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
		 
		 
    }
	
	
	/* 
	
	Date : 28-4-2017
	Develop By : Parthiv shah
	
	@parameters1 : emaiil id 
	@parameters2 : User Name
	
	Notes : Function check validation.
	
	*/	
	protected function validation($email_id=NULL,$username=NULL,$id=NULL)
	{ 
		$this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
		 
		$this->form_validation->set_rules('first_name', lang('users input first_name'), 'required|trim|min_length[2]|max_length[50]');
		
		$this->form_validation->set_rules('last_name', lang('users input last_name'), 'required|trim|min_length[2]|max_length[50]');
		 
		$this->form_validation->set_rules('mobile_no', lang('users input mobile_no'), 'required|trim|numeric|min_length[10]|max_length[10]');
		
		$this->form_validation->set_rules('phone_no', lang('users input phone no'), 'trim|numeric|min_length[10]|max_length[10]');
		
		$this->form_validation->set_rules('phone_no_work', lang('users input phone no work'), 'trim|numeric|min_length[10]|max_length[10]');
		
		$this->form_validation->set_rules('access_level_id', lang('users input access_level'), 'required|trim|numeric');
		   
        $this->form_validation->set_rules('is_active', lang('users input status'), 'required|numeric'); 
		
		if(isset($id) && !empty($id)){
			 
			 $this->form_validation->set_rules('password', lang('users input password'), 'min_length[5]|matches[password_repeat]');
			 
			 $this->form_validation->set_rules('password_repeat', lang('users input password_repeat'), 'matches[password]');
			 
		} else {
			
			$this->form_validation->set_rules('password', lang('users input password'), 'required|trim|min_length[8]|max_length[16]');  
			 
			$this->form_validation->set_rules('password_repeat', lang('users input password_repeat'), 'required|trim|matches[password]');
		}
		
		if(!empty($username)) {			
			$this->form_validation->set_rules('username', lang('users input username'), 'required|trim|min_length[2]|max_length[20]|callback__check_username[' . $username . ' ]');			
		} else {			
		 $this->form_validation->set_rules('username', lang('users input username'), 'required|trim|min_length[2]|max_length[20]');
		} 
		 
		if(!empty($email_id)) {	 
			 
			$this->form_validation->set_rules('email_id', lang('users input email'), 'trim|valid_email|min_length[2]|max_length[100]|callback__check_email['.$email_id.']');	
			
		} else {	
		
		 $this->form_validation->set_rules('email_id', lang('users input email'), 'trim|valid_email|min_length[2]|max_length[100]|callback__check_email[]');
		}

	
		
		
		return $this->form_validation->run(); 
	} 	
	/* End Validation Check */
	
	

    /**
     * Add new user
     */
    function add()
    {      
		/* Check user permission */
		if($this->checkAuth(2,'u_add') == 0)
		    redirect(base_url()."admin/accessdenied"); 
		    
        if ($this->validation())
        {
            // save the new user
            $saved = $this->users_model->add_user($this->input->post());

            if ($saved)
            {
                $this->session->set_flashdata('message', sprintf(lang('users msg add_user_success'), $this->input->post('first_name') . " " . $this->input->post('last_name')));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf(lang('users error add_user_failed'), $this->input->post('first_name') . " " . $this->input->post('last_name')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this
		->add_js_theme( 'users_i18n.js', TRUE )
		->add_css_theme( 'bootstrap-datepicker.css' )
		->add_js_theme( 'bootstrap-datepicker.js' )
		->set_title( lang('users title user_add') );

        $data = $this->includes;

		
		$accessLevel = $this->users_model->accessLevelDropDownList();		
			
        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => NULL,
            'password_required' => TRUE,
			'accessLevel'		=> $accessLevel			
        );
		 
        // load views
        $data['content'] = $this->load->view('admin/users/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }


    /**
     * Edit existing user
     *
     * @param  int $id
     */
    function edit($id = NULL)
    { 
		/* Check user permission */
		if($this->checkAuth(2,'u_edit') == 0)
		    redirect(base_url()."admin/accessdenied"); 
		
		
        // make sure we have a numeric id
        if (is_null($id) OR ! is_numeric($id))
        {
            redirect($this->_redirect_url);
        }

        // get the data
        $user = $this->users_model->get_user($id);

        // if empty results, return to list
        if ( ! $user)
        {
            redirect($this->_redirect_url);
        }
		
        if ($this->validation($user['email_id'],$user['username'],$id))
        {
            // save the changes
            $saved = $this->users_model->edit_user($this->input->post());

            if ($saved)
            {
                $this->session->set_flashdata('message', sprintf(lang('users msg edit_user_success'), $this->input->post('first_name') . " " . $this->input->post('last_name')));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf(lang('users error edit_user_failed'), $this->input->post('first_name') . " " . $this->input->post('last_name')));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

         
		// setup page header data
         $this
		->add_js_theme( 'users_i18n.js', TRUE )
		->add_css_theme( 'bootstrap-datepicker.css' )
		->add_js_theme( 'bootstrap-datepicker.js' )
		->set_title( lang('users title user_edit') );
		$data = $this->includes;
		
		
		$accessLevel = $this->users_model->accessLevelDropDownList();
		
        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => $user,
            'user_id'           => $id,
            'password_required' => FALSE,
			'accessLevel'		=> $accessLevel
			
        );
  //       echo "<xmp>";
		// print_r($content_data);
  //       die;
        // load views
        $data['content'] = $this->load->view('admin/users/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }


    /**
     * Delete a user
     *
     * @param  int $id
     */
    function delete($id = NULL)
    {
		/* Check user permission */
		if($this->checkAuth(2,'u_delete') == 0)
		    redirect(base_url()."admin/accessdenied");
		
        // make sure we have a numeric id
        if ( ! is_null($id) OR ! is_numeric($id))
        {
            // get user details
            $user = $this->users_model->get_user($id);

            if ($user)
            {
                // soft-delete the user
                $delete = $this->users_model->delete_user($id);

                if ($delete)
                {
                    $this->session->set_flashdata('message', sprintf(lang('users msg delete_user'), $user['first_name'] . " " . $user['last_name']));
                }
                else
                {
                    $this->session->set_flashdata('error', sprintf(lang('users error delete_user'), $user['first_name'] . " " . $user['last_name']));
                }
            }
            else
            {
                $this->session->set_flashdata('error', lang('users error user_not_exist'));
            }
        }
        else
        {
            $this->session->set_flashdata('error', lang('users error user_id_required'));
        }

        // return to list and display message
        redirect($this->_redirect_url);
    }


    /**
     * Export list to CSV
     */
    function export()
    {
		/* Check user permission */
		if($this->checkAuth(2,'u_view') == 0)
		    redirect(base_url()."admin/accessdenied");  
		
        // get parameters
        $sort = $this->input->get('sort') ? $this->input->get('sort', TRUE) : DEFAULT_SORT;
        $dir  = $this->input->get('dir')  ? $this->input->get('dir', TRUE)  : DEFAULT_DIR;

        // get filters
        $filters = array();

        if ($this->input->get('email_id'))
        {
            $filters['email_id'] = $this->input->get('email_id', TRUE);
        }
		
		if ($this->input->get('mobile_no'))
        {
            $filters['mobile_no'] = $this->input->get('mobile_no', TRUE);
        }

        if ($this->input->get('first_name'))
        {
            $filters['first_name'] = $this->input->get('first_name', TRUE);
        }

        if ($this->input->get('last_name'))
        {
            $filters['last_name'] = $this->input->get('last_name', TRUE);
        }
		
		if ($this->input->get('is_active'))
        {
			$statusId = '';
			if($this->input->get('is_active', TRUE) == "1"){
				$statusId = 1;
			} else if($this->input->get('is_active', TRUE) == "inactive") {
				$statusId = 0;
			}
            $filters['is_active'] = $statusId;
        }

        // get all users
        $users = $this->users_model->get_all(0, 0, $filters, $sort, $dir);

        if ($users['total'] > 0)
        {
            // manipulate the output array
            foreach ($users['results'] as $key=>$user)
            {
                unset($users['results'][$key]['password']);
				unset($users['results'][$key]['salt']);
                unset($users['results'][$key]['deleted']);

                if ($user['is_active'] == 0)
                {
                    $users['results'][$key]['is_active'] = lang('admin input inactive');
                }
                else
                {
                    $users['results'][$key]['is_active'] = lang('admin input active');
                }
            }

            // export the file
            array_to_csv($users['results'], "users");
        }
        else
        {
            // nothing to export
            $this->session->set_flashdata('error', lang('core error no_results'));
            redirect($this->_redirect_url);
        }

        exit;
    }


    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/
 
 /**
     * Make sure username is available
     *
     * @param  string $username
     * @param  string|null $current
     * @return int|boolean
     */
    function _check_username($username, $current)
    {    
	    if (trim($username) != trim($current) && $this->users_model->username_exists($username))
        {
            $this->form_validation->set_message('_check_username', sprintf(lang('users error username_exists'), $username));
            return FALSE;
        }
        else
        {
            return $username;
        }
    }
	

    /**
     * Make sure email is available
     *
     * @param  string $email
     * @param  string|null $current
     * @return int|boolean
     */
    function _check_email($email, $current)
    { 
        if (trim($email) != trim($current) && $this->users_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('users error email_exists'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }
	
	

}
