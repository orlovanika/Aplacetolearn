    @extends('app')

@section('content')
    
    @if($errors->any())
        @foreach($errors->all() as $err)
            <li>{{$err}}</li>
    @endforeach
@endif
    
    
    <div class="container">
        {!! Form::model($lessons, array('method' => 'PATCH','route' =>array('Classlessons.update', $lessons->id))) !!}
         <div>
        {!! Form::label('Title', 'Title: ') !!}
        {!! Form::text('Title',null  ) !!}
    </div>
        
         <div>
        
            {!! Form::label('less', 'Lesson: ') !!}
            {!! Form::textarea('less', null) !!}
        </div>
             <div>
                {!! Form::submit('Update') !!}
                <a href="{{ URL::to('Classlessons/index')}}">Cancel</a>
            </div>
        {!! Form::close() !!}
    </div>
    @endsection