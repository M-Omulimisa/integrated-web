@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = $course->code.': '.$course->title)

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => '...',
    'menu_group'    => '...',
    'menu_item'     => 'E-Learning',
    'menu_item_url' => '#',
    'current'       => '...'
])
<!-- end page title -->

<!-- Row -->
<div class="row">
    <div class="col-12 col-sm-12 col-md-3">
        <div class="card">
            <div class="card-body">
                @include('e_learning.courses.menu')
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-9">
        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">                    
                    @can('view_course_contents')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/content/'.$course->id) }}">Content</a></li>
                    @endcan
                    @can('list_el_chapters')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/chapters/'.$course->id) }}">Topics</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/lectures/'.$course->id) }}">Lectures</a></li>
                    @endcan
                    @can('view_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ route('e-learning.lectures.show', $data->lecture->id) }}">Lecture Detail</a></li>
                    @endcan
                    @can('add_el_lecture_topic_responses')
                        <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Respond</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/assignments/'.$course->id) }}">Chapter Quiz</a></li>
                    @endcan
                    @can('list_el_lectures')
                        <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/general-assignments/'.$course->id) }}">General Quiz</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">

                        <div class="alert alert-info">
                            <h5>Steps:</h5>
                            <ol>
                                <li>Press <b>Start</b> to start recording. <em>Note: You have to <b>"Allow"</b> in the browser to use microphone when prompted</em></li>
                                <li>Press "Stop" to end recording</li>
                                <li>Press "Download" to save the audio file. <em>Note: Ensure the file is not more than <b>5MBs</b></em></li>
                                <li>Attach the recording for upload</li>
                                <li>Submit the audio</li>
                            </ol>
                        </div>

                        <section class="experiment" style="padding: 5px 0px 5px 0px;">
                            <!-- <label for="time-interval">Time Interval (milliseconds):</label> -->
                            <!-- <input type="hidden" id="time-interval" value="300000">ms -->
                            <!-- <br> -->

                            <!-- <br> recorderType:
                            <select id="audio-recorderType" style="font-size:22px;vertical-align: middle;margin-right: 5px;">
                                <option>[Best Available Recorder]</option>
                                <option>MediaRecorder API</option>
                                <option>WebAudio API (WAV)</option>
                                <option>WebAudio API (PCM)</option>
                            </select>
                            <br> -->

                            <input id="left-channel" type="checkbox" checked style="width:auto; display: none;">
                            <!-- <label for="left-channel">Record Mono Audio if WebAudio API is selected (above)</label> -->

                            <br>
                            <label class="col-form-label">Audio Recording</label>
                            <br>

                            <button id="start-recording" class="btn btn-success">Start</button>
                            <button id="stop-recording" disabled class="btn btn-danger">Stop</button>

                            {{-- <button id="pause-recording" disabled class="btn btn-warning">Pause</button>
                            <button id="resume-recording" disabled class="btn btn-success">Resume</button> --}}

                            <button id="save-recording" disabled class="btn btn-warning">Download</button>
                        </section>

                        <section class="experiment">
                            <div id="audios-container"></div>
                        </section>

                       {!! Form::open(['method' => 'POST', 'files' => true, 'url' => ['e-learning/courses/lectures/topics/responses/store']]) !!}

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="lecture_topic_id" value="{{ $data->id }}">

                        {{-- <div class="form-group mb-3">
                            {!! Form::label('comment', 'Comment', ['class' => 'col-sm-12 col-form-label']) !!}                
                            <div class="col-sm-12">
                                {!! Form::textarea('comment', old('comment'), ['class' => 'content2']) !!}             
                            </div>
                        </div> --}}

                        <div class="form-group mb-3">
                            {!! Form::label('audio', 'Audio Attachment', ['class' => 'col-sm-4 col-form-label']) !!}                
                            <div class="col-sm-12">
                            <input type='file' id="audio" name="audio" accept=".mp3,.mpeg,.wav,.pcm,.webm">           
                            </div>
                            <span class="text-muted ml-2">Allowed types: mp3,mpeg,wav,webm | Max size: 5MBs</span>
                        </div>

                        <div class="form-buttons-w">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!} 

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
   
@endsection

@section('styles')
    

    <script src="https://cdn.WebRTC-Experiment.com/MediaStreamRecorder.js"></script>
    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>

    {{-- <link rel="stylesheet" href="https://cdn.webrtc-experiment.com/style.css"> --}}
@endsection

