<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'level',
        'description',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $appends = ["level"];

    const N5 = 0;
    const N4 = 1;
    const N3 = 2;
    const N2 = 3;
    const N1 = 4;

    const LEVEL_LIST = [
        self::N5 => 'N5',
        self::N4  => 'N4',
        self::N3  => 'N3',
        self::N2  => 'N2',
        self::N1  => 'N1'
    ];

    public function getLevelAttribute() {
        $level = $this->attributes['level'];
        return Helpers::mappingAttribute($level, self::LEVEL_LIST);
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id', 'id');
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

}