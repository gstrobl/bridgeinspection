<?php

namespace App;

use Moloquent;

class ImageMarker extends Moloquent
{
  /**
   * The collection associated with the model.
   *
   * @var string
   */
  protected $collection = 'image_marker';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'inspection_id', 'bridge_id', 'image_id', 'damage_description', 'classification'
  ];
}
