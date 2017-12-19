<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Bridge;
use App\Inspection;
use App\ImageMarker;
use App\Image;

class BridgeController extends Controller
{
  protected function validator(array $data)
  {
    return Validator::make($data, [
        'route' => 'required|max:255',
        'point' => 'required',
        'component' => 'required',
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
      return response()->json($validator->messages(), 200);
    }

    $bridgeItem = Bridge::create([
      'route' => $request['route'],
      'point' => $request['point'],
      'component' => $request['component']
    ]);

    return response()->json(['success' => 'success', 'id' => $bridgeItem->id], 200);

    // $portfolio_item->categories()->sync($request['categories']);

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $bridgeItem = Bridge::find($id);
    return response()->json($bridgeItem, 200);
  }

  /**
   * Display the specified resource.
   *
   */
  public function showAll()
  {
    $bridgeItems = Bridge::all();
    return response()->json($bridgeItems, 200);
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id, Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      return response()->json($validator->messages(), 200);
    }

    $bridgeItem = Bridge::find($id);
    $bridgeItem->route = $request['route'];
    $bridgeItem->point = $request['point'];
    $bridgeItem->component = $request['component'];
    $bridgeItem->save();

    return response()->json(['success' => 'success', 'id' => $bridgeItem->id], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $imageItems = Image::where('bridge_id', '=', $id)->get();
    foreach ($imageItems as $image) {
      $image_path = public_path('uploads/inspectionimages/').$image->filename;
      unlink($image_path);
      $image->delete();
    }
    $imageMarkers = ImageMarker::where('bridge_id', '=', $id)->get();
    foreach ($imageMarkers as $marker) {
      $marker->delete();
    }
    $inspectionItems = Inspection::where('bridge_id', '=', $id)->get();
    foreach ($inspectionItems as $inspectionItem) {
      $inspectionItem->delete();
    }
    $inspectionItem = Bridge::where('_id', '=', $id)->first();
    $inspectionItem->delete();
    return response()->json(['success' => 'success']);
  }
}
