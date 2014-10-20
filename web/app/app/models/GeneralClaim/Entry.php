<?php 

class GeneralClaim__Entry extends Eloquent
{

  // Add your validation rules here
  public static $rules = [
    'description' => 'required'
  ];

  // Don't forget to fill this array
  protected $fillable = ['description', 'claim_id', 'claim_type_id', 'quantity', 'amount', 'receipt_date', 'receipt_number'];

  protected $table = 'general_claim_entries';

  public function type()
  {
    return $this->hasOne('GeneralClaim__Type', 'id', 'claim_type_id');
  }

  public function getAmountAttribute($value)
  {
    return Helper::currency_format($value, false);
  }
}