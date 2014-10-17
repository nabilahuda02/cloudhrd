<?php

    class Subscription extends Ardent {

    /**
    * $show_authorize_flag
    * 0 => all
    * 1 => show mine only
    * 2 => if i'm a head of ou, show all under my ou
    * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
    */
    static $show_authorize_flag = 0;

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

    /**
    * Fillable columns
    */
    protected $fillable = [
        'user_id',
        'staff_count',
        'start_date',
        'end_date',
        'unit_price',
        'is_trial',

    ];

    /**
    * These attributes excluded from the model's JSON form.
    * @var array
    */
    protected $hidden = [
    // 'password'
    ];

    /**
    * Validation Rules
    */
    private static $_rules = [
        'store' => [
            'user_id' => 'required',
            'staff_count' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'unit_price' => 'required',
            'is_trial' => 'required',

        ],
        'update' => [
            'user_id' => 'required',
            'staff_count' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'unit_price' => 'required',
            'is_trial' => 'required',

        ]
    ];

    public static $rules = [];

    public static function setRules($name)
    {
        self::$rules = self::$_rules[$name];
    }

    /**
    * ACL
    */

    public static function canList() 
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'Subscription Admin'], ['Subscription:list']));
    }

    public static function canCreate() 
    {
        return (Auth::user() && Auth::user()->ability(['Admin', 'Subscription Admin'], ['Subscription:create']));
    }

    public function canShow()
    {
        $user = Auth::user();
        if($user->hasRole('Admin', 'Subscription Admin'))
            return true;
        if(isset($this->user_id) && $user->can('Subscription:show')) {
            if($this->user_id === $user->id) {
                return true;
            }
            if($user->is_authorized(static::$show_authorize_flag, $this->user_id)) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function canUpdate() 
    {
        $user = Auth::user();
        if($user->hasRole('Admin', 'Subscription Admin'))
          return true;
        if(isset($this->user_id) && $user->can('Subscription:edit')) {
          if($this->user_id === $user->id) {
            return true;
            }
            if($user->is_authorized(static::$update_authorize_flag, $this->user_id)) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function canDelete() 
    {
        $user = Auth::user();
        if($user->hasRole('Admin', 'Subscription Admin'))
            return true;
        if(isset($this->user_id) && $user->can('Subscription:update')) {
            if($this->user_id === $user->id) {
                return true;
            }
            if($user->is_authorized(static::$delete_authorize_flag, $this->user_id)) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
    * Relationships
    */
   
   public function user()
   {
    return $this->belongsTo('User', 'user_id');
   }
   
    // public function status()
    // {
    //     return $this->hasOne('Status');
    // }


    /**
    * Decorators
    */

    public function getNameAttribute($value)
    {
        return $value;
    }

    /**
    * Boot Method
    */

    public static function boot()
    {
        parent::boot();

        self::created(function(){
            Cache::tags('Subscription')->flush();
        });

        self::updated(function(){
            Cache::tags('Subscription')->flush();
        });

        self::deleted(function(){
            Cache::tags('Subscription')->flush();
        });
    }
}