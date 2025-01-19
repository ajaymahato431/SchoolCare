<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guard = 'students';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentDetails()
    {
        return $this->hasOne(StudentDetail::class, 'student_id');
    }

    public function markEntries()
    {
        return $this->hasMany(MarkEntry::class, 'student_id');
    }

    public function attendences(): BelongsToMany
    {
        return $this->belongsToMany(Attendance::class);
    }

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignment::class);
    }

    public function scholorships(): BelongsToMany
    {
        return $this->belongsToMany(Scholorship::class);
    }

    public function positiveBehaviours()
    {
        return $this->hasMany(PositiveBehaviour::class, 'student_id');
    }

    public function negativeBehaviours()
    {
        return $this->hasMany(NegativeBehaviour::class, 'student_id');
    }
    public function participations()
    {
        return $this->hasMany(StudentParticipation::class, 'student_id');
    }

    public function classMappings()
    {
        return $this->hasMany(ClassMapping::class, 'student_id');
    }
}
