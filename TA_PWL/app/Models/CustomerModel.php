<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';

    protected $fillable = ['nama_lengkap', 'email', 'no_hp', 'alamat'];
}
