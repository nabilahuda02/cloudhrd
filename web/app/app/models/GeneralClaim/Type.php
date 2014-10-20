<?php 

class GeneralClaim__Type extends Eloquent
{
  protected $table = 'general_claim_types';


  protected $fillable = ['name', 'unit_price', 'unit'];

  public static $rules = [
    'name' => 'required'
  ];

  public function getUnitPriceAttribute($value)
  {
    return Helper::currency_format($value, false);
  }
}