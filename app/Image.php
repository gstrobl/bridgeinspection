<?php

namespace App;

use Moloquent;

class Image extends Moloquent
{
  /**
   * The collection associated with the model.
   *
   * @var string
   */
  protected $collection = 'images';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'bridge_id', 'inspection_id', 'filename', 'filepath'
  ];
  public function imageMarker()
  {
    return $this->hasMany('App\ImageMarker');
  }
}
