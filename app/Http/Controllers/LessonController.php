<?php

namespace App\Http\Controllers;

use App\Classlessons; //this is the model
use Validator;
use Input;
use Redirect;
use Illuminate\Http\Request;
use Auth;



class LessonController extends Controller{
    public function __construct()
	{
		$this->middleware('auth');
	}
       
    public function index(){
                if ((Auth::user()->role)=='teacher'){    
    
        
        $learn=Classlessons::all();
        $learn->toarray();
        return view('Classlessons.index')->with('learn', $learn);
        
                }
    }
    
     
    
     public function lessons(){
                if ((Auth::user()->role)=='student'){    
    
        
        $learn=Classlessons::all();
        $learn->toarray();
        return view('Classlessons.lessons')->with('learn', $learn);
        
                }
    }
    
       public function lessonplan($id){
         if ((Auth::user()->role)=='student'){    
    
   $learns=Classlessons::find($id);
        return view('Classlessons.lessonplan')->with('learns', $learns);
         }
          
    }
    
    
    public function show($id){
         if ((Auth::user()->role)=='teacher'){    
    
   $learns=Classlessons::find($id);
        return view('Classlessons.show')->with('learns', $learns);
         }
          
    }
    
  
    public function create(){
                    if ((Auth::user()->role)=='teacher'){    

        return view('Classlessons.create');
                    }
    }
    
    public function store(Request $request){
                    if ((Auth::user()->role)=='teacher'){    

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
       return Redirect('Classlessons/index');
                    }
    }
    
    public function edit($id){
                    if ((Auth::user()->role)=='teacher'){    

        $lessons=Classlessons::find($id);
        if(is_null($lessons))
           {
            return Redirect::route('Classlessons/index');
           }
           return view('Classlessons.edit')->with('lessons',$lessons);
                    }
    }
    
    public function update($id)
    {
                    if ((Auth::user()->role)=='teacher'){    
        $rules=array(
          'Title'=>'required',
          'less'=>'required'
          
        );
  
        $inputs=Input::all();
        $validation= Validator::make($inputs,$rules);
        if($validation->passes())
        {
            
            $lessons=Classlessons::find($id);
            $lessons->update($inputs);
        return Redirect('Classlessons/index')
        ->withInput()
        ->withErrors($validation)
        ->with('message','Success');
            
        }
        else{
        return Redirect::route('Classlessons.edit', $id)
    ->withInput()
    ->withErrors($validation)
    ->with('message','error');
        }
                    }
    }
    
    public function destroy($id)
    {
                    if ((Auth::user()->role)=='teacher'){    

       Classlessons::find($id)->delete();
       return Redirect('Classlessons/index')
    ->withInput()
    ->with('message','Deleted');
                    }
    }
}


?>