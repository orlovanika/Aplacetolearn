@extends('app')

@section('content')
{!! Html::script('node_modules/recordrtc/RecordRTC.js')!!}

{!! Html::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js') !!}

@if($errors->any())
    
    @foreach($errors->all() as $err)
    <li>{{$err}}</li>
    @endforeach
@endif
   
   
   <div class="container">
   <div>
                   <section class="experiment">
               <video id="preview" controls style="border: 1px solid rgb(15, 158, 238); height: 240px; width: 320px;"></video>
            </p>
            <hr />

            <button id="record">Start Recording</button>
            <button id="stop" disabled>Stop(Save)</button>
            <button id="delete" disabled>Delete/Restart</button>

            <div id="container" style="padding:1em 2em;"></div>
        </section>

        <section class="experiment">
           
        </section>
        </div>

            <script>
               function PostBlob(blob, fileType, fileName) {
            // FormData
            var formData = new FormData();
            formData.append(fileType + '-filename', fileName);
            formData.append(fileType + '-blob', blob);

            // progress-bar
            var hr = document.createElement('hr');
            container.appendChild(hr);
            var strong = document.createElement('strong');
            strong.id = 'percentage';
            strong.innerHTML = fileType + ' upload progress: ';
            container.appendChild(strong);
            var progress = document.createElement('progress');
            container.appendChild(progress);

            // POST the Blob using XHR2
            xhr('../Classlessons/save.php', formData, progress, percentage, function(fileURL) {
                container.appendChild(document.createElement('hr'));
                var mediaElement = document.createElement(fileType);

                var source = document.createElement('source');
                var href = location.href.substr(0, location.href.lastIndexOf('/') + 1);
                source.src = href + fileURL;

                if (fileType == 'video') source.type = 'video/webm; codecs="vp8, vorbis"';
                if (fileType == 'audio') source.type = !!navigator.mozGetUserMedia ? 'audio/ogg' : 'audio/wav';

                mediaElement.appendChild(source);

                mediaElement.controls = true;
                container.appendChild(mediaElement);
                mediaElement.play();

                progress.parentNode.removeChild(progress);
                strong.parentNode.removeChild(strong);
                hr.parentNode.removeChild(hr);
            });
        }

        var record = document.getElementById('record');
        var stop = document.getElementById('stop');
        var deleteFiles = document.getElementById('delete');

        var audio = document.querySelector('audio');

        var recordVideo = document.getElementById('record-video');
        var preview = document.getElementById('preview');

        var container = document.getElementById('container');

        // if you want to record only audio on chrome
        // then simply set "isFirefox=true"
        var isFirefox = !!navigator.mozGetUserMedia;

        var recordAudio, recordVideo;
        record.onclick = function() {
            record.disabled = true;
            navigator.getUserMedia({
                audio: true,
                video: true
            }, function(stream) {
                preview.src = window.URL.createObjectURL(stream);
                preview.play();

                // var legalBufferValues = [256, 512, 1024, 2048, 4096, 8192, 16384];
                // sample-rates in at least the range 22050 to 96000.
                recordAudio = RecordRTC(stream, {
                    //bufferSize: 16384,
                    //sampleRate: 45000,
                    onAudioProcessStarted: function() {
                        if (!isFirefox) {
                            recordVideo.startRecording();
                        }
                    }
                });

                if (isFirefox) {
                    recordAudio.startRecording();
                }

                if (!isFirefox) {
                    recordVideo = RecordRTC(stream, {
                        type: 'video'
                    });
                    recordAudio.startRecording();
                }

                stop.disabled = false;
            }, function(error) {
                alert(JSON.stringify(error, null, '\t'));
            });
        };

        var fileName;
        stop.onclick = function() {
            record.disabled = false;
            stop.disabled = true;

            preview.src = '';

            fileName = Math.round(Math.random() * 99999999) + 99999999;

            if (!isFirefox) {
                recordAudio.stopRecording(function() {
                    PostBlob(recordAudio.getBlob(), 'audio', fileName + '.wav');
                });
            } else {
                recordAudio.stopRecording(function(url) {
                    preview.src = url;
                    PostBlob(recordAudio.getBlob(), 'video', fileName + '.webm');
                });
            }

            if (!isFirefox) {
                recordVideo.stopRecording(function() {
                    PostBlob(recordVideo.getBlob(), 'video', fileName + '.webm');
                });
            }

            deleteFiles.disabled = false;
        };

        deleteFiles.onclick = function() {
            deleteAudioVideoFiles();
        };

        function deleteAudioVideoFiles() {
            deleteFiles.disabled = true;
            if (!fileName) return;
            var formData = new FormData();
            formData.append('delete-file', fileName);
            xhr('../Classlessons/delete.php', formData, null, null, function(response) {
                console.log(response);
            });
            fileName = null;
            container.innerHTML = '';
        }

        function xhr(url, data, progress, percentage, callback) {
            var request = new XMLHttpRequest();
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    callback(request.responseText);
                }
            };

            if (url.indexOf('../Classlessons/delete.php') == -1) {
                request.upload.onloadstart = function() {
                    percentage.innerHTML = 'Upload started...';
                };

                request.upload.onprogress = function(event) {
                    progress.max = event.total;
                    progress.value = event.loaded;
                    percentage.innerHTML = 'Upload Progress ' + Math.round(event.loaded / event.total * 100) + "%";
                };

                request.upload.onload = function() {
                    percentage.innerHTML = 'Saved!';
                };
            }

            request.open('POST', url);
            request.send(data);
        }

        window.onbeforeunload = function() {
            if (!!fileName) {
                deleteAudioVideoFiles();
                return 'It seems that you\'ve not deleted audio/video files from the server.';
            }
        }; function PostBlob(blob, fileType, fileName) {
            // FormData
            var formData = new FormData();
            formData.append(fileType + '-filename', fileName);
            formData.append(fileType + '-blob', blob);

            // progress-bar
            var hr = document.createElement('hr');
            container.appendChild(hr);
            var strong = document.createElement('strong');
            strong.id = 'percentage';
            strong.innerHTML = fileType + ' upload progress: ';
            container.appendChild(strong);
            var progress = document.createElement('progress');
            container.appendChild(progress);

            // POST the Blob using XHR2
            xhr('../Classlessons/save.php', formData, progress, percentage, function(fileURL) {
                container.appendChild(document.createElement('hr'));
                var mediaElement = document.createElement(fileType);

                var source = document.createElement('source');
                var href = location.href.substr(0, location.href.lastIndexOf('/php/') + 1);
                source.src = href + fileURL;

                if (fileType == 'video') source.type = 'video/webm; codecs="vp8, vorbis"';
                if (fileType == 'audio') source.type = !!navigator.mozGetUserMedia ? 'audio/ogg' : 'audio/wav';

                mediaElement.appendChild(source);

                mediaElement.controls = true;
                container.appendChild(mediaElement);
                mediaElement.play();

                progress.parentNode.removeChild(progress);
                strong.parentNode.removeChild(strong);
                hr.parentNode.removeChild(hr);
            });
        }

        var record = document.getElementById('record');
        var stop = document.getElementById('stop');
        var deleteFiles = document.getElementById('delete');

        var audio = document.querySelector('audio');

        var recordVideo = document.getElementById('record-video');
        var preview = document.getElementById('preview');

        var container = document.getElementById('container');

        // if you want to record only audio on chrome
        // then simply set "isFirefox=true"
        var isFirefox = !!navigator.mozGetUserMedia;

        var recordAudio, recordVideo;
        record.onclick = function() {
            record.disabled = true;
            navigator.getUserMedia({
                audio: true,
                video: true
            }, function(stream) {
                preview.src = window.URL.createObjectURL(stream);
                preview.play();

                // var legalBufferValues = [256, 512, 1024, 2048, 4096, 8192, 16384];
                // sample-rates in at least the range 22050 to 96000.
                recordAudio = RecordRTC(stream, {
                    //bufferSize: 16384,
                    //sampleRate: 45000,
                    onAudioProcessStarted: function() {
                        if (!isFirefox) {
                            recordVideo.startRecording();
                        }
                    }
                });

                if (isFirefox) {
                    recordAudio.startRecording();
                }

                if (!isFirefox) {
                    recordVideo = RecordRTC(stream, {
                        type: 'video'
                    });
                    recordAudio.startRecording();
                }

                stop.disabled = false;
            }, function(error) {
                alert(JSON.stringify(error, null, '\t'));
            });
        };

        var fileName;
        stop.onclick = function() {
            record.disabled = false;
            stop.disabled = true;

            preview.src = '';

            fileName = Math.round(Math.random() * 99999999) + 99999999;

            if (!isFirefox) {
                recordAudio.stopRecording(function() {
                    PostBlob(recordAudio.getBlob(), 'audio', fileName + '.wav');
                });
            } else {
                recordAudio.stopRecording(function(url) {
                    preview.src = url;
                    PostBlob(recordAudio.getBlob(), 'video', fileName + '.webm');
                });
            }

            if (!isFirefox) {
                recordVideo.stopRecording(function() {
                    PostBlob(recordVideo.getBlob(), 'video', fileName + '.webm');
                });
            }

            deleteFiles.disabled = false;
        };

        deleteFiles.onclick = function() {
            deleteAudioVideoFiles();
        };

        function deleteAudioVideoFiles() {
            deleteFiles.disabled = true;
            if (!fileName) return;
            var formData = new FormData();
            formData.append('delete-file', fileName);
            xhr('../Classlessons/delete.php', formData, null, null, function(response) {
                console.log(response);
            });
            fileName = null;
            container.innerHTML = '';
        }
