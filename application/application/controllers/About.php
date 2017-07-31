<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the language file
        $this->lang->load('about');
    }


    /**
	 * Default
     */
	function index()
	{
        // setup page header data
        $this->set_title(sprintf(lang('about title'), $this->settings->site_name));

        $data = $this->includes;

        // set content data
        $content_data = array(
            'welcome_message' => $this->settings->welcome_message[$this->session->language]
        );

        // load views
        $data['content'] = $this->load->view('about/index', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

}
