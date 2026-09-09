@extends('layouts.master')
@section('css')

    @section('title')
        المستخدمين
    @stop

    <!-- Internal Data table css -->

    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
{{--                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4>--}}
{{--                <span class="text-muted mt-1 tx-13 mr-2 mb-0"> /--}}
{{--            {{ trans('payment.content_title')}}</span>--}}
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('danger'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" style="margin: 15px">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                </div>
                <div class="card-body">
                    <form action="{{route('account_transfer_store')}}" method="post" enctype="multipart/form-data" class="login100-form validate-form">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$id}}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">الاسم التجاري :</label>
                                <input type="text" class="form-control" name="commercail_name" value="{{\Illuminate\Support\Facades\Request::old('first_name') }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">الايميل :</label>
                                <input type="text" class="form-control" name="email" value="{{ old('email' , '') }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">القسم :</label>
                                <select name="cat_id"  class="form-control">
                                    @foreach($cat as $value)
                                    <option value="{{ $value->id }}">
                                        @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                            {{ $value->title_en }}
                                        @else
                                            {{ $value->title_ar }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="EditClient">{{ trans('category.Save') }}</button>
                            <a type="button" class="btn btn-secondary" href="{{ url(app()->getLocale().'/admin/appUser?type=2') }}">{{ trans('category.Close') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script>
    <!-- Internal Nice-select js-->
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>
@endsection
