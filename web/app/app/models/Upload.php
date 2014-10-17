<?php

class Upload extends Eloquent{
  protected $table = 'uploads';

  protected $fillable = [
    'mask',
    'file_name',
    'size',
    'file_url',
    'file_path',
    'thumb_url',
    'thumb_path',
    'imageable_id',
    'imageable_type',
  ];

  public function imageable()
  {
    return $this->morphTo();
  }
}