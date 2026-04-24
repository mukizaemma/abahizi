@extends('layouts.adminbase')

@section('title', 'Edit Events')

@section('sidebar')

    @parent

@endsection

@section('content')

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <div class="card-header">
            <a href="{{route('events')}}" class="btn btn-primary">Back</a>
            @if(session()->has('success'))
            <div class="arlert alert-success">
                <button class="close" type="button" data-dismiss="alert">X</button>
                {{ session()->get('success') }}
            </div>

            @endif
        </div>
        <main>
            <div class="container-fluid px-4">
                <div class="row">

                </div>

                <div class="card mb-4">

                    <div class="card-body">
                        <form class="form" action="{{ route('updateEvent', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row mb-4">

                                <div class="col-lg-8 col-sm-12">
                                        <label for="projectinput1">Event Title</label>
                                        <input type="text" class="form-control" value="{{$data->title}}" name="title">

                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" value="{{$data->location}}" name="location">
                                </div>
                            </div>

                                <div class="row mb-4">
                                    <div class="col-lg-4 col-sm-12">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" value="{{$data->date}}" name="date">
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <label for="time">Start Time</label>
                                        <input type="time" class="form-control" value="{{$data->timeStart}}" name="timeStart">
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <label for="time">End Time</label>
                                        <input type="time" class="form-control" value="{{$data->timeEnd}}" name="timeEnd">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-6 col-sm-12">
                                        <label for="date">Registration Link</label>
                                        <input type="text" class="form-control" value="{{$data->registerLink}}" name="registerLink">
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <label for="time">Registration Contact</label>
                                        <input type="text" class="form-control" value="{{$data->registerContact}}" name="registerContact">
                                    </div>

                                </div>

                            <div class="form-group">
                                <label for="projectinput8">Event Description</label>
                                <textarea id="ProgramDescription" rows="5" class="form-control" name="description" data-editor="rich">{!! $data->description !!}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                        <label>Featured Event Banner</label>
                                        <label id="projectinput7" class="file center-block">
                                            <img src="{{asset('storage/images/events').$data->image}}" alt="" width="120px">
                                        </label>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                        <label>Select Event Banner <br><span style="color: red">(This image should be resized to 540x600 pixels)</span></label>
                                        <label id="projectinput7" class="file center-block">
                                            <input type="file" id="image" name="image">
                                            <span class="file-custom"></span>
                                        </label>
                                </div>


                                <div class="col-lg-4 col-sm-12">
                                        <label for="projectinput1">Status</label>
                                        <select class="form-control select2" name="status"
                                            style="...">
                                            <option value="" disabled selected>{{ $data->status }}</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                </div>

                            </div>

                        </div>

                        <div class="form-actions mt-5">
                            <button type="submit" class="btn btn-primary text-black">
                                <i class="fa fa-save"></i> Save Changes
                            </button>

                        </div>
                    </form>
                    </div>
                </div>


            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

@section('scripts')

<script src="{{asset('assets')}}/js/summernote.js"></script>
@endsection
