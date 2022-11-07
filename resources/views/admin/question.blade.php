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
    <li class="breadcrumb-item"><a href="{{route('exam',['id'=>$question->exam->id])}}">{{$question->exam->title}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('question',['id'=>$question->id])}}">{{$question->content}}</a></li>
  </ol>
</nav>
    
    <h3>Add Answer</h3>
        
        <form method="POST" action="{{route('saveAnswer')}}">
            {{csrf_field()}}
            <input hidden name="exam_id" value="{{$question->exam->id}}" required>
            <input hidden name="question_id" value="{{$question->id}}" required>

            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" placeholder="answer content" required></textarea>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_correct" id="is_correct">
                <label class="form-check-label" for="is_correct">is correct</label>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
        <hr>
        <br>
    <h3>All Answers</h3>
    <div class="table-responsive">
        <table class="table">
            <caption>List of Answers</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Content</th>
                    <th scope="col">Is correct</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($question->answers as $answer)
                <tr>
                    <th scope="row">{{$answer->id}}</th>
                    <td>{{$answer->content}}</td>
                    <td>{{$answer->is_correct?'Yes':''}}</td>
                    <!-- <td><a class="btn btn-primary" href="{{route('answer',['id'=>$answer->id])}}">answers</a></td> -->
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper">
         {{ $question->answers->links() }}
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
