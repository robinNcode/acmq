<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    public function getAllDataBy($branch_id){
        return DB::table('reports')->where('branch_id', $branch_id)->get();
    }
}
