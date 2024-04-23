<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    //protected $db;
    protected $table      = 'display_header';
    protected $primaryKey = 'display_id';

    protected $returnType = 'array';

    protected $allowedFields = ["display_id","manual_id","master_id","description","disp_heading","col_heading","col_align","col_total_ind","col_width","col_height","grid_height","cols_per_row","rows_per_pg","select_clause","search_by","from_clause","where_clause","ext_param_name","ext_param_value","group_by_clause","having_clause","order_by_clause","return_object","return_val_seq","display_type"];
    // protected $createdField = 'created_at';
    // protected $updatedField = 'updated_at';
}