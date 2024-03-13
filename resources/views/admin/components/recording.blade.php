<br>
<button id="startRecord">Start Recording</button>
<button id="stopRecord" disabled>Stop Recording</button>
<button id="play">Play</button>
<button id="stop" disabled>Stop</button>
<button id="download" disabled>Download</button>
<br>
<br>
<audio id="audio" controls></audio>
<br>
<br>
<button id="upload" class="btn btn-primary btn-block" disabled>Upload Audio Answer</button>
{{-- audio player --}}
<br>
<br>


<script>
    let mediaRecorder;
    let audioChunks = [];

    const startRecordButton = document.getElementById('startRecord');
    const stopRecordButton = document.getElementById('stopRecord');
    const playButton = document.getElementById('play');
    const stopButton = document.getElementById('stop');
    const downloadButton = document.getElementById('download');
    const uploadButton = document.getElementById('upload');

    startRecordButton.addEventListener('click', startRecording);
    stopRecordButton.addEventListener('click', stopRecording);
    playButton.addEventListener('click', playRecording);
    stopButton.addEventListener('click', stopPlayback);
    downloadButton.addEventListener('click', downloadRecording);
    uploadButton.addEventListener('click', uploadRecording);


    async function startRecording(e) {
        e.preventDefault();
        const stream = await navigator.mediaDevices.getUserMedia({
            audio: true
        });
        mediaRecorder = new MediaRecorder(stream);

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                audioChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = () => {
            const audioBlob = new Blob(audioChunks, {
                type: 'audio/wav'
            });
            const audioUrl = URL.createObjectURL(audioBlob);

            playButton.disabled = false;
            downloadButton.disabled = false;
            uploadButton.disabled = false;

            document.getElementById('audio').src = audioUrl;
        };

        startRecordButton.disabled = true;
        stopRecordButton.disabled = false;

        mediaRecorder.start();
    }

    function stopRecording(e) {
        e.preventDefault();
        startRecordButton.disabled = false;
        stopRecordButton.disabled = true;

        if (mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
    }

    function playRecording(e) {
        e.preventDefault();
        const audio = document.getElementById('audio');
        audio.play();

        playButton.disabled = true;
        stopButton.disabled = false;
    }

    function stopPlayback(e) {
        e.preventDefault();
        const audio = document.getElementById('audio');
        audio.pause();
        audio.currentTime = 0;

        playButton.disabled = false;
        stopButton.disabled = true;
    }

    function downloadRecording(e) {
        e.preventDefault();
        const audioBlob = new Blob(audioChunks, {
            type: 'audio/wav'
        });
        const audioUrl = URL.createObjectURL(audioBlob);

        const a = document.createElement('a');
        a.href = audioUrl;
        a.download = 'recording.wav';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    async function uploadRecording(e) {
        e.preventDefault();
        const audioBlob = new Blob(audioChunks, {
            type: 'audio/wav'
        });

        const formData = new FormData();
        formData.append('audio', audioBlob);
        formData.append('id', {{ $id }});

        try {
            const response = await fetch('/api/upload-file', {
                method: 'POST',
                body: formData,
            });

            if (response.ok) {
                alert('File uploaded successfully!');
            } else {
                alert('File upload failed.');
            }
        } catch (error) {
            console.error('Error uploading file:', error);
        }
    }
</script>
