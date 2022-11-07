@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row ">
        <a class="btn btn-success" href="{{route('exams')}}">Exams</a>

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
                @foreach($students as $student)
                <tr>
                    <th scope="row">{{$student->id}}</th>
                    <td>{{$student->name}}</td>
                    <td>{{$student->email}}</td>
                    <td><a class="btn btn-primary" href="{{route('student',['id'=>$student->id])}}">exams</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper">
         {{ $students->links() }}
        </div>
    </div>

    </div>
</div>
@endsection
