@extends('app')

@section('content')

<div class="container">
<div>
     <p><a href="{{ url('Classlessons' , 'create') }}">Add Item</a></p>
    </div>


   
    <div>
    <table>
@foreach($learn as $le)
     <tr>
      <td> <h3><a href="{{url('Classlessons', $le->id)}}">{{$le->Title}}</a></h3> </td>
       
          
     <td>  {!! link_to_route('Classlessons.edit', 'edit', array($le->id),
                array('class' => 'btn btn-warning')) !!}</td>
              <td>   {!! Form::open(array('method'=> 'DELETE', 'route' =>
              array('Classlessons.destroy', $le->id))) !!} 
        {!! Form::submit('Delete', array('class' => 'btn btn-danger')) !!}</td>
          </tr>
        @endforeach
      </table>
    </div>
</div>
  
   
    
    @endsection
