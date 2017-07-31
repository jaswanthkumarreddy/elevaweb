<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends Admin_Controller {

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
		$this->lang->load('menus');

		// load the menus model
		$this->load->model('menus_model');

		// set constants
		define('REFERRER', "referrer");
		define('THIS_URL', base_url('admin/menus/add'));
		define('DEFAULT_LIMIT', $this->settings->per_page_limit);
		define('DEFAULT_OFFSET', 0);
		define('DEFAULT_SORT', "menu_id");
		define('DEFAULT_DIR', "asc");
		
		
		$this->load->helper(array('form', 'url'));
		
		 
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
		
		 if($this->checkAuth(9,'u_view') == 0)  
		     redirect(base_url()."admin/accessdenied"); 
		
    } 
 /* Validation Check */
	
	protected function validation(){
		
		// validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
         
	   $this->form_validation->set_rules('level_id', lang('menus input name'), 'required|trim');
		 
		return $this->form_validation->run(); 
	} 
	
	/* End Validation Check */
	
    /**
     * Add new menu
     */
    function add()
    {
		
		/* Check user permission */
		 if($this->checkAuth(9,'u_add') == 0)  
		     redirect(base_url()."admin/accessdenied");
		 
		
        if ($this->validation())
        { 
			// save the new menu 
            $saved = $this->menus_model->add_menu($this->input->post());

            if ($saved)
            {
                $this->session->set_flashdata('message', sprintf(lang('menus msg add_client_success'), " Permission"));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf(lang('menus error add_client_failed'), " Permission"));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }
	
		
		 // setup page header data
		$this 
			->add_js_theme('menus_i18n.js',TRUE)			
			->set_title( lang('menus title') );
		
		$data = $this->includes;
        
		
		// load the categorys model 
		$levelList = $this->menus_model->levelDropDownList();
		 
       
        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'menu'              => NULL,
			'levelList'			=> $levelList 	
        );

        // load views
        $data['content'] = $this->load->view('admin/menus/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    } 
}
