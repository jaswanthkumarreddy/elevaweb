<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Feeds_model extends CI_Model {

    /**
     * @vars
     */
    private $_db;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_db = 'feeds';
    }
  
    /**
     * Get list of non-deleted feeds
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    function get_all($limit = 0, $offset = 0, $filters = array(), $sort = 'feed_name', $dir = 'ASC')
    {
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS *
            FROM {$this->_db}
            WHERE deleted = '0'
        ";

        if ( ! empty($filters))
        {
            foreach ($filters as $key=>$value)
            {
                $value = $this->db->escape('%' . $value . '%');
                $sql .= " AND {$key} LIKE {$value}";
            }
        }

        $sql .= " ORDER BY {$sort} {$dir}";

        if ($limit)
        {
            $sql .= " LIMIT {$offset}, {$limit}";
        }
	
         $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            $results['results'] = $query->result_array();
        }
        else
        {
            $results['results'] = NULL;
        }

        $sql = "SELECT FOUND_ROWS() AS total";
        $query = $this->db->query($sql);
        $results['total'] = $query->row()->total;

        return $results;
    }


    /**
     * Get specific feed
     *
     * @param  int $feed_id
     * @return array|boolean
     */
    function get_feed($feed_id = NULL)
    {
        if ($feed_id)
        {
            $sql = "SELECT *
                FROM {$this->_db}
                WHERE feed_id = " . $this->db->escape($feed_id) . "
                    AND deleted = '0'
            ";

            $query = $this->db->query($sql);

            if ($query->num_rows())
            {
                return $query->row_array();
            }
        }

        return FALSE;
    }
	
	function getFeedPathCategory($feed_id = NULL) {		
	 
	 $sql = "
            SELECT feed_path , feed_category
            FROM feeds_path_category
            WHERE feed_id = " . $this->db->escape($feed_id) . "
      	";		
	  $query = $this->db->query($sql); 
 	 return $query->result_array();
	 
	}


    /**
     * Add a new feed
     *
     * @param  array $data
     * @return mixed|boolean
     */
    function add_feed($data = array())
    {
        if ($data)
        { 
			$data = array(				
				'feed_name' => $data['feed_name'],
				'website_url' => $data['website_url'],   
				'rule1_type' => $data['rule1_type'],  
                'rule1_type_value' => $data['rule1_type_value'],
				'rule1_is_single' => isset($data['rule1_is_single']) ? $data['rule1_is_single'] : 0,  
                'rule1_is_inner' => isset($data['rule1_is_inner']) ? $data['rule1_is_inner'] : 0,				
				'rule2_type' => $data['rule2_type'],  
                'rule2_type_value' => $data['rule2_type_value'],
				'rule2_is_single' => isset($data['rule2_is_single']) ? $data['rule2_is_single'] : 0,  
                'rule2_is_inner' => isset($data['rule2_is_inner']) ? $data['rule2_is_inner'] : 0,				
				'is_strip_parts' => isset($data['is_strip_parts']) ? $data['is_strip_parts'] : 0,
				'strip1_type' => $data['strip1_type'],
				'strip1_value' => $data['strip1_value'],
				'strip2_type' => $data['strip2_type'],
				'strip2_value' => $data['strip2_value'],
				'feed_template' => $data['feed_template'],	 
				'ip_address' => $this->input->ip_address(),				
				'created_on' => date('Y-m-d H:i:s',now()), 
				'is_active' => $data['is_active']				
			); 
						
			$this->db->insert('feeds',$data);
			
			if ($feed_id = $this->db->insert_id()) {
				
				$cart_feeds = $this->session->userdata('cart_feeds'); 
				 foreach($cart_feeds as $fKey=>$fVal){
				 	if(!empty($cart_feeds[$fKey]['feed_path'])){
						$data1 = array(				
							'feed_id' => $feed_id,
							'feed_path' => $cart_feeds[$fKey]['feed_path'],      
							'feed_category' => $cart_feeds[$fKey]['feed_category']
						);  					
						$this->db->insert('feeds_path_category',$data1);					
					}
				 }			
                return $feed_id;
				
            }			
        }
        return FALSE;
    }
 
    /**
     * Edit an existing feed
     *
     * @param  array $data
     * @return boolean
     */
    function edit_feed($data = array())
    { 		
        if ($data)
        {
			
			$up_data = array(				
				'feed_name' => $data['feed_name'],
				'website_url' => $data['website_url'],   
				'rule1_type' => $data['rule1_type'],  
                'rule1_type_value' => $data['rule1_type_value'],
				'rule1_is_single' => isset($data['rule1_is_single']) ? $data['rule1_is_single'] : 0,  
                'rule1_is_inner' => isset($data['rule1_is_inner']) ? $data['rule1_is_inner'] : 0,				
				'rule2_type' => $data['rule2_type'],  
                'rule2_type_value' => $data['rule2_type_value'],
				'rule2_is_single' => isset($data['rule2_is_single']) ? $data['rule2_is_single'] : 0,  
                'rule2_is_inner' => isset($data['rule2_is_inner']) ? $data['rule2_is_inner'] : 0,				
				'is_strip_parts' => isset($data['is_strip_parts']) ? $data['is_strip_parts'] : 0,
				'strip1_type' => $data['strip1_type'],
				'strip1_value' => $data['strip1_value'],
				'strip2_type' => $data['strip2_type'],
				'strip2_value' => $data['strip2_value'],
				'feed_template' => $data['feed_template'],	 
				'ip_address' => $this->input->ip_address(),				
				'updated_on' => date('Y-m-d H:i:s',now()), 
				'is_active' => $data['is_active']	
			);
			
			$this->db->where("feed_id",$data['id']);
			$this->db->update('feeds', $up_data);
			if ($this->db->affected_rows())
            {
				/*Delete all rows in */
				$this->db->delete('feeds_path_category', array('feed_id' =>$data['id'])); 
				
				/*Add new data in Feeds_path_category table */				 
				$cart_feeds = $this->session->userdata('cart_feeds'); 
				 
				 foreach($cart_feeds as $fKey=>$fVal){ 
				 	if(!empty($cart_feeds[$fKey]['feed_path'])){
						$data1 = array(				
							'feed_id' => $data['id'],
							'feed_path' => $cart_feeds[$fKey]['feed_path'],      
							'feed_category' => $cart_feeds[$fKey]['feed_category']
						);  					
						$this->db->insert('feeds_path_category',$data1);	
					}				
				 }		
                return TRUE;
            } 
        } 
        return FALSE;
    }
 
    /**
     * Soft delete an existing feed
     *
     * @param  int $feed_id
     * @return boolean
     */
    function delete_feed($feed_id = NULL)
    {
        if ($feed_id)
        {
			$de_data = array (
				'is_active' => 0,
				'deleted' => 1,				
				'updated_on' => date('Y-m-d H:i:s',now())
			); 
			
			$this->db->where(array('feed_id >'=>1,'feed_id'=>$feed_id)); 
			
			$this->db->update('feeds', $de_data);
			
			//echo $this->db->last_query();
			//die;
			
			if ($this->db->affected_rows())
            {
                return TRUE;
            } 
			
            
        }

        return FALSE;
    } 
   

    /**
     * Check to see if an email_id already exists
     *
     * @param  string $email_id
     * @return boolean
     */
    function email_exists($email_id,$current=NULL)
    {
        $sql = "
            SELECT feed_id
            FROM {$this->_db}
            WHERE email_id = " . $this->db->escape($email_id) . " ";
			
		if(!empty($current))	
			$sql .= " AND  feed_id !=  " . $this->db->escape($current) . " ";
         
		$sql .= " LIMIT 1"; 
	 
        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            return TRUE;
        }

        return FALSE;
    }
	
	/*
		
		Get Dropdown feed list
		Date : 12-04-2017
		Developer Name : Parthiv Shah
	
	*/
	function feedsDropDownList()
	{
		$this->db->from('feeds'); 
		$this->db->order_by('feed_id');
		$result = $this->db->get();
		$return = array();
		$return[''] = '--Select--';
		
		if($result->num_rows() > 0) {		
		foreach($result->result_array() as $row) {
			$return[$row['feed_id']] = ucfirst($row['full_name']); //.' '.ucfirst($row['last_name'])
			}
		}
			return $return;
	}
	
	/*
		
		Get Dropdown Access Level list
		Date : 12-04-2017
		Developer Name : Parthiv Shah
	
	*/
	function accessLevelDropDownList()
	{
		$this->db->from('access_level'); 
		$this->db->order_by('access_level_id');
		$result = $this->db->get();
		$return = array();
		$return[''] = '--Select--';
		if($result->num_rows() > 0) { 
		foreach($result->result_array() as $row) {
			$return[$row['access_level_id']] = $row['title'];
			}
		}
			return $return;
	}
	
	/**
     * Check for valid login credentials
     *
     * @param  string $username
     * @param  string $password
     * @return array|boolean
     */
    function login($email_id = NULL, $password = NULL)
    {
	
        if ($email_id && $password)
        {
            $sql = "
                SELECT
                    full_name,                 
                    feed_id, 
                    email_id,
                    is_active,
					salt,
					password,
					changepassword 
                FROM feeds
                WHERE email_id = " . $this->db->escape($email_id) . "  LIMIT 1 ";
		 
		  
            $query = $this->db->query($sql);
			 
            if ($query->num_rows())
            {
                $results = $query->row_array();
				 
                $salted_password = hash('sha512', $password . $results['salt']);
				 
                if ($results['password'] == $salted_password)
                { 
                    unset($results['password']);
                    unset($results['salt']); 
                    return $results;
                }
            }
        }

        return FALSE;
    }
	
	/**
     * Check for valid login credentials
     *
     * @param  string $username
     * @param  string $password
     * @return array|boolean
     */
    function changePassword($data = array())
    {
	   if ($data['feedId'] && $data['new_password'])
        {
            $sql = "
                SELECT
                    full_name,                 
                    feed_id, 
                    email_id,
                    is_active,
					salt,
					password,
					changepassword 
                FROM feeds
                WHERE feed_id = " . $this->db->escape($data['feedId']) . "  LIMIT 1 ";
		 
		  
            $query = $this->db->query($sql);
			 
            if ($query->num_rows())
            {
                $results = $query->row_array();
                $salted_password = hash('sha512', $data['password'] . $results['salt']);
				 
                if ($results['password'] == $salted_password)
                { 
					// create new salt and stored password
					$salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
					$new_password = hash('sha512', $data['new_password'] . $salt);
					
					$sql1 = "
                    UPDATE {$this->_db} SET
                        password = " . $this->db->escape($new_password) . ",
                        salt = " . $this->db->escape($salt) . ",
                        changepassword = '0'
                    WHERE feed_id = " . $this->db->escape($data['feedId']) . "
                "; 
				 
					$this->db->query($sql1);
	
					if ($this->db->affected_rows()) {
						unset($results['password']);
	                    unset($results['salt']);
						return $results;
					} else {
						 return FALSE;
					}                 
                }
            }
        }

        return FALSE;
      
    }
	
	 /**
     * Reset password
     *
     * @param  array $data
     * @return mixed|boolean
     */
    function reset_password($data = array())
    {
        if ($data)
        {
            $sql = "
                SELECT
                    feed_id,
                    full_name,
					website
                FROM {$this->_db}
                WHERE email_id = " . $this->db->escape($data['email_id']) . "
                    AND is_active = '1'
                    AND deleted = '0'
                LIMIT 1
            ";
		 	
		 
            $query = $this->db->query($sql);

            if ($query->num_rows())
            {
                // get user info
                $user = $query->row_array();

                // create new random password
                $user_data['new_password'] = generate_random_password();
                $user_data['first_name']   = $user['full_name'];
				$user_data['website']   = $user['website'];

                // create new salt and stored password
                $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
                $password = hash('sha512', $user_data['new_password'] . $salt);

                $sql1 = "
                    UPDATE {$this->_db} SET
                        password = " . $this->db->escape($password) . ",
                        salt = " . $this->db->escape($salt) . " ,
						changepassword = '1'
                    WHERE feed_id = " . $this->db->escape($user['feed_id']) . "
                "; 
                $this->db->query($sql1);

                if ($this->db->affected_rows())
                {
                    return $user_data;
                }
            }
        }

        return FALSE;
    }

}
