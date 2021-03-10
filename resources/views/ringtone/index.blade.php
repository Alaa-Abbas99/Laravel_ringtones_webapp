@extends('layouts.app')

@section('content')

@if(Session::has('message'))
        <div class="alert alert-success">{{Session::get('message')}}</div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">All Ringtones</div>
                    <a href="{{route('ringtones.create')}}"> Create Ringtone</a>

                <div class="card-body">
                   


                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">File</th>
                            <th scope="col">Category</th>
                            <th scope="col">Download</th>
                            <th scope="col">size</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($ringtones as $key =>$ringtone)
                            <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$ringtone->title}}</td>
                            <td>{{$ringtone->description}}</td>
                            
                            <td>
                                <audio controls onplay="pauseOthers(this);">
                                     <source src="/audio/{{$ringtone->file}}" type="audio/ogg" >
                                </audio>
                            </td>
                            <td>{{$ringtone->category->name}}</td>
                            <td>{{$ringtone->download}}</td>
                            <td>{{$ringtone->size}}</td>
                            <td>
                                <a href="{{route('ringtones.edit', [$ringtone->id])}}"> Edit </a>
                            </td>
                            <td>
                               <form action="{{route('ringtones.destroy', [$ringtone->id])}}" method="post" onSubmit="return confirm('Confirm Delete')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"> Delete </button>
                               </form>
                            </td>
                            
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{$ringtones->links()}}
        </div>
    </div>
</div>



@endsection