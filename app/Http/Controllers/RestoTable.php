<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestoTables;
use Auth;

class RestoTable extends Controller
{
    //
    public function restoTables(){

            $tables = RestoTables::whereNull('deleted_at')->where('resto_id',Auth::user()->restaurants->id)->orderBy('name','ASC')->get();

        $data = [
            'tables' => $tables
        ];
        return view('tables.tables',$data);
    }

    public function new_table(){

        return view('tables.table_form');
    }

    public function save(Request $request)
    {
        //  dd($request->all());

        $id = $request->id;
        if(empty($id))
            $table = new RestoTables();
        else
            $table = RestoTables::find($id);

        $table->name = $request->name;
        $table->is_active =1;
        $table->resto_id = Auth::user()->role=="restaurant"?Auth::user()->restaurants->id:0;
        $table->save();

        $id = $table->id;
        if($id > 0)
            echo json_encode(array('type' => 'success', 'message'=>"Table is saved successfully."));
        else
            echo json_encode(array('type' => 'error', 'message'=>"Table is not saved successfully."));
    }

    public function delete($id){
        //$id = CommonMethods::decrypt($id);
        $table = RestoTables::find($id);
        $table->deleted_at = date('Y-m-d H:i:s');
        $table->save();


    }
    public function edit($id){

        $table = RestoTables::find($id);

        $data = [
            'table' => $table,
        ];


        return view('tables.table_form',$data);
    }
}
