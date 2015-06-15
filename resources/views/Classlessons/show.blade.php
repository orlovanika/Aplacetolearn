<div>
    <h3>{{$lessons->Title}}</h3>
</div>
<div>
<video width="400" controls>
  <source src="uploads/{{$lessons->vid}}.wav" type="video/wav">
</video>
</div>
    <div>
<audio width="400" controls>
    <source src="uploads/{{$lessons->aud}}.webm"></source>
</audio>
    </div>
        <div>
            <button id="start">Start</button>
            <button id="pause">Pause</button>
        </div>
        <div>
            <p>{{$lessons->less}}</p>
        </div>
            <div>
            {!! link_to_route('Classlessons.index', 'Back') !!}
            </div>
                
                <script>
                    
                </script>