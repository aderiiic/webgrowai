<?php
// app/Models/Ga4Integration.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ga4Integration extends Model
{
    protected $fillable = ['site_id','property_id','service_account_json','status'];
}
