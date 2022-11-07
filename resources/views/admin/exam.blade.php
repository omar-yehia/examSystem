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
    <li class="breadcrumb-item"><a href="{{route('exam',['id'=>$exam->id])}}">{{$exam->title}}</a></li>
    <!-- <li class="breadcrumb-item"><a href="#">Questoin Name</a></li> -->
  </ol>
</nav>
       
        <h3>Add Question</h3>
        <form method="POST" action="{{route('saveQuestion')}}">
            {{csrf_field()}}
            <input hidden name="exam_id" value="{{$exam->id}}" required>

            <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" rows="4" cols="5" id="content" name="content" placeholder="question content" required></textarea>
</div>
            <label for="score">score</label>
            <input id="score" name="score" type="number" min="1" required>

            <button class="btn btn-primary">Save</button>
        </form>
        <hr>
        <br>
    <h3>All Questions</h3>
    <div class="table-responsive">
        <table class="table">
            <caption>List of Questions</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Score</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam->questions as $question)
                <tr>
                    <th scope="row">{{$question->id}}</th>
                    <td>{{$question->content}}</td>
                    <td>{{$question->score}}</td>
                    <td><a class="btn btn-primary" href="{{route('question',['id'=>$question->id])}}">answers</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper">
         {{ $exam->questions->links() }}
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
        setTimeout(() => {
            $('#error,#success').hide(400);
        }, 300);
    })
</script>
@endsection
