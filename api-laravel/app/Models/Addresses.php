<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Countries;

class Addresses extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'addresses';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'address2', 'postal_code', 
        'city', 'province', 'id_country', 'phone', 'deleted'

    ];

    /**
     * Get the Country name associated with the address.
     */
    public function country()
    {
        return $this->hasOne(Countries::class,'id','id_country');
    }
}
