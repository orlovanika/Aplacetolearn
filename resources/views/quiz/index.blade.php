@extends('app')

@section('content')
		<div class="container">
			<div class="content">
				<div class="title">Quiz {{$quizn}}</div>
                                {!!Form::open()!!}
                                
                                <!--Quiz question -->

                                <h2> {{$question[0]['qtest']}}</h2>
                                <div class="content">
                               
                                    <!--Multiple choice radio buttons -->

                                <div style="text-align: left;">
                                    <input type="radio" name="qchoice" value="a" checked><span class="question">{{$question[0]['a']}}</span>
                                    <br>
                                    <input type="radio" name="qchoice" value="b" ><span class="question">{{$question[0]['b']}}</span>
                                    <br>
                                    <input type="radio" name="qchoice" value="c" ><span class="question">{{$question[0]['c']}}</span>
                                    <br>
                                    <input type="radio" name="qchoice" value="d" ><span class="question">{{$question[0]['d']}}</span>
                                </div>
                                </div>
                                {!! Form::hidden('qn',$qn ) !!}
                                <div style="clear:both; padding: 10px"></div>
                                {!! Form::submit('Next question') !!}
                                {!!Form::close()!!} 
			</div>
                    
		</div>
@endsection