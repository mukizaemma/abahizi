@extends('layouts.adminbase')

@section('title', 'Background')

@section('sidebar')

    @parent

@endsection

@section('content')

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        @include('admin.includes.sidenav')
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2  class="btn btn-primary">Our Background</h2>
                                    @if(session()->has('success'))
                                    <div class="arlert alert-success">
                                        <button class="close" type="button" data-dismiss="alert">X</button>
                                        {{ session()->get('success') }}
                                    </div>

                                    @endif
                                </div>
                                <!-- ./card-header -->
                                <div class="card-body">
                                    <form class="form" action="{{ route('saveBackg',$data->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="projectinput1">About Us Details</label>
                                                    <textarea id="background" rows="10" class="form-control" name="description" data-editor="rich">{!!$data->description!!}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="projectinput1">About Donations at Abahizi Rwanda</label>
                                                    <textarea id="background" rows="10" class="form-control" name="donations" data-editor="rich">{!!$data->donations!!}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                        <hr class="my-4">
                                        <h5 class="mb-3">Our Factory Content</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Factory page description</label>
                                                    <textarea rows="6" class="form-control" name="factory_description" data-editor="rich">{!! $data->factory_description !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Factory services</label>
                                                    <textarea rows="5" class="form-control" name="factory_services" data-editor="rich">{!! $data->factory_services !!}</textarea>
                                                    <small class="text-muted d-block mt-1">Commas/new lines/bullets supported.</small>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label>Factory services image</label>
                                                    <input type="file" class="form-control" name="factory_services_image" accept="image/*">
                                                    @if(!empty($data->factory_services_image))
                                                        <img src="{{ asset('storage/images/' . $data->factory_services_image) }}" width="140" class="mt-2 rounded border p-1 bg-white">
                                                    @endif
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label>Factory services sub-items</label>
                                                    <textarea rows="4" class="form-control" name="factory_services_subitems" placeholder="One sub-item per line, bullet, or comma">{{ old('factory_services_subitems', $data->factory_services_subitems ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Community impact</label>
                                                    <textarea rows="5" class="form-control" name="factory_community_impact" data-editor="rich">{!! $data->factory_community_impact !!}</textarea>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label>Community impact image</label>
                                                    <input type="file" class="form-control" name="factory_community_impact_image" accept="image/*">
                                                    @if(!empty($data->factory_community_impact_image))
                                                        <img src="{{ asset('storage/images/' . $data->factory_community_impact_image) }}" width="140" class="mt-2 rounded border p-1 bg-white">
                                                    @endif
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label>Community impact sub-items</label>
                                                    <textarea rows="4" class="form-control" name="factory_community_impact_subitems" placeholder="One sub-item per line, bullet, or comma">{{ old('factory_community_impact_subitems', $data->factory_community_impact_subitems ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Training facilities offered</label>
                                                    <textarea rows="5" class="form-control" name="factory_training_facilities" data-editor="rich">{!! $data->factory_training_facilities !!}</textarea>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label>Training facilities image</label>
                                                    <input type="file" class="form-control" name="factory_training_facilities_image" accept="image/*">
                                                    @if(!empty($data->factory_training_facilities_image))
                                                        <img src="{{ asset('storage/images/' . $data->factory_training_facilities_image) }}" width="140" class="mt-2 rounded border p-1 bg-white">
                                                    @endif
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label>Training facilities sub-items</label>
                                                    <textarea rows="4" class="form-control" name="factory_training_facilities_subitems" placeholder="One sub-item per line, bullet, or comma">{{ old('factory_training_facilities_subitems', $data->factory_training_facilities_subitems ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-sm-12">
                                                    <label>About Cover Image </label><br>
                                                    <label id="projectinput7" class="file center-block">
                                                      <img src="{{ asset('storage/images/' . $data->image) }}" width="150">

                                                    </label>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <label>Change the about Image</label>
                                                    <label id="projectinput7" class="file center-block">
                                                        <input type="file" id="image" name="image">
                                                        <span class="file-custom"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-sm-12">
                                                    <label>Home Back Image </label><br>
                                                    <label id="projectinput7" class="file center-block">
                                                        <img src="{{ asset('storage/images/' . $data->image1) }}" width="150">

                                                    </label>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <label>Change the home back Image</label>
                                                    <label id="projectinput7" class="file center-block">
                                                        <input type="file" id="image" name="image1">
                                                        <span class="file-custom"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-sm-12">
                                                    <label>Pages Header Image </label><br>
                                                    <label id="projectinput7" class="file center-block">
                                                      <img src="{{ asset('storage/images/' . $data->image2) }}" width="150">

                                                    </label>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <label>Change the pages header Image</label>
                                                    <label id="projectinput7" class="file center-block">
                                                        <input type="file" id="image" name="image2">
                                                        <span class="file-custom"></span>
                                                    </label>
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
                            </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->


                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
        </main>
        @include('admin.includes.footer')
    </div>
</div>

@section('scripts')

<script src="{{asset('assets')}}/js/summernote.js"></script>

@endsection
