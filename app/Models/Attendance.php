<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'action_type',
        'action_time',
    ];

    protected $casts = [
        'action_time' => 'datetime',
    ];

    /**
     * Get Relation with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
