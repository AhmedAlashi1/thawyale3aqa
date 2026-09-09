@extends('layouts.master')
@section('css')

    @section('title')
        المستخدمين
    @stop

    <!-- Internal Data table css -->

    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>


@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('app_users.Home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                {{ trans('app_users.app') }}</span>
            </div>

        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="main-body">
        <div id="error_message"></div>
        <div class="modal" id="modaldemo8" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('admins.dele') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ trans('admins.aresure') }}</p><br>
                        <input class="form-control" name="usernamed" id="usernamed" type="text" readonly="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ trans('admins.close') }}</button>
                        <button type="submit" class="btn btn-danger" id="delete">{{ trans('admins.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalAddcredit">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('app_users.app') }}</h6>
                        <button aria-label="Close" class="close"
                                data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formcategorys" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="id" id="idid">
                                <div class="form-group col-md-12">
                                    <label for="credit">{{ trans('app_users.charged_balance') }}</label>
                                    <input type="number" class="form-control" id="credit" name="credit">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="note">{{ trans('app_users.Notes') }}</label>
                                    <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success AddCategory"
                                        id="Addcredit1">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ trans('category.Close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalphone">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('app_users.app') }}</h6>
                        <button aria-label="Close" class="close"
                                data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formphone">
                            <div class="row">
                                <input type="hidden" name="id" id="id_phone">
                                <div class="form-group col-md-12">
                                    <label for="credit">{{ trans('app_users.name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="credit">{{ trans('app_users.mobile') }}</label>
                                    <input type="number" class="form-control" id="mobile_number" name="mobile_number">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="credit">{{ trans('app_users.cat_name') }}</label>

                                    <select class="form-control form-control-md mg-b-20" id="cat_id" name="cat_id">
                                        <option value="null"></option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->title_ar}}</option>
                                        @endforeach

                                    </select>
{{--                                    <input type="number" class="form-control" id="cat_id" name="cat_id">--}}
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"
                                        id="Addphone">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ trans('category.Close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modaldemo100" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('admins.dele') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                    aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ trans('admins.aresure') }}</p><br>
                        <input class="form-control" name="usernamed" id="usernamed" type="text" readonly=""
                               value="{{ trans('orders.Delete_all') }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ trans('admins.close') }}</button>
                        <button type="submit" class="btn btn-danger" id="dletet100">{{ trans('admins.save') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Order Management</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="payment/update" method="post" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="patch">
                            <input type="hidden" name="_token" value="o2RMVxMUvjafmzNYvClExYr7nxek043oY40uUqoM">
                            <input type="hidden" name="id" id="id" value="">
                            <div class="row">
                                <table class="table table-hover" id="example1" data-page-length="50"
                                       style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th>العنوان</th>
                                        <th>الوصف</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <td id="Numberorders"></td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('app_users.activation_code') }}</th>
                                        <td id="Totalinvoices"></td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('app_users.mobile') }}</th>
                                        <td id="pieces"></td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('app_users.name') }}</th>
                                        <td id="date"></td>
                                    </tr>

                                    <tr>
                                        <th>عدد الاعلانات</th>
                                        <td id="numOfAds"></td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('clothes.views') }}</th>
                                        <td id="views"></td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('app_users.connection') }}</th>
                                        <td id="connection"></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @can('customer-view')
            <div class="row row-sm">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('appUser.export') }}" method="get">
                                @csrf
                                <div class="row mg-b-20">
                                    <div class="parsley-input col-md-3" id="fnWrapper">
                                        <label for="exampleInputEmail1">{{ trans('app_users.name') }} :</label>
                                        <input type="text" class="form-control" id="named" name="name">
                                    </div>
                                    @if($type == 2 || $type == 3)
                                    <div class="parsley-input col-md-3" id="fnWrapper">
                                        <label for="exampleInputEmail1">{{ trans('app_users.commercial_name') }} :</label>
                                        <input type="text" class="form-control" id="commercial_name" name="commercial_name">
                                    </div>
                                    @endif
                                    <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                        <label>{{trans('orders.phone')}} :</label>
                                        <input type="number" class="form-control form-control-md mg-b-20" name="phone"
                                               id="phoned">
                                    </div>
                                    <div class="parsley-input col-md-3" id="fnWrapper">
                                        <label for="exampleInputEmail1">{{ trans('app_users.country') }} :</label>
                                        <input type="text" class="form-control" id="Countryd" name="Country">
                                    </div>
                                    <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                        <label>{{trans('category.Status')}} :</label>
                                        <select class="form-control form-control-md mg-b-20" id="statusd" name="status">
                                            <option value="">{{ trans('orders.all') }}</option>
                                            <option value="active">{{ trans('category.Active') }}</option>
                                            <option value="inactive">{{ trans('category.iActive') }}</option>
                                            <option value="pending_activation">{{ trans('app_users.pActive') }}</option>
                                        </select>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary"
                                        id="s">{{ trans('orders.Sarech') }}</button>
                                @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                    <button type="submit" class="btn btn-success float-right">Excel</button>
                                @else
                                    <button type="submit" class="btn btn-success float-left">Excel</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        <div class="row">
            <div class="col-xl-12">
                <div class="card-header">
                    <button class="btn btn-danger" id="Delete_all"><i
                                class="las la-clipboard"> {{ trans('orders.Delete_all') }} </i></button>
                    <button class="btn btn-success" id="Activate_all"><i
                                class="las la-clipboard">  {{ trans('orders.Activate_all') }}  </i>
                    </button>
{{--                    <button class="btn btn-info" id="Order_processing"><i--}}
{{--                                class="las la-clipboard">{{ trans('orders.Order_processing') }}</i>--}}
{{--                    </button>--}}
{{--                    <button class="btn btn-purple" id="Cancelling_order"><i--}}
{{--                                class="las la-clipboard"> {{ trans('orders.cancel_order') }}  </i>--}}
{{--                    </button>--}}
{{--                    <button class="btn btn-success-gradient" id="Charged"><i--}}
{{--                                class="las la-clipboard"> {{ trans('orders.Charged') }}</i></button>--}}
{{--                    <button class="btn btn-success" id="Receipt_confirmed"><i class="las la-clipboard">--}}
{{--                            {{ trans('orders.Receipt_confirmed') }}</i></button>--}}
                </div>

                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('customer-view')
                                <table class="table table-hover" id="get_appUser" style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{ trans('app_users.name') }}</th>
                                        @if($type == 2 || $type == 3)
                                            <th class="border-bottom-0">{{ trans('app_users.commercial_name') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.land_number') }}</th>
                                        @endif
                                        <th class="border-bottom-0">{{ trans('app_users.cat_name') }}</th>
                                        {{--                                        <th class="border-bottom-0">{{ trans('app_users.email') }}</th>--}}
                                        <th class="border-bottom-0">{{ trans('app_users.mobile') }}</th>
                                        <th class="border-bottom-0">{{ trans('app_users.country') }}</th>
{{--                                        <th class="border-bottom-0">{{ trans('app_users.charged_balance') }}</th>--}}
{{--                                        <th class="border-bottom-0">عدد الاعلانات</th>--}}
{{--                                        <th class="border-bottom-0">{{ trans('clothes.views') }}</th>--}}
{{--                                        <th class="border-bottom-0">{{ trans('app_users.connection') }}</th>--}}

                                        <th class="border-bottom-0">الجهاز</th>
                                        <!-- <th class="border-bottom-0">توثيق</th> -->
                                        <th class="border-bottom-0">{{ trans('app_users.status') }}</th>
                                        <th class="border-bottom-0">{{ trans('app_users.activation_code') }}</th>
                                        <!-- <th class="border-bottom-0">{{ trans('app_users.expiration_date') }}</th> -->
                                        <th class="border-bottom-0">{{ trans('app_users.created_at') }}</th>
                                        <th class="border-bottom-0">
                                            @canany([ 'customer-update' , 'customer-delete' ])
                                                {{ trans('category.Processes') }}
                                            @endcanany
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                </table>
                            @endcan
                        </div>
                    </div>
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
    <script>
        var local = "{{ App::getLocale() }}";
        var type = "{{$type}}";
        if (type != 1){
            $('#s').click(function (e) {
                e.preventDefault();
                var name = $('#named').val();
                var commercial_name = $('#commercial_name').val();
                var phoned = $('#phoned').val();
                var creditd = $('#creditd').val();
                var ids = $('#ids').val();
                var credit_operation = $('#credit_operation').val();
                var status = $('#statusd').val();
                var country = $('#Countryd').val();
                var to = $('#to').val();
                var from = $('#from').val();
                var lang = local;
                $('#get_appUser').DataTable({
                    ordering: false,
                    bDestroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    pageLength: 20,
                    lengthMenu: [
                        [10, 50 , 200 , 500 , 1000 ,  -1],
                        [10, 50 , 200 , 500 , 1000],
                    ],
                    ajax: {
                        url: '{{ url("admin/appUser/get") }}' + '/?name=' + name
                            + '&commercial_name=' + commercial_name
                            + '&phone=' + phoned + '&status=' + status
                            + '&country=' + country + '&type=' + @json($type)   ,
                        cache: true
                    },
                    columns: [
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                return `<input type="checkbox" name="check" class="che" value="${data.id}"/>`;
                            },
                        },
                        {
                            'data': 'id',
                            'className': 'text-center text-lg text-medium'
                        },
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                if(data.first_name){
                                    return `
                            {{--<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>--}}
                                    <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.first_name ?? "" + " " + data.last_name ?? ""} </a>
                            `;
                                }
                                // return data.first_name ?? "" + " " + data.last_name ?? "";
                            },
                        },
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                if(data.type != 1){
                                    if(data.commercial_name){
                                        return `
                                        <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.commercial_name} </a>
                            `;

                                    }else {
                                        return "";
                                    }
                                }
                            },
                        },
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                if(data.type != 1){
                                    if(data.land_number){
                                        return data.land_number;
                                    }else {
                                        return "";
                                    }
                                }
                            },
                        },
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data) {
                                if(data.categories){
                                    if (local == 'en') {
                                        return data.categories.title_en;
                                    }else {
                                        return data.categories.title_ar;
                                    }
                                }else {
                                    return 'null'
                                }
                            },
                        },
                        {
                            'data': 'mobile_number',
                        },
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                if (local == 'en') {
                                    if (data.country) {
                                        return data.country.title_en ?? "";
                                    } else {
                                        return "";
                                    }
                                } else {
                                    if (data.country) {
                                        return data.country.title_ar ?? "";
                                    } else {
                                        return "";
                                    }
                                }
                            },
                        },
                        // {
                        //     'data': null,
                        //     'className': 'text-center text-lg text-medium',
                        //     render: function (data) {
                        //         return data.clothes_count
                        //     },
                        //
                        // },
                        // {
                        //     'data': 'views',
                        //     "searchable": false ,
                        //     'className': 'text-center text-lg text-medium'
                        // },
                        // {
                        //     'data': 'connection',
                        //     "searchable": false ,
                        //     'className': 'text-center text-lg text-medium'
                        // },
                        {
                            'data': 'device_type',
                            "searchable": false ,
                            'className': 'text-center text-lg text-medium'
                        },
                        //

                        // {
                        //     'data': null,
                        //     render: function (data, row, type) {
                        //         var phone;
                        //         if (data.automatic_acceptance == '1') {
                        //             return `<button class="btn btn-success-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'موثق' }}</button>`;

                        //         } else  {
                        //             return `<button class="btn btn-danger-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'غير موثق' }}</button>`;
                        //         }
                        //     },
                        // },
                        {
                            'data': null,
                            render: function (data, row, type) {
                                var phone;
                                if (data.status == 'active') {
                                    return `<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;

                                } else if (data.status == 'inactive') {
                                    return `<button class="btn btn-danger-gradient btn-block" id="inactive" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                                } else {
                                    return `<button class="btn btn-warning-gradient btn-block" id="pending_activation" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('app_users.pActive') }}</button>`;
                                }
                            },
                        },
                        {
                            'data': null,
                            render: function (data, row, type) {
                                return data.activation_code ?? "";
                            },
                        },
                        // {
                        //     'data': null,
                        //     render: function (data, row, type) {
                        //         return data.exp_at ?? "حساب جديد غير فعال";
                        //     },
                        // },




                        {
                            'data': 'created_at',
                            'className': 'text-center text-lg text-medium',
                            render: function (data) {
                                if (data == null) return "";
                                var date = new Date(data);
                                var month = date.getMonth() + 1;
                                return date.toLocaleString();
                                // return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" +
                                //     month) + "/" + date.getFullYear();
                            }
                        },
                        {
                            'data': null,
                            render: function (data, row, type) {
                                return `
                        @can('customer-view')
                                <button class="btn btn-success btn-sm" id="Management" data-id="${data.id}" data-created="${data.activation_code}" data-payment="${data.mobile_number}"
                            data-address="${data.first_name + " " + data.last_name}" data-delivery_type="${data.credit}" data-count="${data.orders_count ?? 0}" data-sum="${data.orders_sum_total_cost ?? 0}"><i class="fa fa-clipboard"></i> {{ trans('orders.Details') }}</button>
                            {{--<a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/app_user_address') }}/${data.id}" class="btn btn-purple btn-sm "><i class="fa fa-bars"></i>  {{ trans('orders.addresses') }}</a>--}}
                                @endcan
                                @can('customer-update')
                                {{--<button class="modal-effect btn btn-sm btn-info" id="Addcredit" data-id="${data.id}"><i class="fa fa-cogs"></i> {{ trans('app_users.Add_Credit') }}</button>--}}
                                <a href="appUser/edit/${data.id}" class="modal-effect btn btn-sm btn-info">
                                <i class="las la-pen"></i>
                            </a>
                        @endcan
                                @can('customer-delete')
                                <button class="modal-effect btn btn-sm btn-danger" id="DeleteappUser" data-id="${data.id}" data-namee="${data.first_name}"><i class="las la-trash"></i></button>
                        @endcan
                                `;
                            },
                        },
                    ],
                });

            });
            var table = $('#get_appUser').DataTable({
                ordering: false,
                bDestroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 20,
                lengthMenu: [
                    [10, 50 , 200 , 500 , 1000 ,  -1],
                    [10, 50 , 200 , 500 , 1000],
                ],
                ajax: '{{ route("get_appUser") .'?type='.$type}}',
                columns: [
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return `<input type="checkbox" name="check" class="che" value="${data.id}"/>`;
                        },
                    },
                    {
                        'data': 'id',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if(data.first_name){
                                return `
                            {{--<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>--}}
                                <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.first_name ?? "" + " " + data.last_name ?? ""} </a>
                            `;
                            }
                            // return data.first_name ?? "" + " " + data.last_name ?? "";
                        },
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if(data.type != 1){
                                if(data.commercial_name){
                                    return `
                            {{--<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>--}}
                                    <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.commercial_name} </a>
                            `;

                                }else {
                                    return "";
                                }
                            }
                        },
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if(data.type != 1){
                                if(data.land_number){
                                    return data.land_number;
                                }else {
                                    return "";
                                }
                            }
                        },
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data) {
                            if(data.categories){
                                if (local == 'en') {
                                    return data.categories.title_en;
                                }else {
                                    return data.categories.title_ar;
                                }
                            }else {
                                return 'null'
                            }
                        },
                    },
                    {
                        'data': 'mobile_number',
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if (local == 'en') {
                                if (data.country) {
                                    return data.country.title_en ?? "";
                                } else {
                                    return "";
                                }
                            } else {
                                if (data.country) {
                                    return data.country.title_ar ?? "";
                                } else {
                                    return "";
                                }
                            }
                        },
                    },
                    // {
                    //     'data': null,
                    //     'className': 'text-center text-lg text-medium',
                    //     render: function (data) {
                    //         return data.clothes_count
                    //     },
                    //
                    // },
                    // {
                    //     'data': 'views',
                    //     "searchable": false ,
                    //     'className': 'text-center text-lg text-medium'
                    // },
                    // {
                    //     'data': 'connection',
                    //     "searchable": false ,
                    //     'className': 'text-center text-lg text-medium'
                    // },
                    {
                        'data': 'device_type',
                        "searchable": false ,
                        'className': 'text-center text-lg text-medium'
                    },
                    //

                    // {
                    //     'data': null,
                    //     render: function (data, row, type) {
                    //         var phone;
                    //         if (data.automatic_acceptance == '1') {
                    //             return `<button class="btn btn-success-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'موثق' }}</button>`;

                    //         } else  {
                    //             return `<button class="btn btn-danger-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'غير موثق' }}</button>`;
                    //         }
                    //     },
                    // },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            var phone;
                            if (data.status == 'active') {
                                return `<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;

                            } else if (data.status == 'inactive') {
                                return `<button class="btn btn-danger-gradient btn-block" id="inactive" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                            } else {
                                return `<button class="btn btn-warning-gradient btn-block" id="pending_activation" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('app_users.pActive') }}</button>`;
                            }
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            return data.activation_code ?? "";
                        },
                    },
                    // {
                    //     'data': null,
                    //     render: function (data, row, type) {
                    //         return data.exp_at ?? "حساب جديد غير فعال";
                    //     },
                    // },




                    {
                        'data': 'created_at',
                        'className': 'text-center text-lg text-medium',
                        render: function (data) {
                            if (data == null) return "";
                            var date = new Date(data);
                            var month = date.getMonth() + 1;
                            return date.toLocaleString();
                            // return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" +
                            //     month) + "/" + date.getFullYear();
                        }
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            return `
                        @can('customer-view')
                            <button class="btn btn-success btn-sm" id="Management" data-id="${data.id}" data-created="${data.activation_code}" data-payment="${data.mobile_number}"
                            data-address="${data.first_name + " " + data.last_name}" data-delivery_type="${data.credit}" data-count="${data.orders_count ?? 0}" data-sum="${data.orders_sum_total_cost ?? 0}" data-connection="${data.connection ?? 0}" data-clothes_count="${data.clothes_count ?? 0}" data-views="${data.views ?? 0}"><i class="fa fa-clipboard"></i> {{ trans('orders.Details') }}</button>
                            {{--<a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/app_user_address') }}/${data.id}" class="btn btn-purple btn-sm "><i class="fa fa-bars"></i>  {{ trans('orders.addresses') }}</a>--}}
                            @endcan
                            @can('customer-update')
                            <a href="appUser/edit/${data.id}" class="modal-effect btn btn-sm btn-info">
                                <i class="las la-pen"></i>
                            </a>
                        @endcan

                            @can('customer-delete')
                            <button class="modal-effect btn btn-sm btn-danger" id="DeleteappUser" data-id="${data.id}" data-namee="${data.first_name}"><i class="las la-trash"></i></button>
                        @endcan
                            `;
                        },
                    },
                ],
            });
        } else{
            $('#s').click(function (e) {
                e.preventDefault();
                var name = $('#named').val();
                var commercial_name = $('#commercial_name').val();
                var phoned = $('#phoned').val();
                var creditd = $('#creditd').val();
                var ids = $('#ids').val();
                var credit_operation = $('#credit_operation').val();
                var status = $('#statusd').val();
                var country = $('#Countryd').val();
                var to = $('#to').val();
                var from = $('#from').val();
                var lang = local;
                $('#get_appUser').DataTable({
                    ordering: false,
                    bDestroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    pageLength: 20,
                    lengthMenu: [
                        [10, 50 , 200 , 500 , 1000 ,  -1],
                        [10, 50 , 200 , 500 , 1000],
                    ],
                    ajax: {
                        url: '{{ url("admin/appUser/get") }}' + '/?name=' + name
                            + '&phone=' + phoned + '&status=' + status
                            + '&country=' + country + '&type=' + @json($type)   ,
                        cache: true
                    },
                    columns: [
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                return `<input type="checkbox" name="check" class="che" value="${data.id}"/>`;
                            },
                        },
                        {
                            'data': 'id',
                            'className': 'text-center text-lg text-medium'
                        },
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                if(data.first_name){
                                    return `
                            {{--<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>--}}
                                    <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.first_name ?? "" + " " + data.last_name ?? ""} </a>
                            `;
                                }
                                // return data.first_name ?? "" + " " + data.last_name ?? "";
                            },
                        },
                        {{--{--}}
                        {{--    'data': null,--}}
                        {{--    'className': 'text-center text-lg text-medium',--}}
                        {{--    render: function (data, row, type) {--}}
                        {{--        if(data.type != 1){--}}
                        {{--            if(data.commercial_name)--}}
                        {{--                return `--}}
                        {{--    --}}{{--<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>--}}
                        {{--                <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.commercial_name} </a>--}}
                        {{--    `;--}}

                        {{--        }--}}
                        {{--    },--}}
                        {{--},--}}
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data) {
                                if(data.categories){
                                    if (local == 'en') {
                                        return data.categories.title_en;
                                    }else {
                                        return data.categories.title_ar;
                                    }
                                }else {
                                    return 'null'
                                }
                            },
                        },
                        {
                            'data': 'mobile_number',
                        },
                        {
                            'data': null,
                            'className': 'text-center text-lg text-medium',
                            render: function (data, row, type) {
                                if (local == 'en') {
                                    if (data.country) {
                                        return data.country.title_en ?? "";
                                    } else {
                                        return "";
                                    }
                                } else {
                                    if (data.country) {
                                        return data.country.title_ar ?? "";
                                    } else {
                                        return "";
                                    }
                                }
                            },
                        },
                        // {
                        //     'data': null,
                        //     'className': 'text-center text-lg text-medium',
                        //     render: function (data) {
                        //         return data.clothes_count
                        //     },
                        //
                        // },
                        // {
                        //     'data': 'views',
                        //     "searchable": false ,
                        //     'className': 'text-center text-lg text-medium'
                        // },
                        // {
                        //     'data': 'connection',
                        //     "searchable": false ,
                        //     'className': 'text-center text-lg text-medium'
                        // },
                        {
                            'data': 'device_type',
                            "searchable": false ,
                            'className': 'text-center text-lg text-medium'
                        },
                        //

                        // {
                        //     'data': null,
                        //     render: function (data, row, type) {
                        //         var phone;
                        //         if (data.automatic_acceptance == '1') {
                        //             return `<button class="btn btn-success-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'موثق' }}</button>`;

                        //         } else  {
                        //             return `<button class="btn btn-danger-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'غير موثق' }}</button>`;
                        //         }
                        //     },
                        // },
                        {
                            'data': null,
                            render: function (data, row, type) {
                                var phone;
                                if (data.status == 'active') {
                                    return `<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;

                                } else if (data.status == 'inactive') {
                                    return `<button class="btn btn-danger-gradient btn-block" id="inactive" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                                } else {
                                    return `<button class="btn btn-warning-gradient btn-block" id="pending_activation" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('app_users.pActive') }}</button>`;
                                }
                            },
                        },
                        {
                            'data': null,
                            render: function (data, row, type) {
                                return data.activation_code ?? "";
                            },
                        },
                        // {
                        //     'data': null,
                        //     render: function (data, row, type) {
                        //         return data.exp_at ?? "حساب جديد غير فعال";
                        //     },
                        // },




                        {
                            'data': 'created_at',
                            'className': 'text-center text-lg text-medium',
                            render: function (data) {
                                if (data == null) return "";
                                var date = new Date(data);
                                var month = date.getMonth() + 1;
                                return date.toLocaleString();
                                // return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" +
                                //     month) + "/" + date.getFullYear();
                            }
                        },
                        {
                            'data': null,
                            render: function (data, row, type) {
                                return `
                        @can('customer-view')
                                <button class="btn btn-success btn-sm" id="Management" data-id="${data.id}" data-created="${data.activation_code}" data-payment="${data.mobile_number}"
                            data-address="${data.first_name + " " + data.last_name}" data-delivery_type="${data.credit}" data-count="${data.orders_count ?? 0}" data-sum="${data.orders_sum_total_cost ?? 0}"><i class="fa fa-clipboard"></i> {{ trans('orders.Details') }}</button>
                            {{--<a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/app_user_address') }}/${data.id}" class="btn btn-purple btn-sm "><i class="fa fa-bars"></i>  {{ trans('orders.addresses') }}</a>--}}
                                @endcan
                                @can('customer-update')
                                {{--<button class="modal-effect btn btn-sm btn-info" id="Addcredit" data-id="${data.id}"><i class="fa fa-cogs"></i> {{ trans('app_users.Add_Credit') }}</button>--}}
                                <a href="appUser/edit/${data.id}" class="modal-effect btn btn-sm btn-info">
                                <i class="las la-pen"></i>
                            </a>
                        @endcan
                                @can('customer-delete')
                                <button class="modal-effect btn btn-sm btn-danger" id="DeleteappUser" data-id="${data.id}" data-namee="${data.first_name}"><i class="las la-trash"></i></button>
                        @endcan
                                `;
                            },
                        },
                    ],
                });

            });
            var table = $('#get_appUser').DataTable({
                ordering: false,
                bDestroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 20,
                lengthMenu: [
                    [10, 50 , 200 , 500 , 1000 ,  -1],
                    [10, 50 , 200 , 500 , 1000],
                ],
                ajax: '{{ route("get_appUser") .'?type='.$type}}',
                columns: [
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return `<input type="checkbox" name="check" class="che" value="${data.id}"/>`;
                        },
                    },
                    {
                        'data': 'id',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if(data.first_name){
                                return `
                            {{--<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>--}}
                                <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.first_name ?? "" + " " + data.last_name ?? ""} </a>
                            `;
                            }
                            // return data.first_name ?? "" + " " + data.last_name ?? "";
                        },
                    },
                    {{--{--}}
                    {{--    'data': null,--}}
                    {{--    'className': 'text-center text-lg text-medium',--}}
                    {{--    render: function (data, row, type) {--}}
                    {{--        if(data.type != 1){--}}
                    {{--            if(data.commercial_name)--}}
                    {{--                return `--}}
                    {{--        --}}{{--<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>--}}
                    {{--                <a target="_blank" href="{{url('admin/appUser/profile/')}}/${data.id} ">${data.commercial_name} </a>--}}
                    {{--        `;--}}

                    {{--        }--}}
                    {{--    },--}}
                    {{--},--}}
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data) {
                            if(data.categories){
                                if (local == 'en') {
                                    return data.categories.title_en;
                                }else {
                                    return data.categories.title_ar;
                                }
                            }else {
                                return 'null'
                            }
                        },
                    },
                    {
                        'data': 'mobile_number',
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if (local == 'en') {
                                if (data.country) {
                                    return data.country.title_en ?? "";
                                } else {
                                    return "";
                                }
                            } else {
                                if (data.country) {
                                    return data.country.title_ar ?? "";
                                } else {
                                    return "";
                                }
                            }
                        },
                    },
                    // {
                    //     'data': null,
                    //     'className': 'text-center text-lg text-medium',
                    //     render: function (data) {
                    //         return data.clothes_count
                    //     },
                    //
                    // },
                    // {
                    //     'data': 'views',
                    //     "searchable": false ,
                    //     'className': 'text-center text-lg text-medium'
                    // },
                    // {
                    //     'data': 'connection',
                    //     "searchable": false ,
                    //     'className': 'text-center text-lg text-medium'
                    // },
                    {
                        'data': 'device_type',
                        "searchable": false ,
                        'className': 'text-center text-lg text-medium'
                    },
                    //

                    // {
                    //     'data': null,
                    //     render: function (data, row, type) {
                    //         var phone;
                    //         if (data.automatic_acceptance == '1') {
                    //             return `<button class="btn btn-success-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'موثق' }}</button>`;

                    //         } else  {
                    //             return `<button class="btn btn-danger-gradient btn-block" id="active_automatic_acceptance" data-id="${data.id}" data-automatic_acceptance="${data.automatic_acceptance}">{{ 'غير موثق' }}</button>`;
                    //         }
                    //     },
                    // },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            var phone;
                            if (data.status == 'active') {
                                return `<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;

                            } else if (data.status == 'inactive') {
                                return `<button class="btn btn-danger-gradient btn-block" id="inactive" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                            } else {
                                return `<button class="btn btn-warning-gradient btn-block" id="pending_activation" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('app_users.pActive') }}</button>`;
                            }
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            return data.activation_code ?? "";
                        },
                    },
                    // {
                    //     'data': null,
                    //     render: function (data, row, type) {
                    //         return data.exp_at ?? "حساب جديد غير فعال";
                    //     },
                    // },




                    {
                        'data': 'created_at',
                        'className': 'text-center text-lg text-medium',
                        render: function (data) {
                            if (data == null) return "";
                            var date = new Date(data);
                            var month = date.getMonth() + 1;
                            return date.toLocaleString();
                            // return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" +
                            //     month) + "/" + date.getFullYear();
                        }
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            return `
                        @can('customer-view')
                            <button class="btn btn-success btn-sm" id="Management" data-id="${data.id}" data-created="${data.activation_code}" data-payment="${data.mobile_number}"
                            data-address="${data.first_name + " " + data.last_name}" data-delivery_type="${data.credit}" data-count="${data.orders_count ?? 0}" data-sum="${data.orders_sum_total_cost ?? 0}" data-connection="${data.connection ?? 0}" data-clothes_count="${data.clothes_count ?? 0}" data-views="${data.views ?? 0}"><i class="fa fa-clipboard"></i> {{ trans('orders.Details') }}</button>
                            {{--<a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/app_user_address') }}/${data.id}" class="btn btn-purple btn-sm "><i class="fa fa-bars"></i>  {{ trans('orders.addresses') }}</a>--}}
                            @endcan
                            @can('customer-update')
                            <a href="appUser/edit/${data.id}" class="modal-effect btn btn-sm btn-info">
                                <i class="las la-pen"></i>
                            </a>
                        @endcan

                            @can('customer-delete')
                            <button class="modal-effect btn btn-sm btn-danger" id="DeleteappUser" data-id="${data.id}" data-namee="${data.first_name}"><i class="las la-trash"></i></button>
                        @endcan
                            `;
                        },
                    },
                ],
            });
        }


        // table.draw( false );



        $(document).on('click', '#ShowModalEditCategory', function (e) {
            e.preventDefault();
            var id_admin = $(this).data('id');
            var mobile_number = $(this).data('phone');
            var name = $(this).data('name');
            var cat_id = $(this).data('cat');
            $('#mobile_number').val(mobile_number);
            $('#id_phone').val(id_admin);
            $('#name').val(name);
            $("select[name=cat_id] option[value="+cat_id+"]").attr("selected", "selected");
            // $('#cat_id').val(cat_id);


            $('#modalphone').modal('show');
        });
        $(document).on('click', '#Addphone', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formphone')[0]);
            var id = $('#id_phone').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/appUser/add_address/") }}/' + id + '/3',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-info");
                    $('#error_message').text(response.message);
                    $('#modalphone').modal('hide');
                    table.draw(false)
                    // table.ajax.reload();
                }
            });
        });

        $(document).on('click', '#DeleteappUser', function (e) {
            e.preventDefault();
            $('#usernamed').val($(this).data('namee'));
            var id_admin = $(this).data('id');
            $('#modaldemo8').modal('show');
            console.log(id_admin)
            aaaaa(id_admin);
        });

        function aaaaa(id) {
            $(document).off("click", "#delete").on("click", "#delete", function (e) {
               console.log(id)
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ url("admin/appUser/delete") }}/' + id,
                    data: '',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                        $('#modaldemo8').modal('hide');
                        table.draw(false)
                        // table.ajax.reload();
                    }
                });
            });
        }


        $(document).on('click', '#active', function (e) {
            e.preventDefault();
            // console.log("Alliiiii");
            var edit_id = $(this).data('id');
            var status = $(this).data('viewing_status');
            if (status == "active") {
                status = "inactive";
            } else {
                status = "pending_activation";
            }
            var data = {
                id: edit_id,
                status: status
            };
            // console.log(status);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("appuser.status") }}',
                data: data,
                success: function (response) {
                    table.draw(false)
                    // table.ajax.reload();

                }
            });


        });
        $(document).on('click', '#pending_activation', function (e) {
            e.preventDefault();
            // console.log("Alliiiii");
            var edit_id = $(this).data('id');
            var status = $(this).data('viewing_status');
            if (status == "pending_activation") {
                status = "active";
            } else {
                status = "inactive";
            }
            var data = {
                id: edit_id,
                status: status
            };
            // console.log(status);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("appuser.status") }}',
                data: data,
                success: function (response) {
                    // $('#error_message').html("");
                    // $('#error_message').addClass("alert alert-danger");
                    // $('#error_message').text(response.message);
                    // table.ajax.reload();
                    table.draw(false)

                }
            });
        });
        $(document).on('click', '#inactive', function (e) {
            e.preventDefault();
            // console.log("Alliiiii");
            var edit_id = $(this).data('id');
            var status = $(this).data('viewing_status');
            if (status == "inactive") {
                status = "pending_activation";
            } else {
                status = "active";
            }
            var data = {
                id: edit_id,
                status: status
            };
            // console.log(status);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("appuser.status") }}',
                data: data,
                success: function (response) {
                    // $('#error_message').html("");
                    // $('#error_message').addClass("alert alert-danger");
                    // $('#error_message').text(response.message);
                    // table.ajax.reload();
                    table.draw(false)
                }
            });
        });
        $(document).on('click', '#Addcredit', function (e) {
            e.preventDefault();
            console.log("dsadsadsa");
            $('#modalAddcredit').modal('show');
            var id = $(this).data('id');
            aaaa(id);
            {{--$('#Addcredit1').click(function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    var data = {--}}
            {{--        id: id,--}}
            {{--        credit: $('#credit').val(),--}}
            {{--        note: $('#note').val(),--}}
            {{--    };--}}
            {{--    $.ajaxSetup({--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        }--}}
            {{--    });--}}
            {{--    $.ajax({--}}
            {{--        type: 'POST',--}}
            {{--        url: '{{ route("ds") }}',--}}
            {{--        data: data,--}}
            {{--        success: function (response) {--}}
            {{--            $('#error_message').html("");--}}
            {{--            $('#error_message').addClass("alert alert-danger");--}}
            {{--            $('#error_message').text(response.message);--}}
            {{--            $('#modalAddcredit').modal('hide');--}}
            {{--            $('#formcategorys')[0].reset();--}}

            {{--            table.ajax.reload();--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
        });
        function aaaa(id) {
            $(document).off("click", "#Addcredit1").on("click", "#Addcredit1", function (e) {
                e.preventDefault();
                var data = {
                    id: id,
                    credit: $('#credit').val(),
                    note: $('#note').val(),
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route("ds") }}',
                    data: data,
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        $('#modalAddcredit').modal('hide');
                        $('#formcategorys')[0].reset();
                        table.draw(false)
                        // table.ajax.reload();
                    }
                });
            });
        }

        $(document).on('click', '#Management', function (e) {
            e.preventDefault();
            $('#exampleModal2').modal('show');
            var id = $(this).data('id');
            var created = $(this).data('created');
            var payment = $(this).data('payment');
            var address = $(this).data('address');
            var delivery_type = $(this).data('delivery_type');
            var count = $(this).data('count');
            var sum = $(this).data('sum');
            var connection = $(this).data('connection');
            var views = $(this).data('views');
            var numOfAds = $(this).data('clothes_count');
            // var user_id = $(this).data('user_id');
            // var user_name = $(this).data('user_name');
            // var mobile_number = $(this).data('mobile_number');
            // var user_agent = $(this).data('user_agent');
            $('#Numberorders').text(id);
            $('#Totalinvoices').text(created);
            $('#pieces').text(payment);
            $('#date').text(address);
            $('#Payment').text(delivery_type);
            $('#count').text(count);
            $('#sum').text(sum);
            $('#connection').text(connection);
            $('#views').text(views);
            $('#numOfAds').text(numOfAds);
            // $('#Customer_Number').text(user_id);
            // $('#Name').text(user_name);
            // $('#Mobile_number').text(mobile_number);
            // $('#Device_type').text(user_agent);

        });

        $(document).on('click', '#Delete_all', function (e) {
            e.preventDefault();
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $('#modaldemo100').modal('show');
                $(document).on('click', '#dletet100', function (e) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'get',
                        url: '{{ route("users.all") }}'+ '/?type=1',
                        data: {'ids': ids},
                        success: function (response) {
                            $('#error_message').html("");
                            $('#error_message').addClass("alert alert-danger");
                            $('#error_message').text(response.message);
                            $('#modaldemo100').modal('hide');
                            table.draw(false)
                            // table.ajax.reload();
                        }
                    });
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }

        });
        $(document).on('click', '#Activate_all', function (e) {
            e.preventDefault();
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                // $('#modaldemo100').modal('show');
                // $(document).on('click', '#dletet100', function (e) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'get',
                        url: '{{ route("users.all") }}'+ '/?type=2',
                        data: {'ids': ids},
                        success: function (response) {
                            // $('#error_message').html("");
                            // $('#error_message').addClass("alert alert-danger");
                            // $('#error_message').text(response.message);
                            // $('#modaldemo100').modal('hide');
                            table.draw(false)
                            // table.ajax.reload();
                        }
                    });
                // });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }

        });

        $(document).on('click', '#active_automatic_acceptance', function (e) {
            e.preventDefault();
            // console.log("Alliiiii");
            var edit_id = $(this).data('id');
            var active_automatic_acceptance = $(this).data('automatic_acceptance');
            console.log(active_automatic_acceptance);
            if (active_automatic_acceptance == "1") {
                active_automatic_acceptance = "0";
            } else {
                active_automatic_acceptance = "1";
            }
            var data = {
                id: edit_id,
                active_automatic_acceptance: active_automatic_acceptance
            };
            // console.log(status);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("appuser.active_automatic_acceptance") }}',
                data: data,
                success: function (response) {
                    table.draw(false)
                    // table.ajax.reload();

                }
            });


        });


    </script>
@endsection
