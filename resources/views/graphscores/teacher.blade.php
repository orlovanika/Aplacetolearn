@extends('app')

@section('content')
		<div class="container">
                    <div style="float:left; width: 40%">
			<div class="content">
				<div class="title">Graph Marks:</div>
                                
                                <!--form for various graphs available -->

                                <h4>Show % of students who have taken the test: </h4>
                                    
                                        {!!Form::open()!!}
                                                <select name='test_taken'>
                                                        <option selected disabled>Select Test</option>
                                                        @foreach($testid_detall as $testids_all)
                                                        <option value="{{ $testids_all}}">{{ $testids_all }}</option>
                                                        @endforeach
                                                </select>
                                        {!! Form::submit('Show graph') !!}
                                        {!!Form::close()!!}   
                                <h4>Show all student marks for selected test: </h4>
                           
                                        {!!Form::open()!!}
                                                <select name='test_id'>
                                                        <option selected disabled>Select Test</option>
                                                        @foreach($testids as $testid)
                                                        <option value="{{ $testid}}">{{ $testid }}</option>
                                                        @endforeach
                                                </select>
                                        {!! Form::submit('Show graph') !!}
                                        {!!Form::close()!!}
                                        
                                <h4>Show all test marks for selected student: </h4>
                                    
                                        {!!Form::open()!!}
                                                <select name='student_id'>
                                                        <option selected disabled>Select Student</option>
                                                        @foreach($stud_ids as $stud_id)
                                                        <option value="{{ $stud_id}}">{{ $stud_id }}</option>
                                                        @endforeach
                                                </select>
                                        {!! Form::submit('Show graph') !!}
                                        {!!Form::close()!!}
                                        
                                <h4>Show sum of correct answers for specific test: </h4>
                                    
                                        {!!Form::open()!!}
                                                <select name='testsum_id'>
                                                        <option selected disabled>Select Test</option>
                                                        @foreach($testid_det as $testids_)
                                                        <option value="{{ $testids_}}">{{ $testids_ }}</option>
                                                        @endforeach
                                                </select>
                                        {!! Form::submit('Show graph') !!}
                                        {!!Form::close()!!}
                                        
                                
			</div>
                    </div>
                    <div style="float:left; width: 40%">
                        
                        <!--Section which shows various graphs -->

                        <div class="content">
                                <?php if (isset($scores)){
                                        echo \Lava::render('LineChart', 'myFancyChart', 'myChart', array('height'=>400, 'width'=>650));}
                                ?>
                                <?php if (isset($scores2)){                                       
                                        echo \Lava::render('ColumnChart', 'myFancyChart2', 'myChart', array('height'=>400, 'width'=>650));}
                                ?>
                                <?php if (isset($qscores)){
                                        echo \Lava::render('LineChart', 'myFancyChart3', 'myChart', array('height'=>400, 'width'=>650));}
                                ?>
                                <?php if (isset($taken_id)){
                                        echo \Lava::render('PieChart', 'myFancyChart4', 'myChart', array('height'=>400, 'width'=>650));}
                                ?>
                        </div>
                    </div>
		</div>

@endsection