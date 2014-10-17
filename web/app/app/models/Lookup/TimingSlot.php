<?php

class Lookup__TimingSlot extends \Eloquent {
  protected $table = 'lookup_timing_slots';
  protected $fillable = ['name', 'start', 'end'];
  public static $rules = [
    'start' => 'required',
    'end' => 'required',
    'name' => 'required'
  ];

  public static function former_options()
  {
    return self::all()->map(function($data){
      return [
        'id'   => $data->id,
        'name' => $data->pretty()
      ];
    })->lists('name', 'id');
  }

  public function pretty()
  {
    return date('g:i a', strtotime('1970-01-01 ' . $this->start))  . ' - ' . date('h:i:s A', strtotime('1970-01-01 ' . $this->end)) . ' (' . $this->name . ')';
  }

  public static function selectOptions()
  {
    return ['' => 'Select One'] + (static::all()->lists('name', 'id'));
  }
}