<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentTestAttempt extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_test_attemps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'test_id',
        'score_achieved',
        "completed_time"
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the student that took the test.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the test associated with this attempt.
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
