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
    <div class="row justify-content-center">
        <form method="POST" action="{{route('saveExam')}}">
            {{csrf_field()}}
            <label for="title">Title</label>
            <input id="title" name="title" placeholder="exam title" required>
            
            <label for="total_score">Total Grade</label>
            <input id="total_score" name="total_score" type="number" min="1" required>

            <label for="time">Total Time (in minutes)</label>
            <input id="time" name="time" type="number" min="1" required>

            <button class="btn btn-primary">Save</button>
        </form>
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
