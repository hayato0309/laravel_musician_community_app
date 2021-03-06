<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Role;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'avatar', 'email', 'password', 'greeting', 'interests',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function favoritePosts()
    {
        return $this->hasManyThrough(
            Post::class, // 仲介するModel
            Like::class, // 最終的に取得したいデータのModel
            'user_id',
            'id',
            'id',
            'post_id'
        );
    }

    public function follows()
    {
        return $this->belongsToMany(Follow::class);
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'followed_id'); // LaravelのNaming conventionに沿っていないため、第2~4の引数が必要。
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'following_id'); // LaravelのNaming conventionに沿っていないため、第2~4の引数が必要。
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    static function filterUsersForAdmin($user_search, $role_search, $status_search)
    {
        $query = User::query();

        if (!empty($user_search) && empty($role_search) && empty($status_search)) {
            $query->withTrashed()->where('name', 'like', '%' . $user_search . '%');
        }

        if (empty($user_search) && !empty($role_search) && empty($status_search)) {
            $query->withTrashed()->whereHas('roles', function ($query) use ($role_search) {
                $query->where('slug', $role_search);
            });
        }

        if (empty($user_search) && empty($role_search) && !empty($status_search)) {
            if ($status_search === 'active') {
                $query->where('deleted_at', NULL);
            } elseif ($status_search === 'deactivated') {
                $query->onlyTrashed();
            }
        }

        if (!empty($user_search) && !empty($role_search) && empty($status_search)) {
            $query->withTrashed()
                ->where('name', 'like', '%' . $user_search . '%')
                ->whereHas('roles', function ($query) use ($role_search) {
                    $query->where('slug', $role_search);
                });
        }

        if (empty($user_search) && !empty($role_search) && !empty($status_search)) {
            if ($status_search === 'active') {
                $query->where('deleted_at', NULL)
                    ->whereHas('roles', function ($query) use ($role_search) {
                        $query->where('slug', $role_search);
                    });
            } elseif ($status_search === 'deactivated') {
                $query->onlyTrashed()
                    ->whereHas('roles', function ($query) use ($role_search) {
                        $query->where('slug', $role_search);
                    });
            }
        }

        if (!empty($user_search) && empty($role_search) && !empty($status_search)) {
            if ($status_search === 'active') {
                $query->where('name', 'like', '%' . $user_search . '%')->where('deleted_at', NULL);
            } elseif ($status_search === 'deactivated') {
                $query->onlyTrashed()->where('name', 'like', '%' . $user_search . '%');
            }
        }

        if (!empty($user_search) && !empty($role_search) && !empty($status_search)) {
            if ($status_search === 'active') {
                $query->where('name', 'like', '%' . $user_search . '%')
                    ->whereHas('roles', function ($query) use ($role_search) {
                        $query->where('slug', $role_search);
                    })
                    ->where('deleted_at', NULL);
            } elseif ($status_search === 'deactivated') {
                $query->where('name', 'like', '%' . $user_search . '%')
                    ->whereHas('roles', function ($query) use ($role_search) {
                        $query->where('slug', $role_search);
                    })
                    ->onlyTrashed();
            }
        }

        $users = $query->paginate(10);
        return $users;
    }
}
