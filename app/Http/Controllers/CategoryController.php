<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Category;
use DB;

class CategoryController extends Controller
{
  protected function validator(array $data)
  {
      return Validator::make($data, [
          'name' => 'required|max:255',
      ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function createCategory()
  {
    $category_items = DB::table('categories')->get();
    return view('dashboard.pages.categories.overview',compact('category_items'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function createCategoryAdd()
  {
    return view('dashboard.pages.categories.addcategory');
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
      return redirect()->route('category.add')
            ->withInput()
            ->withErrors($validator);
    }

    $category_item = Category::create([
      'name' => $request['name']
    ]);

    return redirect()->route('category.add');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
      $category_item = Category::find($id);
      return view('dashboard.pages.categories.editcategory',compact('category_item'));
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
        return redirect()->route('category.edit', $id)
              ->withErrors($validator);
      }

      $catData = $request->all();
      Category::find($id)->update($catData);
      return redirect()->route('category');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
      //
  }
}
