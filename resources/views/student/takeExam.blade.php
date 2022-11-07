@extends('layouts.app')

@section('content')

@if(session('error'))
<div id="error" class="alert alert-danger">
    {{session('error')}}
</div>
@elseif(session('success'))
<div id="success" class="alert alert-success">
    {{session('success')}}
</div>
@endif

<div class="container">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{route('studentExams')}}">Exams</a></li>
    </ol>
</nav>
<h4>remaining time<span id="remaining_time"></span></h4>
<h1>Exam: {{$exam->title}}</h1>
<form action="{{route('submitAnswers')}}" method="POST">
    {{csrf_field()}}
    @foreach($exam->questions as $question)
        <h2>Q{{$loop->index+1}}:{{$question->content}} ({{$question->score}} Points)</h2>
        @foreach($question->answers as $answer)
        <div class="form-group form-check">
            <input type="radio" class="form-check-input" id="answer_{{$answer->id}}" name="question_{{$question->id}}" value="{{$answer->id}}" required>
            <label class="form-check-label" for="answer_{{$answer->id}}">{{$answer->content}}</label>
        </div>
        @endforeach
        <hr>
    @endforeach
    <button class="btn btn-success">Submit</button>
</form>

</div>

<script>
    function convertHMS(value) {
        const sec = parseInt(value, 10); // convert value to number if it's string
        let hours   = Math.floor(sec / 3600); // get hours
        let minutes = Math.floor((sec - (hours * 3600)) / 60); // get minutes
        let seconds = sec - (hours * 3600) - (minutes * 60); //  get seconds
        // add 0 if value < 10; Example: 2 => 02
        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return hours+':'+minutes+':'+seconds; // Return is HH : MM : SS
    }
    $(document).ready( function(){
        var timeRemaining={{$exam->timeRemaining}};
        var text=convertHMS(timeRemaining);
        $('#remaining_time').text(text);

        setInterval(() => {
            timeRemaining--;
            if(timeRemaining<=0){window.location="{{route('studentExams')}}";}
            text=convertHMS(timeRemaining);
            $('#remaining_time').text(text);
        }, 1000);
        setTimeout(() => {
            $('#error,#success').hide(400);
        }, 300);
    })
</script>
@endsection
