<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accessdenied extends Admin_Controller {

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
		    

		// set constants
		define('REFERRER', "referrer");
		define('THIS_URL', base_url('admin/candidates'));
		define('DEFAULT_LIMIT', $this->settings->per_page_limit);
		define('DEFAULT_OFFSET', 0);
	 
		 
		// use the url in session (if available) to return to the previous filter/sorted/paginated list
		if ($this->session->userdata(REFERRER))
		{
			$this->_redirect_url = $this->session->userdata(REFERRER);
		}
		else
		{
			$this->_redirect_url = THIS_URL;
		}   
    }  
	
	
    /**
     * Client list page
     */
    function index()
    { 
        // setup page header data
		$this->set_title( 'Access Denied' );
		$data = $this->includes; 
        // load views
        $data['content'] = $this->load->view('admin/access-denied', '', TRUE);
        $this->load->view($this->template, $data);
    }
     
}
