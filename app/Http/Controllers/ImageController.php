<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Image;
use App\Inspection;
use App\ImageMarker;

class ImageController extends Controller
{
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'uploadedFiles.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max: 2048'
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      return response()->json(['error' => 'error', 'messages' => $validator->messages()], 200);
    }

    $imagesReturn = array();
    if (empty($request['inspectionId'])) {
      $inspection = Inspection::create([
        'bridge_id' => ($request['bridgeId']) ? $request['bridgeId'] : '',
        'inspectiondate' => date("Y-m-d"),
        // 'achievedate' => date("Y-m-d"),
      ]);
    }
    foreach ($request->uploadedFiles as $image) {
      $imageName = uniqid().'.'.$image->getClientOriginalExtension();
      $image->move(public_path('uploads/inspectionimages'), $imageName);
      $imageItem = Image::create([
        'bridge_id' => (!(empty($request['bridgeId']))) ? $request['bridgeId'] : '',
        'filename' => $imageName,
        'filepath' => '/uploads/inspectionimages/'.$imageName,
        'inspection_id' => (empty($request['inspectionId'])) ? $inspection->id : $request['inspectionId']
      ]);
      array_push($imagesReturn, $imageName);
    }
    return response()->json(['success' => 'success',
                             'inspectionId' => (empty($request['inspectionId'])) ? $inspection->id : $request['inspectionId'],
                             'images' => $imagesReturn], 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $imageItems = Image::where('inspection_id', '=', $id)->get();
    return response()->json($imageItems, 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function showSpecificPhoto($inspectionId, $imageId)
  {
    $imageItem = Image::where('inspection_id', '=', $inspectionId)->where('_id', '=', $imageId)->first();
    return response()->json($imageItem, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroyImage($id)
  {
    $imageItem = Image::where('_id', '=', $id)->first();
    $image_path = public_path('uploads/inspectionimages/').$imageItem->filename;
    unlink($image_path);
    $imageItem->delete();
    return response()->json(['success' => 'success']);
  }
}
