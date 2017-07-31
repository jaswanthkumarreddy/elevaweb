<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

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
        $this->_db = 'users';
    }


    /**
     * Get list of non-deleted users
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    function get_all($limit = 0, $offset = 0, $filters = array(), $sort = 'last_name', $dir = 'ASC')
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
     * Get specific user
     *
     * @param  int $user_id
     * @return array|boolean
     */
    function get_user($user_id = NULL)
    {
        if ($user_id)
        {
            $sql = "
                SELECT *
                FROM {$this->_db}
                WHERE user_id = " . $this->db->escape($user_id) . "
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
     * Add a new user
     *
     * @param  array $data
     * @return mixed|boolean
     */
    function add_user($data = array())
    {
        if ($data)
        {
            // secure password
            $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
            $password = hash('sha512', $data['password'] . $salt);

            $registration_date = $data['registration_date'];
            $date = str_replace('/', '-', $registration_date);
            $registration_date = date('Y-m-d', strtotime($date));  

			$data = array(				
				'username' => $data['username'],
				'first_name' => $data['first_name'],				
				'last_name' => $data['last_name'],
				'email_id' => $data['email_id'],
				'phone_no_work' => $data['phone_no_work'],
				'phone_no' => $data['phone_no'],
				'mobile_no' => $data['mobile_no'],
				'password' => $password,
				'salt' => $salt,								
				'registration_date' => $registration_date,
				'ip_address' => $this->input->ip_address(),
				'note' => $data['note'],				
				'created_by' => $this->session->userdata['logged_in']['user_id'],
				'created_on' => date('Y-m-d H:i:s',now()), 
				'is_active' => $data['is_active'],
				'access_level_id' => $data['access_level_id'], 
				'is_admin' => 1
			); 
			
			$this->db->insert('users',$data);
			
			if ($user_id = $this->db->insert_id()) {
                return $user_id;
            }			
        }
        return FALSE;
    }


    /**
     * User creates their own profile
     *
     * @param  array $data
     * @return mixed|boolean
     */
   //  function create_profile($data = array())
   //  {
   //      if ($data)
   //      {
   //          // secure password and create validation code
   //          $salt            = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
   //          $password        = hash('sha512', $data['password'] . $salt);
   //          $validation_code = sha1(microtime(TRUE) . mt_rand(10000, 90000));
			 
			// $data = array(
			// 	'client_id' =>$data['client_id'],
			// 	'username' => $data['username'],
			// 	'first_name' => $data['first_name'],				
			// 	'last_name' => $data['last_name'],
			// 	'email_id' => $data['email'], 
			// 	'mobile_no' => $data['mobile_no'],
			// 	'password' => $password,
			// 	'salt' => $salt, 
			// 	'ip_address' => $this->input->ip_address(), 
			// 	'user_type_id' => 1,
			// 	'created_by' => 0,
			// 	'created_on' => date('Y-m-d H:i:s',now()), 
			// 	'is_active' => 0,
			// 	'access_level_id' => 2, 
			// 	'is_admin' => 1
			// ); 
			
			// $this->db->insert('users',$data);
			
			// if ($user_id = $this->db->insert_id()) {
   //              return $user_id;
   //          }	
			 
           
   //      }

   //      return FALSE;
   //  }


    /**
     * Edit an existing user
     *
     * @param  array $data
     * @return boolean
     */
    function edit_user($data = array())
    {
        if ($data)
        {
            $registration_date = $data['registration_date'];
            $date = str_replace('/', '-', $registration_date);
            $registration_date = date('Y-m-d', strtotime($date));            
			
			$up_data = array(				
				'username' => $data['username'],
				'first_name' => $data['first_name'],				
				'last_name' => $data['last_name'],
				'email_id' => $data['email_id'],
				'phone_no_work' => $data['phone_no_work'],
				'phone_no' => $data['phone_no'],
				'mobile_no' => $data['mobile_no'], 				
				'registration_date' => $registration_date,
				'ip_address' => $this->input->ip_address(),
				'note' => $data['note'],				
				'updated_by' => $this->session->userdata['logged_in']['user_id'],
				'updated_on' => date('Y-m-d H:i:s',now()), 
				'is_active' => $data['is_active'],
				'access_level_id' => $data['access_level_id'], 
				'is_admin' => 1,
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
			$this->db->where("user_id",$data['id']);
			$this->db->update('users', $up_data);
			if ($this->db->affected_rows())
            {
                return TRUE;
            } 
        } 

        return FALSE;
    }


    /**
     * User edits their own profile
     *
     * @param  array $data
     * @param  int $user_id
     * @return boolean
     */
    function edit_profile($data = array(), $user_id = NULL)
    {
        if ($data && $user_id)
        {
            $sql = "
                UPDATE {$this->_db}
                SET
                    username = " . $this->db->escape($data['username']) . ",
            ";

            if ($data['password'] != '')
            {
                // secure password
                $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
                $password = hash('sha512', $data['password'] . $salt);

                $sql .= "
                    password = " . $this->db->escape($password) . ",
                    salt = " . $this->db->escape($salt) . ",
                ";
            }

            $sql .= "
                    first_name = " . $this->db->escape($data['first_name']) . ",
                    last_name = " . $this->db->escape($data['last_name']) . ",
                    email_id = " . $this->db->escape($data['email_id']) . ",
                    language = " . $this->db->escape($data['language']) . ",
                    updated = '" . date('Y-m-d H:i:s') . "',
					access_level_id =  " . $this->db->escape($data['access_level_id']) . "
                WHERE user_id = " . $this->db->escape($user_id) . "
                    AND deleted = '0'
            ";

            $this->db->query($sql);

            if ($this->db->affected_rows())
            {
                return TRUE;
            }
        }

        return FALSE;
    }


    /**
     * Soft delete an existing user
     *
     * @param  int $user_id
     * @return boolean
     */
    function delete_user($user_id = NULL)
    {
        if ($user_id)
        {
			$de_data = array (
				'is_admin' =>0,
				'is_active' => 0,
				'deleted' => 1,
				'updated_by' => $this->session->userdata['logged_in']['user_id'],
				'updated_on' => date('Y-m-d H:i:s',now())
			); 
			
			$this->db->where(array('user_id >'=>1,'user_id'=>$user_id)); 
			
			$this->db->update('users', $de_data);
			
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
     * Check for valid login credentials
     *
     * @param  string $username
     * @param  string $password
     * @return array|boolean
     */
    function login($username = NULL, $password = NULL)
    {
        if ($username && $password)
        {
            $sql = "
                SELECT
                    user_id,
                    username,
                    password,
                    salt,
                    first_name,
                    last_name,
                    email_id,
                    is_active, 
					is_admin,
					access_level_id,
					user_type_id
                FROM {$this->_db}
                WHERE (username = " . $this->db->escape($username) . "
                        OR email_id = " . $this->db->escape($username) . ")
                    AND is_active = '1'
                    AND deleted = '0'
                LIMIT 1
            ";
		 
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
     * Handle user login attempts
     *
     * @return boolean
     */
    function login_attempts()
    {
        // delete older attempts
        $older_time = date('Y-m-d H:i:s', strtotime('-' . $this->config->item('login_max_time') . ' seconds'));

        $sql = "
            DELETE FROM login_attempts
            WHERE attempt < '{$older_time}'
        ";

        $query = $this->db->query($sql);

        // insert the new attempt
        $sql = "
            INSERT INTO login_attempts (
                ip,
                attempt
            ) VALUES (
                " . $this->db->escape($_SERVER['REMOTE_ADDR']) . ",
                '" . date("Y-m-d H:i:s") . "'
            )
        ";

        $query = $this->db->query($sql);

        // get count of attempts from this IP
        $sql = "
            SELECT
                COUNT(*) AS attempts
            FROM login_attempts
            WHERE ip = " . $this->db->escape($_SERVER['REMOTE_ADDR'])
        ;

        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            $results = $query->row_array();
            $login_attempts = $results['attempts'];
            if ($login_attempts > $this->config->item('login_max_attempts'))
            {
                // too many attempts
                return FALSE;
            }
        }

        return TRUE;
    }


    /**
     * Validate a user-created account
     *
     * @param  string $encrypted_email
     * @param  string $validation_code
     * @return boolean
     */
    function validate_account($encrypted_email = NULL, $validation_code = NULL)
    {
        if ($encrypted_email && $validation_code)
        {
            $sql = "
                SELECT user_id
                FROM {$this->_db}
                WHERE SHA1(email_id) = " . $this->db->escape($encrypted_email) . "
                    AND validation_code = " . $this->db->escape($validation_code) . "
                    AND is_active = '0'
                    AND deleted = '0'
                LIMIT 1
            ";

            $query = $this->db->query($sql);

            if ($query->num_rows())
            {
                $results = $query->row_array();

                $sql = "
                    UPDATE {$this->_db}
                    SET is_active = '1',
                        validation_code = NULL
                    WHERE user_id = '" . $results['user_id'] . "'
                ";

                $this->db->query($sql);

                if ($this->db->affected_rows())
                {
                    return TRUE;
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
                    user_id,
                    first_name
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
                $user_data['first_name']   = $user['first_name'];

                // create new salt and stored password
                $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
                $password = hash('sha512', $user_data['new_password'] . $salt);

                $sql = "
                    UPDATE {$this->_db} SET
                        password = " . $this->db->escape($password) . ",
                        salt = " . $this->db->escape($salt) . "
                    WHERE user_id = " . $this->db->escape($user['user_id']) . "
                ";

                $this->db->query($sql);

                if ($this->db->affected_rows())
                {
                    return $user_data;
                }
            }
        }

        return FALSE;
    }


    /**
     * Check to see if a username already exists
     *
     * @param  string $username
     * @return boolean
     */
    function username_exists($username,$current=NULL)
    {
        $sql = "
            SELECT user_id
            FROM {$this->_db}
            WHERE 
				username = " . $this->db->escape($username) . " "; 
		
		if(!empty($current))		
			$sql .= " AND user_id !=  " . $this->db->escape($current) . " ";
				
        $sql .= " LIMIT 1 ";
          
        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            return TRUE;
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
            SELECT user_id
            FROM {$this->_db}
            WHERE email_id = " . $this->db->escape($email_id) . " ";
			
		if(!empty($current))	
			$sql .= " AND  user_id !=  " . $this->db->escape($current) . " ";
         
		$sql .= " LIMIT 1"; 
	 
        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            return TRUE;
        }

        return FALSE;
    }
	
	/*
		
		Get Dropdown user list
		Date : 12-04-2017
		Developer Name : Parthiv Shah
	
	*/
	function usersDropDownList()
	{
		$this->db->from('users'); 
		$this->db->order_by('user_id');
		$result = $this->db->get();
		$return = array();
		$return[''] = '--Select--';
		
		if($result->num_rows() > 0) {		
		foreach($result->result_array() as $row) {
			$return[$row['user_id']] = ucfirst($row['first_name']).' '.ucfirst($row['last_name']);
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

}
