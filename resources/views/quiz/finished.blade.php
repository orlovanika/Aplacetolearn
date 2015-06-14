@extends('app')

@section('content')
		<div class="container">
			<div class="content">
				<div class="title">Finished!</div>
                                <h2> Your score is {{round($finalmark, 2)}} </h2>
                                <?php echo \Lava::render('LineChart', 'lookatmarks', 'myChart', array('height'=>400, 'width'=>650));?>
			</div>
                    
		</div>
@endsection
