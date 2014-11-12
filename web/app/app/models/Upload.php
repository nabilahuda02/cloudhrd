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

  public function humanSize($unit="") {
    if( (!$unit && $this->size >= 1<<30) || $unit == "GB")
      return number_format($this->size/(1<<30),2)."GB";
    if( (!$unit && $this->size >= 1<<20) || $unit == "MB")
      return number_format($this->size/(1<<20),2)."MB";
    if( (!$unit && $this->size >= 1<<10) || $unit == "KB")
      return number_format($this->size/(1<<10),2)."KB";
    return number_format($this->size)." bytes";
  }

  public static function boot()
  {
      parent::boot();
      Upload::deleting(function($entryFile)
      {
        if(file_exists($entryFile->file_path)) {
          unlink($entryFile->file_path);
        }
        if(file_exists($entryFile->thumb_path)) {
          unlink($entryFile->thumb_path);
        }
      });
  }

}