<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public const table = 'tag';
    public const table_as = 'tag as tag';

    protected $table = 'tag';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'is_optional',
        'is_permanent',
    ];

    protected $hidden = [];

}
