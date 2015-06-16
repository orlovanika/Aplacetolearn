@extends('app')

@section('content')
    <div class="container">
<div>
    <h3>{{$learns['Title']}}</h3>
</div>
<div>
<video id="video" width="400" controls>
  <source src="../uploads/{{$learns['vid']}}.webm">
</video>
</div>
    <div>
<audio id="audio" width="400" controls>
    <source src="../uploads/{{$learns['aud']}}.wav"></source>
</audio>
    </div>
        <div>
            <button id="play">Play</button>
            <button id="pause">Pause</button>
        </div>
            <script>
                var playBtn = document.getElementById("play");
                var stopBtn = document.getElementById('pause');

                var playtog = function () {
                audio.play();
                video.play();
            
            };
                    
                    var pausetog = function () {
                        audio.pause();
                        video.pause();
                    };
                
                playBtn.addEventListener('click', playtog, false);
                stopBtn.addEventListener('click', pausetog, false);
              </script>
        <div>
        <h3>Lesson:</h3>
            <p>{{$learns['less']}}</p>
        </div>
            <div><a href="{{ URL::to('Classlessons/lessons')}}">Back</a>
            </div>
    </div>
    
    @endsection