@section('scripts')
    


    <!-- https://github.com/streamproc/MediaStreamRecorder/blob/master/demos/audio-recorder.html -->

    <script>
            function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
                navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
            }

            var mediaConstraints = {
                audio: true
            };

            document.querySelector('#start-recording').onclick = function() {
                this.disabled = true;
                captureUserMedia(mediaConstraints, onMediaSuccess, onMediaError);
            };

            document.querySelector('#stop-recording').onclick = function() {
                this.disabled = true;
                mediaRecorder.stop();
                mediaRecorder.stream.stop();

                // document.querySelector('#pause-recording').disabled = true;
                document.querySelector('#start-recording').disabled = true;
                document.querySelector('#save-recording').disabled = false;
            };

            // document.querySelector('#pause-recording').onclick = function() {
            //     this.disabled = true;
            //     mediaRecorder.pause();

            //     document.querySelector('#resume-recording').disabled = false;
            // };

            // document.querySelector('#resume-recording').onclick = function() {
            //     this.disabled = true;
            //     mediaRecorder.resume();

            //     document.querySelector('#pause-recording').disabled = false;
            // };

            document.querySelector('#save-recording').onclick = function() {
                this.disabled = true;
                mediaRecorder.save();

                // alert('Drop WebM file on Chrome or Firefox. Both can play entire file. VLC player or other players may not work.');
            };

            var mediaRecorder;

            function onMediaSuccess(stream) {
                var audio = document.createElement('audio');

                audio = mergeProps(audio, {
                    controls: true,
                    muted: true
                });
                audio.srcObject = stream;
                audio.play();

                audiosContainer.appendChild(audio);
                // audiosContainer.appendChild(document.createElement('hr'));
                audiosContainer.appendChild(document.createElement('br'));

                mediaRecorder = new MediaStreamRecorder(stream);
                mediaRecorder.stream = stream;

                var recorderType = '[Best Available Recorder]'; // document.getElementById('audio-recorderType').value;

                if (recorderType === 'MediaRecorder API') {
                    mediaRecorder.recorderType = MediaRecorderWrapper;
                }

                if (recorderType === 'WebAudio API (WAV)') {
                    mediaRecorder.recorderType = StereoAudioRecorder;
                    mediaRecorder.mimeType = 'audio/wav';
                }

                if (recorderType === 'WebAudio API (PCM)') {
                    mediaRecorder.recorderType = StereoAudioRecorder;
                    mediaRecorder.mimeType = 'audio/pcm';
                }

                // don't force any mimeType; use above "recorderType" instead.
                // mediaRecorder.mimeType = 'audio/webm'; // audio/ogg or audio/wav or audio/webm

                mediaRecorder.audioChannels = !!document.getElementById('left-channel').checked ? 1 : 2;
                mediaRecorder.ondataavailable = function(blob) {
                    var a = document.createElement('a');
                    a.target = '_blank';
                    a.innerHTML = 'Recorded Audio Size: ~ ' + bytesToSize(blob.size * 2);
                    // a.innerHTML = 'Open Recorded Audio No. ' + (index++) + ' (Size: ' + bytesToSize(blob.size) + ') Time Length: ' + getTimeLength(timeInterval);

                    a.href = URL.createObjectURL(blob);

                    audiosContainer.appendChild(a);
                    // audiosContainer.appendChild(document.createElement('hr'));
                    audiosContainer.appendChild(document.createElement('br'));
                };

                var timeInterval = 300000;  //5mins document.querySelector('#time-interval').value;
                if (timeInterval) timeInterval = parseInt(timeInterval);
                else timeInterval = 5 * 1000;

                // get blob after specific time interval
                mediaRecorder.start(timeInterval);

                document.querySelector('#stop-recording').disabled = false;
                // document.querySelector('#pause-recording').disabled = false;
                // document.querySelector('#save-recording').disabled = false;
            }

            function onMediaError(e) {
                console.error('media error', e);
            }

            var audiosContainer = document.getElementById('audios-container');
            var index = 1;

            // below function via: http://goo.gl/B3ae8c
            function bytesToSize(bytes) {
                var k = 1000;
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes === 0) return '0 Bytes';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(k)), 10);
                return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
            }

            // below function via: http://goo.gl/6QNDcI
            function getTimeLength(milliseconds) {
                var data = new Date(milliseconds);
                return data.getUTCHours() + " hours, " + data.getUTCMinutes() + " minutes and " + data.getUTCSeconds() + " second(s)";
            }

            window.onbeforeunload = function() {
                document.querySelector('#start-recording').disabled = false;
            };
        </script>
@endsection