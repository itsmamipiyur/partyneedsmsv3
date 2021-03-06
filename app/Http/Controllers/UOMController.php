<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UOM;

class UOMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $idss = \DB::table('tblUOM')
           ->select('uomCode')
           ->orderBy('uomCode', 'desc')
           ->first();

       if ($idss == null) {
         $newID = $this->smartCounter("0000");
       }else{
         $newID = $this->smartCounter($idss->uomCode);
       }

       $uoms = UOM::all();

        return view('maintenance.uom')
            ->with('newID', $newID)
            ->with('uoms', $uoms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = ['uom_code' => 'required',
                  'uom_name' => 'required|unique:tblUOM,uomName'];
        $this->validate($request, $rules);

        $uom = new UOM;
        $uom->uomCode = $request->uom_code;
        $uom->uomName = $request->uom_name;
        $uom->uomDesc = $request->uom_description;
        $uom->save();

        return redirect('uom')
            ->with('alert-success', 'Unit of Measurement was successfully added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $uom = UOM::find($id);
        $uom->delete();

        return redirect('uom')
            ->with('alert-success', 'Unit of Measurement was successfully deactivated!');
    }

    public function uom_update(Request $request)
    {
       $rules = ['uom_name' => 'required | max:100'];
       $id = $request->uom_code;

       $this->validate($request, $rules);
       $uom = UOM::find($id);
       $uom->uomName = $request->uom_name;
       $uom->uomDesc = $request->uom_description;
       $uom->save();

       return redirect('uom')
            ->with('alert-success', 'Unit of Measurement was successfully updated!');
    }

      public function uom_restore(Request $request)
      {
        $id = $request->uom_code;
        $uom = UOM::onlyTrashed()->where('uomCode', '=', $id)->firstOrFail();
        $uom->restore();

        return redirect('uom')->with('alert-success', 'Unit of Measurement was successfully restored.');
      }
}
