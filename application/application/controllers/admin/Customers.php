<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends Admin_Controller {

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
        $this->lang->load('customers');

        // load the customers model
        $this->load->model('customers_model'); 

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/customers'));
        define('DEFAULT_LIMIT', $this->settings->per_page_limit);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "customer_id");
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
		
		/* Check customer permission */   
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
		/* Check customer permission */ 
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
		
		// if ($this->input->get('mobile_no'))
  //       {
  //           $filters['mobile_no'] = $this->input->get('mobile_no', TRUE);
  //       }

        if ($this->input->get('full_name'))
        {
            $filters['full_name'] = $this->input->get('full_name', TRUE);
        }

        // if ($this->input->get('last_name'))
        // {
        //     $filters['last_name'] = $this->input->get('last_name', TRUE);
        // }
		
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

                // if ($this->input->post('mobile_no'))
                // {
                //     $filter .= "&mobile_no=" . $this->input->post('mobile_no', TRUE);
                // }
				
			    if ($this->input->post('email_id'))
                {
                    $filter .= "&email_id=" . $this->input->post('email_id', TRUE);
                }

                if ($this->input->post('full_name'))
                {
                    $filter .= "&full_name=" . $this->input->post('full_name', TRUE);
                }

                // if ($this->input->post('last_name'))
                // {
                //     $filter .= "&last_name=" . $this->input->post('last_name', TRUE);
                // }
				
				if ($this->input->post('is_active'))
                {
                    $filter .= "&is_active=" . $this->input->post('is_active', TRUE);
                } 
				
                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $customers = $this->customers_model->get_all($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $customers['total'],
            'per_page'   => $limit
        ));

        // setup page header data
		$this
			->set_title( lang('customers title customer_list') );

        $data = $this->includes;
		
		/* Status Array */
		$statuslist = array(''=>'-Select-','1'=>'Active','inactive'=>'Inactive');
		$paymentlist = array(''=>'-Select-','1'=>'Paid','0'=>'Free');
		
        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'customers'      => $customers['results'],
            'total'      => $customers['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir,
			'statuslist' => $statuslist,
			'paymentlist' => $paymentlist
        );

        // load views
        $data['content'] = $this->load->view('admin/customers/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
		 
		 
    }
	
	
	/* 
	
	Date : 28-4-2017
	Develop By : Parthiv shah
	
	@parameters1 : emaiil id 
	@parameters2 : User Name
	
	Notes : Function check validation.
	
	*/	
	protected function validation($email_id=NULL,$id=NULL)
	{ 
		$this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
		 
		$this->form_validation->set_rules('full_name', lang('customers input full_name'), 'required|trim|min_length[2]|max_length[50]');
		
		// $this->form_validation->set_rules('last_name', lang('customers input last_name'), 'required|trim|min_length[2]|max_length[50]');
		 
		// $this->form_validation->set_rules('mobile_no', lang('customers input mobile_no'), 'required|trim|numeric|min_length[10]|max_length[10]');	

  //       $this->form_validation->set_rules('mobile_no', lang('customers input mobile_no'), 'required|trim|numeric|min_length[10]|max_length[10]');	
		
		$this->form_validation->set_rules('payment_status', lang('customers input payment_status'), 'required|trim|numeric');
		            
		if(isset($id) && !empty($id)){
			 
			 $this->form_validation->set_rules('password', lang('customers input password'), 'min_length[5]|matches[password_repeat]');
			 
			 $this->form_validation->set_rules('password_repeat', lang('customers input password_repeat'), 'matches[password]');
			 
		} else {
			
			$this->form_validation->set_rules('password', lang('customers input password'), 'required|trim|min_length[8]|max_length[16]');  
			 
			$this->form_validation->set_rules('password_repeat', lang('customers input password_repeat'), 'required|trim|matches[password]');
		}
		
	 
		if(!empty($email_id)) {	 
			 
			$this->form_validation->set_rules('email_id', lang('customers input email'), 'required|trim|valid_email|min_length[2]|max_length[100]|callback__check_email['.$email_id.']');	
			
		} else {	
		
		 $this->form_validation->set_rules('email_id', lang('customers input email'), 'required|trim|valid_email|min_length[2]|max_length[100]|callback__check_email[]');
		}	
		return $this->form_validation->run(); 
	} 	
	/* End Validation Check */
	
	

    /**
     * Add new customer
     */
    function add()
    {      
		/* Check customer permission */
		if($this->checkAuth(2,'u_add') == 0)
		    redirect(base_url()."admin/accessdenied"); 
		    
        if ($this->validation())
        {
            // save the new customer
            $saved = $this->customers_model->add_customer($this->input->post());

            if ($saved)
            {
                $this->session->set_flashdata('message', sprintf(lang('customers msg add_customer_success'), $this->input->post('full_name') ));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf(lang('customers error add_customer_failed'), $this->input->post('full_name') ));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this
		->add_js_theme( 'customers_i18n.js', TRUE )
		->add_css_theme( 'bootstrap-datepicker.css' )
		->add_js_theme( 'bootstrap-datepicker.js' )
		->set_title( lang('customers title customer_add') );

        $data = $this->includes;

		$paymentlist = array(''=>'-Select-','1'=>'Paid','0'=>'Free');
			
        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'customer'              => NULL,
            'password_required' => TRUE,
			'paymentlist'	=> $paymentlist 
        );
		 
        // load views
        $data['content'] = $this->load->view('admin/customers/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }


    /**
     * Edit existing customer
     *
     * @param  int $id
     */
    function edit($id = NULL)
    { 
		/* Check customer permission */
		if($this->checkAuth(2,'u_edit') == 0)
		    redirect(base_url()."admin/accessdenied"); 
		
		
        // make sure we have a numeric id
        if (is_null($id) OR ! is_numeric($id))
        {
            redirect($this->_redirect_url);
        }

        // get the data
        $customer = $this->customers_model->get_customer($id);

        // if empty results, return to list
        if ( ! $customer)
        {
            redirect($this->_redirect_url);
        }
		
        if ($this->validation($customer['email_id'],$id))
        {
            // save the changes
            $saved = $this->customers_model->edit_customer($this->input->post());

            if ($saved)
            {
                $this->session->set_flashdata('message', sprintf(lang('customers msg edit_customer_success'), $this->input->post('full_name') ));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf(lang('customers error edit_customer_failed'), $this->input->post('full_name') ));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

         
		// setup page header data
         $this
		->add_js_theme( 'customers_i18n.js', TRUE )
		->add_css_theme( 'bootstrap-datepicker.css' )
		->add_js_theme( 'bootstrap-datepicker.js' )
		->set_title( lang('customers title customer_edit') );
		$data = $this->includes;
		
		
		//$accessLevel = $this->customers_model->accessLevelDropDownList();
		$paymentlist = array(''=>'-Select-','1'=>'Paid','0'=>'Free');
		
        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'customer'              => $customer,
            'customer_id'           => $id,
            'password_required' => FALSE,
			'paymentlist'		=> $paymentlist  
        );	
        
        
        $data['content'] = $this->load->view('admin/customers/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }


    /**
     * Delete a customer
     *
     * @param  int $id
     */
    function delete($id = NULL)
    {
		/* Check customer permission */
		if($this->checkAuth(2,'u_delete') == 0)
		    redirect(base_url()."admin/accessdenied");
		
        // make sure we have a numeric id
        if ( ! is_null($id) OR ! is_numeric($id))
        {
            // get customer details
            $customer = $this->customers_model->get_customer($id);

            if ($customer)
            {
                // soft-delete the customer
                $delete = $this->customers_model->delete_customer($id);

                if ($delete)
                {
                    $this->session->set_flashdata('message', sprintf(lang('customers msg delete_customer'), $customer['full_name'] ));
                }
                else
                {
                    $this->session->set_flashdata('error', sprintf(lang('customers error delete_customer'), $customer['full_name'] ));
                }
            }
            else
            {
                $this->session->set_flashdata('error', lang('customers error customer_not_exist'));
            }
        }
        else
        {
            $this->session->set_flashdata('error', lang('customers error customer_id_required'));
        }

        // return to list and display message
        redirect($this->_redirect_url);
    }


    /**
     * Export list to CSV
     */
    function export()
    {
		/* Check customer permission */
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
		
		// if ($this->input->get('mobile_no'))
  //       {
  //           $filters['mobile_no'] = $this->input->get('mobile_no', TRUE);
  //       }

        if ($this->input->get('full_name'))
        {
            $filters['full_name'] = $this->input->get('full_name', TRUE);
        }

        // if ($this->input->get('last_name'))
        // {
        //     $filters['last_name'] = $this->input->get('last_name', TRUE);
        // }
		
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

        // get all customers
        $customers = $this->customers_model->get_all(0, 0, $filters, $sort, $dir);

        if ($customers['total'] > 0)
        {
            // manipulate the output array
            foreach ($customers['results'] as $key=>$customer)
            {
                unset($customers['results'][$key]['password']);
				unset($customers['results'][$key]['salt']);
                unset($customers['results'][$key]['deleted']);

                if ($customer['is_active'] == 0)
                {
                    $customers['results'][$key]['is_active'] = lang('admin input inactive');
                }
                else
                {
                    $customers['results'][$key]['is_active'] = lang('admin input active');
                }
            }

            // export the file
            array_to_csv($customers['results'], "customers");
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
     * Make sure customername is available
     *
     * @param  string $customername
     * @param  string|null $current
     * @return int|boolean
     */
    // function _check_customername($customername, $current)
    // {    
	   //  if (trim($customername) != trim($current) && $this->customers_model->customername_exists($customername))
    //     {
    //         $this->form_validation->set_message('_check_customername', sprintf(lang('customers error customername_exists'), $customername));
    //         return FALSE;
    //     }
    //     else
    //     {
    //         return $customername;
    //     }
    // }
	

    /**
     * Make sure email is available
     *
     * @param  string $email
     * @param  string|null $current
     * @return int|boolean
     */
    function _check_email($email, $current)
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
	
	

}
