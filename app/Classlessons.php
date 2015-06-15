<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Classlessons extends Model{
    
    protected $table="lessons";
    public $timestamps= false;
    protected $fillable = ['vid','less', 'Title' ,'aud' ];
    
    public static $rules= array(
       'less'=>'required |min:100',
       'Title'=>'required| min:10',
        'vid'=>'required',
        'aud'=>'required',
    ); 
    
}




?>