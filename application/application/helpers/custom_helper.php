<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('test_method'))
{
    function getproductvarients($id = '')
    {
        $CI =& get_instance();
		$query = $CI->db->get_where("product_varients",array('product_id'=>$id));
		$prodVarients = $query->result_array();
		return $prodVarients;
    }   
}
