<?php

use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserTrait;
use Subscription\SubscriptionTrait;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser implements UserInterface, RemindableInterface
{

    use UserTrait, SubscriptionTrait, RemindableTrait, HasRole;

    /**
     * $show_authorize_flag
     * 0 => all
     * 1 => show mine only
     * 2 => if i'm a head of ou, show all under my ou
     * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
     */
    static $show_authorize_flag = 2;

    /**
     * $update_authorize_flag
     * 0 => all
     * 1 => show mine only
     * 2 => if i'm a head of ou, show all under my ou
     * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
     */
    static $update_authorize_flag = 0;

    /**
     * $delete_authorize_flag
     * 0 => all
     * 1 => show mine only
     * 2 => if i'm a head of ou, show all under my ou
     * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
     */
    static $delete_authorize_flag = 0;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'domain',
        'database',
        'username',
        'email',
        'password',
        'password_confirmation',
        'organization_unit_id',
        'confirmation_code',
        'confirmed',
        'trial_ends',
        'subscription_ends',
        'reseller_code',
        'reseller_id',
    ];

    /**
     * Validation Rules
     */
    static $_rules = [
        'store' => [
            'domain' => 'required|alpha_dash|max:12|unique:users,domain',
            'name' => 'required',
            'username' => 'required|alpha_dash|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'min:4',
        ],
        'testing' => [
            'domain' => 'required|alpha_dash|max:12|unique:users,domain',
            'name' => 'required',
            'username' => 'required|alpha_dash|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
        ],
        'reseller' => [
            'name' => 'required',
            'username' => 'required|alpha_dash|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'min:4',
        ],
        'update' => [
            'domain' => 'required|alpha_dash|unique:users,domain',
            'name' => 'required',
            'username' => 'required|alpha_dash|unique:users,username',
            'email' => 'required|email|unique:users,email',
        ],
        'resetPassword' => [
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'min:4',
        ],
        'setPassword' => [
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'min:4',
        ],
        'setConfirmation' => [
            'confirmed' => 'numeric|min:0|max:1',
        ],
        'changePassword' => [
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'min:4',
        ],
    ];

    static $rules = [];

    public static function setRules($name)
    {
        self::$rules = self::$_rules[$name];
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    public function getAuthorizedUserids($authorization_flag)
    {
        if ($authorization_flag === 0) {
            return [];
        }

        if ($authorization_flag === 1) {
            return [$this->id];
        }

        $key = implode('.', ['User', 'getAuthorizedUserids', $this->id, $authorization_flag]);
        $user = $this;
        return Cache::tags(['User', 'OrganizationUnit'])->rememberForever($key, function () use ($authorization_flag, $user) {
            $result = [$user->id];
            if ($user->organizationunit->user_id === $user->id) {
                if ($authorization_flag == 2) {
                    $result = $user->organizationunit->users->lists('id');
                }
                if ($authorization_flag == 3) {
                    $result = User::whereIn('organization_unit_id', $user->organizationunit->descendantsAndSelf()->get()->lists('id'))->lists('id');
                }
            }
            return $result;
        });
    }

    public function isAuthorized($authorization_flag, $user_id)
    {
        if ($authorization_flag == 0) {
            return true;
        }
        $users = getAuthorizedUserids($authorization_flag);
        return in_array($user_id, $users);
    }

    public function isReseller()
    {
        return in_array(6, $this->roles->lists('id'));
    }

    public function isSubscriber()
    {
        return in_array(7, $this->roles->lists('id'));
    }

    public static function generateDatabaseName()
    {
        return str_replace('.', '_', uniqid('hrduser_', true));
    }

    public static function getResellerCode()
    {
        $str = strtoupper(str_random(6));
        $reseller_codes = User::all()->lists('reseller_id');
        while (in_array($str, $reseller_codes)) {
            $str = strtoupper(str_random(6));
        }
        return $str;
    }

    /**
     * ACL
     */

    public static function canList()
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'User Admin'], ['User:list']));
    }

    public static function canCreate()
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'User Admin'], ['User:create']));
    }

    public function canShow()
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'User Admin'], ['User:show']));
    }

    public function canUpdate()
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'User Admin'], ['User:edit']));
    }

    public function canDelete()
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'User Admin'], ['User:delete']));
    }

    public function canSetPassword()
    {
        return true;
    }

    public function canSetConfirmation()
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'User Admin'], ['User:set_confirmation']));
    }

    /**
     * Decorators
     */

    protected $type;

    public function getTypeAttribute()
    {
        $ids = $this->roles->lists('id');
        return (count($ids) === 0) ? 'Customer' : in_array(6, $ids) ? 'Reseller' : 'Admin';
    }

    public function getDomainAttribute($value)
    {
        $domain = '.cloudhrd.com';
        if (App::environment('local')) {
            $domain = '.cloudhrd.dev';
        }

        return $value . $domain;
    }

    /**
     * Relationships
     */

    public function organizationunit()
    {
        return $this->belongsTo('OrganizationUnit', 'organization_unit_id');
    }

    public function reseller()
    {
        return $this->belongsTo('User', 'reseller_id');
    }

    public function subscriptions()
    {
        return $this->hasMany('Subscription', 'user_id');
    }

    public function cloudhrdmail($view, $subject)
    {
        $user = $this;
        Mail::send('emails.' . $view, compact('user'), function ($mail) use ($user, $subject) {
            $mail->to($user->email);
            $mail->subject($subject);
        });
    }

    /**
     * Boot
     */

    public static function boot()
    {
        parent::boot();
        // self::BootSubscriptionHandler();

        self::created(function ($user) {
            Cache::tags('User')->flush();
        });

        self::updated(function () {
            Cache::tags('User')->flush();
        });

        self::deleted(function () {
            Cache::tags('User')->flush();
        });
    }

}
