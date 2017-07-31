<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Customers_model extends CI_Model {

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
        $this->_db = 'customers';
    }
  
    /**
     * Get list of non-deleted customers
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    function get_all($limit = 0, $offset = 0, $filters = array(), $sort = 'full_name', $dir = 'ASC')
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
     * Get specific customer
     *
     * @param  int $customer_id
     * @return array|boolean
     */
    function get_customer($customer_id = NULL)
    {
        if ($customer_id)
        {
            $sql = "
                SELECT *
                FROM {$this->_db}
                WHERE customer_id = " . $this->db->escape($customer_id) . "
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


    /**
     * Add a new customer
     *
     * @param  array $data
     * @return mixed|boolean
     */
    function add_customer($data = array())
    {
        if ($data)
        {
            // secure password
            $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
            $password = hash('sha512', $data['password'] . $salt);

			$data = array(				
				'full_name' => $data['full_name'],
				//'last_name' => $data['last_name'],				 
				'email_id' => $data['email_id'],
			//	'mobile_no' => $data['mobile_no'],  
			//	'birthdate' => $data['birthdate'],
				'website' => $data['website'],      
				'password' => $password,
				'salt' => $salt,												
				'ip_address' => $this->input->ip_address(),				
				'created_on' => date('Y-m-d H:i:s',now()), 
				'is_active' => '1', //$data['is_active'],
				'payment_status' => '0' //$data['payment_status']				
			); 
						
			$this->db->insert('customers',$data);
			
			if ($customer_id = $this->db->insert_id()) {
                return $customer_id;
            }			
        }
        return FALSE;
    }
 
    /**
     * Edit an existing customer
     *
     * @param  array $data
     * @return boolean
     */
    function edit_customer($data = array())
    {
        if ($data)
        {
			
			$up_data = array(				
				'full_name' => $data['full_name'],
				//'last_name' => $data['last_name'],								
				'email_id' => $data['email_id'],
				'mobile_no' => $data['mobile_no'],  
			//	'birthdate' => $data['birthdate'],
				'website' => $data['website'],
				'ip_address' => $this->input->ip_address(),								 
				'updated_on' => date('Y-m-d H:i:s',now()), 
				'is_active' => $data['is_active'],
				'payment_status' => $data['payment_status']
			);
			
			$pass = array();
			if ( isset($data['password']) &&  $data['password'] != '')
            {
				// secure password
                $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
                $password = hash('sha512', $data['password'] . $salt);
				
				$pass = array(
					'password' => $password,
					'salt' => $salt
				);
				$up_data =  array_merge($up_data,$pass);
			}  
			$this->db->where("customer_id",$data['id']);
			$this->db->update('customers', $up_data);
			if ($this->db->affected_rows())
            {
                return TRUE;
            } 
        } 

        return FALSE;
    }
 
    /**
     * Soft delete an existing customer
     *
     * @param  int $customer_id
     * @return boolean
     */
    function delete_customer($customer_id = NULL)
    {
        if ($customer_id)
        {
			$de_data = array (
				'is_active' => 0,
				'deleted' => 1,
				'updated_by' => $this->session->customerdata['logged_in']['customer_id'],
				'updated_on' => date('Y-m-d H:i:s',now())
			); 
			
			$this->db->where(array('customer_id >'=>1,'customer_id'=>$customer_id)); 
			
			$this->db->update('customers', $de_data);
			
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
            SELECT customer_id
            FROM {$this->_db}
            WHERE email_id = " . $this->db->escape($email_id) . " ";
			
		if(!empty($current))	
			$sql .= " AND  customer_id !=  " . $this->db->escape($current) . " ";
         
		$sql .= " LIMIT 1"; 
	 
        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            return TRUE;
        }

        return FALSE;
    }
	
	/*
		
		Get Dropdown customer list
		Date : 12-04-2017
		Developer Name : Parthiv Shah
	
	*/
	function customersDropDownList()
	{
		$this->db->from('customers'); 
		$this->db->order_by('customer_id');
		$result = $this->db->get();
		$return = array();
		$return[''] = '--Select--';
		
		if($result->num_rows() > 0) {		
		foreach($result->result_array() as $row) {
			$return[$row['customer_id']] = ucfirst($row['full_name']); //.' '.ucfirst($row['last_name'])
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
                    customer_id, 
                    email_id,
                    is_active,
					salt,
					password,
					changepassword 
                FROM customers
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
	   if ($data['customerId'] && $data['new_password'])
        {
            $sql = "
                SELECT
                    full_name,                 
                    customer_id, 
                    email_id,
                    is_active,
					salt,
					password,
					changepassword 
                FROM customers
                WHERE customer_id = " . $this->db->escape($data['customerId']) . "  LIMIT 1 ";
		 
		  
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
                    WHERE customer_id = " . $this->db->escape($data['customerId']) . "
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
                    customer_id,
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
                    WHERE customer_id = " . $this->db->escape($user['customer_id']) . "
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
