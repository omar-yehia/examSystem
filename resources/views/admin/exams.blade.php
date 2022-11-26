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
            <li class="breadcrumb-item active"><a href="{{route('exams')}}">Exams</a></li>
        </ol>
    </nav>
    <h1>Add Exam</h1>
    <form method="POST" action="{{route('saveExam')}}">
        {{csrf_field()}}
        <label for="title">Title</label>
        <input id="title" class="form-control" name="title" placeholder="exam title" required>
        
        <label for="total_score">Total Grade</label>
        <input id="total_score" class="form-control" name="total_score" type="number" min="1" required>

        <label for="time">Total Time (in minutes)</label>
        <input id="time" class="form-control" name="time" type="number" min="1" required>

        <button class="btn btn-primary">Save</button>
    </form>
    <hr>
    <br>
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
                    <td>
                        <a class="btn btn-primary" href="{{route('exam',['id'=>$exam->id])}}">questions</a>
                        <a class="btn btn-danger" href="{{route('deleteExam',['id'=>$exam->id])}}" onclick="if(!confirm('are you sure you want to delete this exam?')) return false;">delete</a>
                        <a class="btn btn-info" href="{{route('togglePublishExam',['id'=>$exam->id])}}" onclick="if(!confirm('are you sure you want to {{$exam->is_published==0? "publish":"unpublish"}} this exam?')) return false;">{{$exam->is_published==0?'publish':'unpublish'}}</a>
                    </td>


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
    $("body").on("submit", "form", function() {
        $(this).submit(function() {
            return false;
        });
        return true;
    });
    $(document).ready( function(){
        $('#title').focus();
        setTimeout(() => {
            $('#error,#success').hide(400);
        }, 300);
    })
</script>
@endsection
