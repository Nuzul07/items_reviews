<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table ='image';
    protected $primaryKey = 'id';
    protected $fillable = [
    'lokasi_file',
    'id_barang',
    'id_user'
    ];
}
