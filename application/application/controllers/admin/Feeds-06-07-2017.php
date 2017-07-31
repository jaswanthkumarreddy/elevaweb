<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Feeds extends Admin_Controller {

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

        // load the feeds model
        $this->load->model('feeds_model');

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/feeds'));
        define('DEFAULT_LIMIT', $this->settings->per_page_limit);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "feed_id");
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

		/* Check feed permission */
		if($this->checkAuth(2,'u_view') == 0)
		    redirect(base_url()."admin/accessdenied");
    }



    /**
     * User list page
     */
    function index()
    {
		/*Remove session */
		$this->session->unset_userdata('cart_feeds');

		/* Check feed permission */
		if($this->checkAuth(2,'u_view') == 0)
		    redirect(base_url()."admin/accessdenied");

        // get parameters
        $limit  = $this->input->get('limit')  ? $this->input->get('limit', TRUE)  : DEFAULT_LIMIT;
        $offset = $this->input->get('offset') ? $this->input->get('offset', TRUE) : DEFAULT_OFFSET;
        $sort   = $this->input->get('sort')   ? $this->input->get('sort', TRUE)   : DEFAULT_SORT;
        $dir    = $this->input->get('dir')    ? $this->input->get('dir', TRUE)    : DEFAULT_DIR;

        // get filters
        $filters = array();

        // if ($this->input->get('email_id'))
        // {
        //     $filters['email_id'] = $this->input->get('email_id', TRUE);
        // }

		// if ($this->input->get('mobile_no'))
  //       {
  //           $filters['mobile_no'] = $this->input->get('mobile_no', TRUE);
  //       }

        if ($this->input->get('feed_name'))
        {
            $filters['feed_name'] = $this->input->get('feed_name', TRUE);
        }

        if ($this->input->get('website_url'))
        {
            $filters['website_url'] = $this->input->get('website_url', TRUE);
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

                // if ($this->input->post('mobile_no'))
                // {
                //     $filter .= "&mobile_no=" . $this->input->post('mobile_no', TRUE);
                // }

			    // if ($this->input->post('email_id'))
       //          {
       //              $filter .= "&email_id=" . $this->input->post('email_id', TRUE);
       //          }

                if ($this->input->post('feed_name'))
                {
                    $filter .= "&feed_name=" . $this->input->post('feed_name', TRUE);
                }

                if ($this->input->post('website_url'))
                {
                    $filter .= "&website_url=" . $this->input->post('website_url', TRUE);
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
        $feeds = $this->feeds_model->get_all($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $feeds['total'],
            'per_page'   => $limit
        ));

        // setup page header data
		$this
			->set_title('Feeds List');

        $data = $this->includes;

		/* Status Array */
		$statuslist = array(''=>'-Select-','1'=>'Active','inactive'=>'Inactive');
		$selectorlist1 = array('0'=>'ID','1'=>'Class','2'=>'Xpath');
        $selectorlist2 = array('0'=>'ID','1'=>'Class');

        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'feeds'      => $feeds['results'],
            'total'      => $feeds['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir,
		    'statuslist' => $statuslist,
		    'selectorlist1' => $selectorlist1,
            'selectorlist2' => $selectorlist2
        );

        // load views
        $data['content'] = $this->load->view('admin/feeds/list', $content_data, TRUE);
        $this->load->view($this->template, $data);

    }


	/*

	Date : 28-4-2017
	Develop By : Parthiv shah

	@parameters1 : emaiil id
	@parameters2 : User Name

	Notes : Function check validation.

	*/
	protected function validation($id=NULL)
	{
		$this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));

		$this->form_validation->set_rules('feed_name', 'Feed Name','required|trim|min_length[2]|max_length[50]');

		$this->form_validation->set_rules('website_url', 'Website Url', 'required|trim|min_length[2]|max_length[100]');

		// $this->form_validation->set_rules('mobile_no', lang('feeds input mobile_no'), 'required|trim|numeric|min_length[10]|max_length[10]');

  //       $this->form_validation->set_rules('mobile_no', lang('feeds input mobile_no'), 'required|trim|numeric|min_length[10]|max_length[10]');

		// $this->form_validation->set_rules('payment_status', lang('feeds input payment_status'), 'required|trim|numeric');

		// if(isset($id) && !empty($id)){

		// 	 $this->form_validation->set_rules('password', lang('feeds input password'), 'min_length[5]|matches[password_repeat]');

		// 	 $this->form_validation->set_rules('password_repeat', lang('feeds input password_repeat'), 'matches[password]');

		// } else {

		// 	$this->form_validation->set_rules('password', lang('feeds input password'), 'required|trim|min_length[8]|max_length[16]');

		// 	$this->form_validation->set_rules('password_repeat', lang('feeds input password_repeat'), 'required|trim|matches[password]');
		// }


		// if(!empty($email_id)) {

		// 	$this->form_validation->set_rules('email_id', lang('feeds input email'), 'required|trim|valid_email|min_length[2]|max_length[100]|callback__check_email['.$email_id.']');

		// } else {

		//  $this->form_validation->set_rules('email_id', lang('feeds input email'), 'required|trim|valid_email|min_length[2]|max_length[100]|callback__check_email[]');
		// }
		return $this->form_validation->run();
	}
	/* End Validation Check */



    /**
     * Add new feed
     */
    function add()
    {

		/* Check feed permission */
		if($this->checkAuth(2,'u_add') == 0)
		    redirect(base_url()."admin/accessdenied");

        if ($this->validation())
        {
            // save the new feed
            $saved = $this->feeds_model->add_feed($this->input->post());

            if ($saved)
            {
                $this->session->set_flashdata('message', sprintf('Feed Added Sucessfully !!', $this->input->post('feed_name') ));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf('Failed To Add Feed', $this->input->post('feed_name') ));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this
		->add_js_theme( 'feeds_i18n.js', TRUE )
		->add_css_theme( 'bootstrap-datepicker.css' )
		->add_js_theme( 'bootstrap-datepicker.js' )
		->set_title('Add Feed' );

        $data = $this->includes;

		$rule1_type = $rule2_type = array('0'=>'ID','1'=>'Class','2'=>'Xpath');
        $strip1_type = $strip2_type = array('0'=>'ID','1'=>'Class');

        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'feed'              => NULL,
			'rule1_type'	=> $rule1_type,
			'rule2_type'	=> $rule2_type,
            'strip1_type' => $strip1_type,
			'strip2_type' => $strip2_type
        );

        // load views
        $data['content'] = $this->load->view('admin/feeds/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }


    /**
     * Edit existing feed
     *
     * @param  int $id
     */
    function edit($id = NULL)
    {
		/* Check feed permission */
		if($this->checkAuth(2,'u_edit') == 0)
		    redirect(base_url()."admin/accessdenied");


        // make sure we have a numeric id
        if (is_null($id) OR ! is_numeric($id))
        {
            redirect($this->_redirect_url);
        }

        // get the data
        $feed = $this->feeds_model->get_feed($id);


        // if empty results, return to list
        if ( ! $feed)
        {
            redirect($this->_redirect_url);
        }

        if ($this->validation($id))
         {
            // save the changes
            $saved = $this->feeds_model->edit_feed($this->input->post());

            if ($saved)
            {
                $this->session->set_flashdata('message', sprintf(lang('feeds msg edit_feed_success'), $this->input->post('feed_name') ));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf(lang('feeds error edit_feed_failed'), $this->input->post('feed_name') ));
            }

            // return to list and display message
            redirect($this->_redirect_url);
        }

        $feedPathCategory = $this->feeds_model->getFeedPathCategory($id);
		$this->session->set_userdata('cart_feeds', $feedPathCategory);

		// setup page header data
         $this
		->add_js_theme( 'feeds_i18n.js', TRUE )
		->add_css_theme( 'bootstrap-datepicker.css' )
		->add_js_theme( 'bootstrap-datepicker.js' )
		->set_title('Edit Feed');
		$data = $this->includes;


		$rule1_type = $rule2_type = array('0'=>'ID','1'=>'Class','2'=>'Xpath');
        $strip1_type = $strip2_type = array('0'=>'ID','1'=>'Class');

        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'feed'              => $feed,
            'feed_id'           => $id,
            'rule1_type'	=> $rule1_type,
			'rule2_type'	=> $rule2_type,
            'strip1_type' => $strip1_type,
			'strip2_type' => $strip2_type
        );


        $data['content'] = $this->load->view('admin/feeds/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }


    /**
     * Delete a feed
     *
     * @param  int $id
     */
    function delete($id = NULL)
    {

		/* Check feed permission */
		if($this->checkAuth(2,'u_delete') == 0)
		    redirect(base_url()."admin/accessdenied");

        // make sure we have a numeric id
        if ( ! is_null($id) OR ! is_numeric($id))
        {
            // get feed details
            $feed = $this->feeds_model->get_feed($id);

            if ($feed)
            {
                // soft-delete the feed
                $delete = $this->feeds_model->delete_feed($id);

                if ($delete)
                {
                    $this->session->set_flashdata('message', sprintf(lang('feeds msg delete_feed'), $feed['feed_name'] ));
                }
                else
                {
                    $this->session->set_flashdata('error', sprintf(lang('feeds error delete_feed'), $feed['feed_name'] ));
                }
            }
            else
            {
                $this->session->set_flashdata('error', lang('feeds error feed_not_exist'));
            }
        }
        else
        {
            $this->session->set_flashdata('error', lang('feeds error feed_id_required'));
        }

        // return to list and display message
        redirect($this->_redirect_url);
    }


    /**
     * Export list to CSV
     */
    function export()
    {
		/* Check feed permission */
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

        if ($this->input->get('feed_name'))
        {
            $filters['feed_name'] = $this->input->get('feed_name', TRUE);
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

        // get all feeds
        $feeds = $this->feeds_model->get_all(0, 0, $filters, $sort, $dir);

        if ($feeds['total'] > 0)
        {
            // manipulate the output array
            foreach ($feeds['results'] as $key=>$feed)
            {
                unset($feeds['results'][$key]['password']);
				unset($feeds['results'][$key]['salt']);
                unset($feeds['results'][$key]['deleted']);

                if ($feed['is_active'] == 0)
                {
                    $feeds['results'][$key]['is_active'] = lang('admin input inactive');
                }
                else
                {
                    $feeds['results'][$key]['is_active'] = lang('admin input active');
                }
            }

            // export the file
            array_to_csv($feeds['results'], "feeds");
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
     * Make sure feedname is available
     *
     * @param  string $feedname
     * @param  string|null $current
     * @return int|boolean
     */
    // function _check_feedname($feedname, $current)
    // {
	   //  if (trim($feedname) != trim($current) && $this->feeds_model->feedname_exists($feedname))
    //     {
    //         $this->form_validation->set_message('_check_feedname', sprintf(lang('feeds error feedname_exists'), $feedname));
    //         return FALSE;
    //     }
    //     else
    //     {
    //         return $feedname;
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
        if (trim($email) != trim($current) && $this->feeds_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('feeds error email_exists'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }



}
