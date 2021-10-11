<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participate extends Model
{
    use HasFactory;

    public const table = 'participate';
    public const table_as = 'participate as par';

    protected $table = 'participate';
    public $timestamps = false;

    protected $fillable = [
        'id_module_class',
        'id_student',
        'evaluation',
        'process_score',
        'test_score',
        'final_score',
    ];

    protected $hidden = [
        'pivot'
    ];
}
