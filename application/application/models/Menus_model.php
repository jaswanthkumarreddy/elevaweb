<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menus_model extends CI_Model {

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
        $this->_db = 'menus';
    }
  
     
	
	/* Check menu id exist or not */

	function checkMenuIdExist($level_id){
		
		  $sql = "SELECT user_access_id,access_level_id
                FROM user_access
                WHERE access_level_id = " . $level_id . " 
            "; 
            
			$query = $this->db->query($sql);

            if ($query->num_rows())            
            {    
				$rws = $query->row_array();
				return $rws;
			}	
            else
			{ 
				return "0";
			}
			
	}	
	/* End Check menu id exist or not */
	
    /**
     * Add a new user
     *
     * @param  array $data
	 * @param  string ResumeFileName
     * @return mixed|boolean
     */
    function add_menu($data = array())
    {  
		 
		$this->db->trans_begin();	
        if ($data)
        {  
		   
		   $l = $this->checkMenuIdExist($data['level_id']);
		  
		   if($l == 0){ 
		   
           $sql = "
                INSERT INTO user_access (
                    access_level_id,					 
					created 
                ) VALUES ( 
                    " . $this->db->escape($data['level_id']) . ",
					'" . date('Y-m-d H:i:s') . "' 
			   )
            "; 
			
            $this->db->query($sql);
			$id = $this->db->insert_id();
			
		   } else {
			   
			   $this->delete_permission($l['user_access_id']);
			   $id = $l['user_access_id'];
			  
		   }
			  
			/* Add data in user_access_permission*/
			
			$sql_1 = "SELECT menu_id FROM {$this->_db}";
			$query_1 = $this->db->query($sql_1);
			$results = $query_1->result_array();
		 	 
		    foreach($results as $result){
				
				$menuId = $result['menu_id'];
				$view = isset($data['view-'.$menuId]) ? $data['view-'.$menuId] : '0';
				$add = isset($data['add-'.$menuId]) ? $data['add-'.$menuId] : '0';
				$edit = isset($data['edit-'.$menuId]) ? $data['edit-'.$menuId] : '0';
				$delete = isset($data['delete-'.$menuId]) ? $data['delete-'.$menuId] : '0'; 
				
				$sql_2 = "
                INSERT INTO user_access_permission (
                    user_access_id,					 
					menu_id,
					u_view,
					u_add,
					u_edit,
					u_delete
                ) VALUES ( 
                    " . $id . ",
					" . $menuId . ",
					" . $view . ",
					" . $add . ",
					" . $edit . ",
					" . $delete . "
			   )";
			    $this->db->query($sql_2);
			}
			
			/* End data in user_access_permission*/
			    
			if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					return FALSE;
			} else {
					$this->db->trans_commit();
					return $id;
			} 	
			
        }
        return FALSE;
    }

    /*  Delete Permission   */
	
	function delete_permission($id = NULL)
    {
        if ($id)
        {
            $sql = "
                DELETE 
                FROM  user_access_permission
                WHERE user_access_id = " .$id . " 
            ";

            $this->db->query($sql);

            if ($this->db->affected_rows())
            {
                return TRUE;
            }
        }

        return FALSE;
    }
 
	
	/* End Delete Permission  */
 
  
	/* Dropdonw list   */
	
	function levelDropDownList()
	{
		$this->db->from('access_level'); 
		$this->db->order_by('access_level_id');
		$result = $this->db->get();
		$return = array();
		$return[''] = '-Select-';
		if($result->num_rows() > 0) {
		
		foreach($result->result_array() as $row) {
			$return[$row['access_level_id']] = $row['title'];
			}
		}
			return $return;
	}
	
	function menuDropDownList()
	{
		$this->db->from('menus'); 
		$this->db->where('status',1);
		$this->db->order_by('title');
		$result = $this->db->get();
		$return = array(); 
		if($result->num_rows() > 0) {
		
		foreach($result->result_array() as $row) {
			$return[$row['menu_id']] = $row['title'];
			}
		}
			return $return;
	}
	
	/* Dropdonw list   */
	
	
	/* Get permission  */
	
	function getPermissionData($levelId){
		
		 $sql = "SELECT UAP.*
                FROM user_access as UA  RIGHT JOIN 
				user_access_permission as UAP ON
				UA.user_access_id = UAP.user_access_id  
                WHERE UA.access_level_id = " . $levelId . "
                    
            ";
			
			$query = $this->db->query($sql);

            if ($query->num_rows() > 0)
			{
				$results_t = $query->result_array();
				foreach($results_t as $result){ 
					$results[$result['menu_id']] = $result; 
				}
			}
			else
			{
				$results = NULL;
			} 
			 
			return $results;
		
	}
		
	/* End  Get permission*/
	
	
	/* Check user permission   */
	
	function accessPermission($userTypeId, $menuId, $actionType){
		 
		 $sql = "SELECT $actionType as permission
                FROM user_access as UA  RIGHT JOIN 
				user_access_permission as UAP ON
				UA.user_access_id = UAP.user_access_id  
                WHERE 
				UA.access_level_id = " . $userTypeId . " AND
				UAP.menu_id = " . $menuId . " 
            ";
		 
		//echo $sql;
		//die;
		$query = $this->db->query($sql);
		$rws =  $query->row_array();  
 
		return $rws['permission'];
	}
	
	/* End User permission   */
	
}
