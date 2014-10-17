<?php

class MedicalClaim__PanelClinic extends \Eloquent 
{
  public $table = 'medical_claim_panel_clinics';

  public static $rules = [
    'name' => 'required'
  ];
  protected $fillable = ['name'];

  public static function selectOptions()
  {
    return ['' => 'Select One'] + (static::all()->lists('name', 'id'));
  }

}