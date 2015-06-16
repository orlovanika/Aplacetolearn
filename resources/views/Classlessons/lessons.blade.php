@extends('app')

@section('content')

<div class="container">
    
@foreach($learn as $le)
   
    <div>
   

      <h2>Lessons:</h2>
      <h3><a href="{{url('Classlessons/lessonplan', $le->id)}}">{{$le->Title}}</a></h3>
    </div>
</div>
   
    
    @endsection
@endforeach