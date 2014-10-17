<?php

class Audit extends \Eloquent {

  // Add your validation rules here
  public static $rules = [];

  // Don't forget to fill this array
  protected $fillable = [
    'auditable_id',
    'auditable_type',
    'type',
    'ref',
    'data'
  ];

  public $_types = [
    1 => 'Created a new record',
    2 => 'Updated record details',
    3 => 'Changed record status'
  ];

  public function auditable()
  {
    return $this->morphTo();
  }

  public function setDataAttribute($value)
  {
    $this->attributes['data'] = serialize($value);
  }

  public function getTypeMaskAttribute()
  {
    $name = $value = $this->attributes['auditable_type'];
    switch ($value) {
      case 'Leave__Main':
        $name = 'Leave';
        break;
      case 'MedicalClaim__Main':
        $name = 'Medical Claim';
        break;
      case 'GeneralClaim__Main':
        $name = 'General Claim';
        break;
      case 'RoomBooking__Main':
        $name = 'Room Booking';
        break;
    }
    return $name;
  }

  public function getDataAttribute($value)
  {
    $value = unserialize($value);
    if($this->attributes['type'] === 3)
      return Status::find($value)->name;
    return $value;
  }

  public function getTypeAttribute($value)
  {
    return $this->_types[$value];
  }

  public function __construct(array $attributes = array())
  {
    parent::__construct($attributes);

    self::creating(function($item){
      $item->user_id = 0;
      if(Auth::user()) {
        $item->user_id = Auth::user()->id;
      }
    });
  }
}