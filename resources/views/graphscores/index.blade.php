@extends('app')

@section('content')
		<div class="container">
                    <div>
			<div class="content">
				<div class="title">Quiz Marks</div>
                        </div>      
                    </div>
                    <div>
                        <!-- Shows student graph-->
                        <div class="content">    
                                <?php if (isset($scores2)){                                       
                                        echo \Lava::render('ColumnChart', 'myFancyChart2', 'myChart', array('height'=>400, 'width'=>650));}
                                ?>                               
                        </div>
                    </div>
		</div>

@endsection