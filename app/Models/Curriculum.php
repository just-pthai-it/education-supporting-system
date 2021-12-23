<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curriculum extends Model
{
    use HasFactory;

    public const table = 'curriculum';
    public const table_as = 'curriculum as cc';

    protected $table = 'curriculum';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];

    public function modules () : BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'curriculum_module', 'id_curriculum',
                                    'id_module');
    }
}
