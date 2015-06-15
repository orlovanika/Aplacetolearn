<?php

namespace App\Http\Controllers;
use App\Classlessons; //this is the model
use Input;
use Redirect;
use Illuminate\Http\Request;


class LessonsController extends Controller{
    
    public function index(){
        
        
        $learn=Classlessons::all();
        $learn->toarray();
        return view('Classlessons.index')->with('learn', $learn);
        
        
    }
    
    public function show($id){
        $lessons=Classlessons::find($id);
        return view('Classlessons.show')->with('lessons', $lessons);
    }
    
    public function create(){
        return view('Classlessons.create');
    }
    
    public function store(Request $request){
        
        $this->validate($request,
                        [
                            'less'=>'required |min:100',
                            'Title'=>'required| min:10',
                            'vid'=>'required',
                             'aud'=>'required'
                        ]
                        );
        $inputs=$request->all();
        Classlessons::create($inputs);
        return Redirect('Classlessons');
        
    }
    
    public function edit($id){
        $lessons=Classlessons::find($id);
        if(is_null($lessons))
           {
            return Redirect::route('Classlessons');
           }
           return view('Classlessons.edit')->with('lessons',$lessons);
    }
    
    public function update($id)
    {
        $inputs=Classlessons::all($id);
        $validation= Validator::make($inputs, lessons::$rules);
        if($validation->passes())
        {
            $lessons=Classlessons::find($id);
            $lessons->update($inputs);
            return Redirect::route('Classlessons.index')
        ->withInput()
        ->with('message','Success');
            
        }
        return Redirect('Classlessons.edit', $id)
    ->withInput()
    ->withErrors($validation)
    ->with('message','error');
    }
    
    public function destroy($id)
    {
       Classlessons::find($id)->delete();
       return Redirect('Classlessons')
    ->withInput()
    ->with('message','Deleted');
    }
}


?>