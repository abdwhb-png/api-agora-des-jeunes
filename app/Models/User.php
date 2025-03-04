<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;
    use HasRoles;

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

    public function getUser(): array
    {
        $user = (array) DB::table('users')->find($this->id);
        $userInfo = DB::table('user_infos')->where('user_id', $this->id)->first();
        $name = $userInfo->nom . ($userInfo->prenom ? ' ' . $userInfo->prenom : '');

        $tokens = DB::table('personal_access_tokens')->where('tokenable_id', $this->id)->get();

        $nameForAvatar = $name ? trim(collect(explode(' ', $name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' ')) : $this->email;

        return [
            'name' => $name,
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nameForAvatar) . '&color=7F9CF5&background=EBF4FF',
            ...$user,
            'info' => $userInfo,
            'tokens' => $tokens,
            // 'roles' => $this->getRoleNames(),
        ];
    }
}
