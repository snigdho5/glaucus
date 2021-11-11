<?php

class Rochak_projection_report_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //display the projection logs
    function get_projection_logs_web()
    {
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            `payment_projection_history`.`dateOfCollection` AS collectionDate, 
            `payment_projection_history`.`projectionAmount`, 
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        $this->db->order_by('`payment_projection_history`.`projectionId`', "DESC");
        
        $query = $this->db->get();
        
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }        
    }    
    /************************Projection Report Model*************************/
    //Report section for Projection
    //function to get the records of current week
    function get_projection_report_current_week($data)
    {
        $where  =   "`payment_projection_history`.`created_at` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            date_format(`payment_projection_history`.`dateOfCollection`, "%d-%b-%Y") AS collectionDate,
            `payment_projection_history`.`projectionAmount`, 
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        if($data['salesUserId']){
            $this->db->where('`payment_projection_history`.`salesUserId`',$data['salesUserId']);
        }
        if($data['distributorid']){
            $this->db->where('`payment_projection_history`.`distributorid`',$data['distributorid']);
        }
        $this->db->where($where);
        $this->db->order_by('`payment_projection_history`.`projectionId`', "DESC");
        $query  =   $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }        
    }
    //function to get the records of current month
    function get_projection_report_current_month($data)
    {
        $where  =   "`payment_projection_history`.`created_at` between DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE()";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            date_format(`payment_projection_history`.`dateOfCollection`, "%d-%b-%Y") AS collectionDate,
            `payment_projection_history`.`projectionAmount`, 
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        if($data['salesUserId']){
            $this->db->where('`payment_projection_history`.`salesUserId`',$data['salesUserId']);
        }
        if($data['distributorid']){
            $this->db->where('`payment_projection_history`.`distributorid`',$data['distributorid']);
        }
        $this->db->where($where);
        $this->db->order_by('`payment_projection_history`.`projectionId`', "DESC");
        $query  =   $this->db->get();
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }        
    }
    //function to get the records of current year
    function get_projection_report_current_year($data)
    {
        $where  =   "`payment_projection_history`.`created_at` between DATE_FORMAT(CURDATE() ,'%Y-01-01') AND CURDATE()";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            date_format(`payment_projection_history`.`dateOfCollection`, "%d-%b-%Y") AS collectionDate,
            `payment_projection_history`.`projectionAmount`, 
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        if($data['salesUserId']){
            $this->db->where('`payment_projection_history`.`salesUserId`',$data['salesUserId']);
        }
        if($data['distributorid']){
            $this->db->where('`payment_projection_history`.`distributorid`',$data['distributorid']);
        }
        $this->db->where($where);
        $this->db->order_by('`payment_projection_history`.`projectionId`', "DESC");
        $query  =   $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }
    //filter get collections records by combinations
    function generate_projection_report($data)
    {
        $betweenClause  =   "`payment_projection_history`.`created_at` BETWEEN '".$data['dateFrom']."' AND '".$data['dateTo']."'";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor,
            date_format(`payment_projection_history`.`dateOfCollection`, "%d-%b-%Y") AS collectionDate,
            `payment_projection_history`.`projectionAmount`, 
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        if($data['dateFrom'] && $data['dateTo'])
        {
            $this->db->where($betweenClause);
        }        
        if($data['salesUserId']){
            $this->db->where('`payment_projection_history`.`salesUserId`',$data['salesUserId']);
        }
        if($data['distributorid']){
            $this->db->where('`payment_projection_history`.`distributorid`',$data['distributorid']);
        }
        $this->db->order_by('`payment_projection_history`.`projectionId`', "DESC");
        $query  =   $this->db->get();
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }
}