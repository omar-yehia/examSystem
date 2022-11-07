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
<h1>Student's Exams</h1>
    <div class="table-responsive">
        <table class="table">
            <caption>List of Exams</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Total Score</th>
                    <th scope="col">Time</th>
                    <th scope="col">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->exams as $exam)
                <tr>
                    <th scope="row">{{$exam->id}}</th>
                    <td>{{$exam->title}}</td>
                    <td>{{$exam->total_score}}</td>
                    <td>{{date('H:i', mktime(0,$exam->time))}} Hours</td>
                    <td>
                        @if($exam->status=='ended')
                        Score {{$exam->score}}/{{$exam->total_score}}
                        @elseif($exam->status=='time over')Time Over
                        @elseif($exam->status=='ongoing')OnGoing
                        @else Not taken
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper">
         {{ $student->exams->links() }}
        </div>
    </div>

</div>

<script>
    $(document).ready( function(){
        setTimeout(() => {
            $('#error,#success').hide(400);
        }, 2000);
    })
</script>
@endsection