var fileName;
        function xhr(url, data, progress, percentage, callback) {
            var request = new XMLHttpRequest();
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    callback(request.responseText);
                }
            };

            if (url.indexOf('../Classlessons/delete.php') == -1) {
                request.upload.onloadstart = function() {
                    percentage.innerHTML = 'Upload started...';
                };

                request.upload.onprogress = function(event) {
                    progress.max = event.total;
                    progress.value = event.loaded;
                    percentage.innerHTML = 'Upload Progress ' + Math.round(event.loaded / event.total * 100) + "%";
                };

                request.upload.onload = function() {
                    percentage.innerHTML = 'Saved! File name is ' + fileName;
                    document.getElementById("name").innerHTML= fileName;
                };
                
            }

            request.open('POST', url);
            request.send(data);
        }

        window.onbeforeunload = function() {
            if (!!fileName) {
                deleteAudioVideoFiles();
                return 'It seems that you\'ve not deleted audio/video files from the server.';
            }
        };
        
        

            </script>

<div>
    {!! Form::open(array('url'=>'Classlessons/store')) !!}
    <div>
        {!! Form::label('Title', 'Title: ') !!}
        {!! Form::text('Title',null  ) !!}
    </div>
        <div>
        <p>Video/Audio name(copy the name and paste into the textbox for audio and video): </p><p id="name"></p>
            {!! Form::label('vid', 'Video: ') !!}
        {!! Form::text('vid','') !!}
           
        </div>
        <div>
            {!! Form::label('aud', 'Audio: ')!!}
            {!!Form::text('aud','') !!}
        </div>
        
        <div>
        
        
            {!! Form::label('less', 'Lesson: ') !!}
            {!! Form::textarea('less', null) !!}
        </div>
        <div>
            {!! Form::submit('Submit Lesson') !!}
            <a href="{{ URL::to('Classlessons/index')}}">Cancel</a>
        </div>
        
        <div>
            {!! Form::close() !!}
        </div>
</div>
   </div>
                
@endsection