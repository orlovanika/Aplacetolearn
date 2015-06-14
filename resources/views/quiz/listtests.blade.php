@extends('app')

@section('content')
		<div class="container">
			<div class="content">
                            <div class="title">Select a quiz</div>
                                
                            <!--list of tests available -->

                            @for ($i=0; $i< sizeof($testids); $i++)
                            @if ($teststatus[$i] == 1)
                                <h3>
                                    Quiz number {{$testids[$i]}} is already taken
                                </h3>
                            @elseif($teststatus[$i] == 0)
                                <h3>
                                <a href="{{$url= action ('QuizController@index',$testids[$i])}}">Quiz number {{$testids[$i]}} available</a>
                                </h3>
                            @endif
                           
                            @endfor
			</div>
                    
		</div>
@endsection