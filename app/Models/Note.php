<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'color_code', // #FD8A8A, #FFCBCB, #9EA1D4, #F1F7B5, #A8D1D1, #DFEBEB
        'noted_time',
        'lesson_id',
    ];

    /**
     * Get the lesson associated with the note.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
