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
                    <form action="{{route('appUser.update',$appUser->id)}}" method="post" enctype="multipart/form-data" class="login100-form validate-form">
                        @csrf
                        <div class="row row-sm">
                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="avatar">{{ __('app_users.image') }}</label>
                                <input type="file" name="avatar" id="avatar" class="form-control">
                                @error('avatar')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="first_name">{{ __('app_users.first_name') }}  :</label>
                                <input value="{{$appUser->first_name}}" id="first_name" type="text" class="form-control " name="first_name" >
                                @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="last_name">{{ __('app_users.last_name') }}  :</label>
                                <input value="{{$appUser->last_name}}" id="last_name" type="text" class="form-control " name="last_name" >
                                @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="email">{{ __('app_users.email') }}  :</label>
                                <input value="{{$appUser->email}}" id="email" type="text" class="form-control " name="email" >
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
{{--                            <div class="form-group col-md-6 has-success mg-t-10">--}}
{{--                                <label for="work_hours">{{ __('app_users.work_hours') }}  :</label>--}}
{{--                                <input value="{{$appUser->work_hours}}" id="work_hours" type="text" class="form-control " name="work_hours" >--}}
{{--                                @error('work_hours')--}}
{{--                                <span class="text-danger">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}

                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="start_time">{{ __('app_users.start_time') }} :</label>
                                <input value="{{  $appUser->work_hours ? ( $appUser->work_hours != 'null' ?   \Carbon\Carbon::createFromFormat('h:i A', explode(',', $appUser->work_hours)[0])->format('H:i') : null)  : null}}" id="start_time" type="time" class="form-control" name="start_time">
                                @error('start_time')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="end_time">{{ __('app_users.end_time') }} :</label>
                                <input value="{{ $appUser->work_hours ?  ( $appUser->work_hours != 'null' ? \Carbon\Carbon::createFromFormat('h:i A', explode(',', $appUser->work_hours)[1])->format('H:i') : null )  : null}}" id="end_time" type="time" class="form-control" name="end_time">
                                @error('end_time')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="work_hours2">{{ __('app_users.work_hours2') }}  :</label>
                                <input value="{{$appUser->work_hours2}}" id="work_hours2" type="text" class="form-control " name="work_hours2" >
                                @error('work_hours2')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="mobile_number">{{ __('app_users.mobile_number') }}  :</label>
                                <input value="{{$appUser->mobile_number}}" id="mobile_number" type="text" class="form-control " name="mobile_number" >
                                @error('mobile_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if($appUser->type == 2)
                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="mobile_number">{{ __('app_users.land_number') }}  :</label>
                                <input value="{{$appUser->land_number}}" id="land_number" type="text" class="form-control " name="land_number" >
                                @error('land_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                                <div class="form-group col-md-6 has-success mg-t-10">
                                    <label for="mobile_number">{{ __('app_users.whats_number') }}  :</label>
                                    <input value="{{$appUser->whats_number}}" id="whats_number" type="text" class="form-control " name="whats_number" >
                                    @error('whats_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            @endif
                            @if($appUser->type == 2)
                                <div class="form-group col-md-6  mg-t-10">
                                    <label for="category">{{ __('app_users.category') }} :</label>
                                    <select name="category" class="form-control">
                                        <option value=""> </option>
                                    @foreach($categories as $cat)
                                            <option value="{{$cat->id}}" @if($appUser->cat_id == $cat->id) selected @endif>
                                                @if(App::getLocale() == 'en')
                                                    {{$cat->title_en}}
                                                @else
                                                    {{$cat->title_ar}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group col-md-6  mg-t-10">
                                <label for="status">{{ __('app_users.status') }} :</label>
                                <select name="status" class="form-control">
                                    <option value="active" @if($appUser->status === 'active') selected @endif>{{ __('app_users.active') }}</option>
                                    <option value="pending_activation" @if($appUser->status === 'pending_activation') selected @endif>{{ __('app_users.Inactive') }}</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6  mg-t-10">
                                <label for="type">{{ __('app_users.type') }} :</label>
                                <select name="type" class="form-control">
                                    <option value="1" @if($appUser->type === 1) selected @endif>{{ __('app_users.Individual account') }}</option>
                                    <option value="2" @if($appUser->type === 2) selected @endif>{{ __('app_users.Business account') }}</option>
                                </select>
                                @error('type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 has-success mg-t-10">
                                <label for="address">{{ __('app_users.address') }}  :</label>
                                <input value="{{$appUser->address}}" id="address" type="text" class="form-control " name="address" >
                                @error('address')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if($appUser->type === 2)
                                <div class="form-group col-md-6 has-success mg-t-10">
                                    <label for="commercial_name">{{ __('app_users.commercial_name') }}  :</label>
                                    <input value="{{$appUser->commercial_name}}" id="commercial_name" type="text" class="form-control " name="commercial_name" >
                                    @error('commercial_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="form-group  col-md-6 has-success mg-t-10">
                                <label for="country_id">{{ __('app_users.country_id') }}  :</label>
                                <select name="country_id" id="country_id" class="form-control">
                                    <option value=""> </option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" @if($appUser->country_id == $country->id) selected @endif>
                                            @if(App::getLocale() == 'en')
                                                {{$country->title_en}}
                                            @else
                                                {{$country->title_ar}}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 has-success mg-t-10">
                                <label for="holiday">{{ __('app_users.holiday') }}  :</label>
                                <select name="holiday[]" id="holiday" class="form-control" multiple>
                                    <option value="1" {{in_array(1,$appUser->holiday_arr) ? 'selected' : ''}}>سبت</option>
                                    <option value="2" {{in_array(2,$appUser->holiday_arr) ? 'selected' : ''}}>احد</option>
                                    <option value="3" {{in_array(3,$appUser->holiday_arr) ? 'selected' : ''}}>اثنين</option>
                                    <option value="4" {{in_array(4,$appUser->holiday_arr) ? 'selected' : ''}}>ثلاثاء</option>
                                    <option value="5" {{in_array(5,$appUser->holiday_arr) ? 'selected' : ''}}>أربعاء</option>
                                    <option value="6" {{in_array(6,$appUser->holiday_arr) ? 'selected' : ''}}>خميس</option>
                                    <option value="7" {{in_array(7,$appUser->holiday_arr) ? 'selected' : ''}}>جمعه</option>
                                </select>

                                @error('holidays')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12 has-success mg-t-10">
                                <label for="description">{{ __('app_users.description') }}  :</label>
                                <textarea id="description" type="text" class="form-control " name="description" >{{$appUser->description}}</textarea>
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <div class="form-group col-md-12 has-success mg-t-20">
                                    <button type="submit" class="btn btn-primary" >{{ __('category.Save') }}</button>
                                </div>
                            </div>
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
