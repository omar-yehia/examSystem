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
<h1>All Exams</h1>
    <div class="table-responsive">
        <table class="table">
            <caption>List of exams</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Total Score</th>
                    <th scope="col">Time</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allExams as $exam)
                <tr>
                    <th scope="row">{{$exam->id}}</th>
                    <td>{{$exam->title}}</td>
                    <td>{{$exam->total_score}}</td>
                    <td>{{date('H:i', mktime(0,$exam->time))}} Hours</td>
                    <td><a class="btn btn-primary" href="{{route('startExam',['id'=>$exam->id])}}">Start Exam</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper">
         {{ $allExams->links() }}
        </div>
    </div>

</div>

<script>
    $(document).ready( function(){
        setTimeout(() => {
            $('#error,#success').hide(400);
        }, 300);
    })
</script>
@endsection
