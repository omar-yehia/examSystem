@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <form method="POST" action="{{route('saveExam')}}">
            <input name="title" placeholder="exam title" required>
            <input name="total_score" type="number" min="1" required>
        </form>
    </div>
</div>
@endsection
