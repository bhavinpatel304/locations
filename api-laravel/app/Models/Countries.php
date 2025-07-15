<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'countries';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'alpha2_code', 'alpha3_code'

    ];
}
