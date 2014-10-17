<?php

class Lookup__FamilyRelationship extends \Eloquent {
  protected $table = 'lookup_family_relationships';
  protected $fillable = ['name'];
  public static $rules = [
    'name' => 'required'
  ];
}