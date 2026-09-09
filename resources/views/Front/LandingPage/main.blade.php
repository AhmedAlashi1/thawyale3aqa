@extends("Front.layout.master")
@section("content")
    
    <div class="content" id="content">
        <div class="owl-carousel owl-theme Slider" id="SliderTop">
            @foreach($data['up_banner'] as $banner)
            <div class="item">
                <a href="{{$banner->url}}">
                    <img src="{{url('/').'/assets/tmp/'.$banner->image}}" class="w-100" style="height: 708px">
                </a>
            </div>
            @endforeach
        </div>
{{--        <hr>--}}
        <div class="container">
            <div class="classfications mb-4">
                <div class="headerShare mb-3 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                    <h4 class="m-0">الاقسام</h4>
                    <div class="navgation">
                        <button class="owl-prev-custom bg-none border-0">
                            <i class="far fa-angle-right"></i>
                        </button>
                        <button class="ms-1 owl-next-custom bg-none border-0">
                            <i class="far fa-angle-left"></i>
                        </button>
                    </div>
                </div>
                <div class="owl-carousel owl-theme SliderClassfication" id="SliderClassfication">

                    @foreach($data['categories'] as $category)
                        <div class="item">
                            <a href="{{route('classification').'?cat_id='.$category->id}}"
                               class="text-decoration-none category-link category-link">
                                <div class="classficationImg d-flex justify-content-center"
                                     style="background: {{$category->color}}; padding-top: 20%">
                                    <img src="{{url('/').'/assets/tmp/'.$category->image}}" width="39.935"
                                         height="41.027" style="width: 80px; height: 60px" id="vehicle-car-svgrepo-com">
                                    {{--                                <svg xmlns="http://www.w3.org/2000/svg" width="39.935" height="41.027" viewBox="0 0 39.935 41.027">--}}
                                    {{--                                    <path id="vehicle-car-svgrepo-com" d="M31.39,3a7.209,7.209,0,0,1,6.993,5.456l.854,3.416h2.025a1.664,1.664,0,0,1,1.648,1.438l.015.226a1.664,1.664,0,0,1-1.438,1.648l-.226.015H40.073l.461,1.833a4.988,4.988,0,0,1,2.4,4.266V40.146a3.882,3.882,0,0,1-3.882,3.882H35.717a3.882,3.882,0,0,1-3.882-3.882l0-2.765H14.1v2.765a3.882,3.882,0,0,1-3.882,3.882H6.882A3.882,3.882,0,0,1,3,40.146V21.3a4.988,4.988,0,0,1,2.4-4.266l.46-1.834h-1.2a1.664,1.664,0,0,1-1.648-1.438L3,13.536a1.664,1.664,0,0,1,1.438-1.648l.226-.015H6.689L7.545,8.46A7.209,7.209,0,0,1,14.538,3ZM10.772,37.38H6.325l0,2.765a.555.555,0,0,0,.555.555h3.336a.555.555,0,0,0,.555-.555Zm28.835,0H35.16l0,2.765a.555.555,0,0,0,.555.555h3.336a.555.555,0,0,0,.555-.555ZM37.944,19.636H7.991A1.664,1.664,0,0,0,6.327,21.3V34.053H39.608V21.3A1.664,1.664,0,0,0,37.944,19.636ZM19.079,27.4h7.767a1.664,1.664,0,0,1,.226,3.312l-.226.015H19.079a1.664,1.664,0,0,1-.226-3.312l.226-.015h0Zm14.974-4.436a2.218,2.218,0,1,1-2.218,2.218A2.218,2.218,0,0,1,34.053,22.963Zm-22.181,0a2.218,2.218,0,1,1-2.218,2.218A2.218,2.218,0,0,1,11.872,22.963ZM31.39,6.327H14.538a3.882,3.882,0,0,0-3.766,2.94l-1.76,7.041H36.921L35.155,9.265A3.882,3.882,0,0,0,31.39,6.327Z" transform="translate(-3 -3)" fill="#212121"/>--}}
                                    {{--                                </svg>--}}
                                </div>
                                <p class="m-0 Describe CairoSemiBold text-center">
                                    <?php
                                    $title = 'title_' . app()->getLocale();
                                    $note = 'note_' . app()->getLocale();
//                                    $coin_name = 'note_' . app()->getLocale();
                                    ?>
                                    {{$category->$title}}
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="BigAdds fixedMarg">
                <div class="row">
{{--                    <div class="col-lg-8 col-md-4  col-sm-8 col-12 BigAdd pe-md-1 mb-md-0 mb-3">--}}
{{--                        <a href="{{route("DetailProduct")}}">--}}
{{--                            <div class="Add" style="background-image: url('img/Png/Big.png');">--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}

                @foreach($data['up_banner_commercial'] as $up_banner_commercial)
                    <div class="col-lg-4 col-md-4  col-sm-4 col-12 SmallAdd ps-md-1">
                        <a href="{{$up_banner_commercial->url}}">
                            <div class="Add" style="background-image: url({{url('/').'/assets/tmp/'.$up_banner_commercial->image}});">
                            </div>
                        </a>
                    </div>

            @endforeach
            </div>
            </div>
            @if(($data['fixed_ads'])->count() > 0)
                <div class="FixedAdds fixedMarg">
                    <div class="headerShare p-3  mb-3 d-flex justify-content-between align-items-center CairoSemiBold">
                        <h4 class="m-0">الإعلانات المثبتة</h4>
                        <div class="navgation">
                            <a class="ms-1 bg-none border-0 text-decoration-none" href="Classification.html">
                                <span class="CariaRegular me-2">عرض الجميع</span>
                                <i class="far fa-angle-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="AddsItems">
{{--                        <div class="row">--}}
                            <div class="owl-carousel owl-theme SliderProducts" id="SliderProducts">
                                @foreach($data['fixed_ads'] as $fixed_add)
                                    <div class="item mb-md-3 mb-lg-0 mb-sm-4 mb-3 AddsItem">
                                        <div class="card cardShare border-0">
                                            <a href="{{route("DetailProduct").'?id='.$fixed_add->clothes->id . '&cat_id='.$fixed_add->clothes->cat_id}}">
                                                <div class="card-img card-img1 position-relative"
                                                     style="background-image:url( {{url('/').'/assets/tmp/'.$fixed_add->clothes->image}});">

                                                    <div class="IconAdd position-absolute">
                                                        <img src="{{asset('assets/front/img/SVG/AddIcon.svg')}}">
                                                    </div>


                                                </div>
                                            </a>
                                            <div class="card-body bg-none">
                                                <div class="NameAdd d-flex align-items-center justify-content-between mb-2">
                                                    <p class="CairoSemiBold m-0">
                                                        {{$fixed_add->clothes->$title}}
                                                    </p>
                                                    @auth('user')



    {{--                                                        <button class="  fav "    data-id="{{$fixed_add->clothes->id}}" >--}}
                                                                <i    data-id="{{$fixed_add->clothes->id}}"  id="fav{{$fixed_add->clothes->id}}" class="fav fa  @if($fixed_add->clothes->favorites->where('user_id', auth('user')->user()->id)->count() > 0)fa-heart Liked @else fa-heart-o UnLiked  @endif fav{{$fixed_add->clothes->id}} "  style="cursor:pointer">

                                                                </i>
    {{--                                                    </button>--}}

                                                    @endauth
                                                </div>
                                                <div class="AddDescribe AddDescribe1 mb-2">
                                                    <p class="m-0 CariaRegular">
                                                        {{$fixed_add->clothes->$note}}
                                                    </p>
                                                </div>
                                                <div class="AddDetails AddDetails1 CairoSemiBold d-flex align-items-center justify-content-between">
                                            <span  class="price">
                                              <span>
                                                  {{$fixed_add->clothes->price}}
                                              </span>
                                                @if (App::getLocale() == 'en')
                                                    {{ $fixed_add->clothes->country->coin_name_en }}
                                                @else
                                                    {{ $fixed_add->clothes->country->coin_name }}
                                                @endif

                                            </span>
                                                <a href="{{route('chat',$fixed_add->clothes->user->id)}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                                         height="22.274"
                                                         viewBox="0 0 32.957 32.274">
                                                        <path id="Chat"
                                                              d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                              transform="translate(-2 -2)" fill="#5c4db1"
                                                              fill-rule="evenodd"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="text-danger" target="_blank">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                                <a href="" class="text-primary" target="_blank">
                                                    <i class="fas fa-sms"></i>
                                                </a>
                                                <a href="" class="text-success" target="_blank">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
{{--                        </div>--}}
                    </div>
                </div>
            @endif
            @if(($data['featured'])->count() > 0)
                <div class="FeaturesAdds fixedMarg">
                    <div class="headerShare p-3  mb-3 d-flex justify-content-between align-items-center CairoSemiBold">
                        <h4 class="m-0">الأكثر المميزة</h4>
                        <div class="navgation">
                            <a class="ms-1 bg-none border-0 text-decoration-none" href="All.html">
                                <span class="CariaRegular me-2">عرض الجميع</span>
                                <i class="far fa-angle-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="AddsItems">
                        <div class="row">
                            <div class="owl-carousel owl-theme SliderProducts" id="SliderProducts">
                            @foreach($data['featured'] as $featured)
                                <div class="item mb-md-3 mb-lg-0 mb-sm-4 mb-3 AddsItem">
                                    <div class="card cardShare border-0">
                                        <a href="{{route("DetailProduct").'?id='.$featured->id . '&cat_id='.$featured->cat_id}}">
                                            <div class="card-img card-img1 position-relative"
                                                 style="background-image: url({{url('/').'/assets/tmp/'.$featured->image}});">
                                            </div>
                                        </a>
                                        <div class="card-body bg-none">
                                            <div class="NameAdd d-flex align-items-center justify-content-between mb-2">
                                                <p class="CairoSemiBold m-0">
                                                    {{$featured->$title}}
                                                </p>
                                                @auth('user')



                                                    {{--                                                        <button class="  fav "    data-id="{{$fixed_add->clothes->id}}" >--}}
                                                    <i    data-id="{{$featured->id}}"  id="fav{{$featured->id}}" class="fav fa  @if($featured->favorites->where('user_id', auth('user')->user()->id)->count() > 0)fa-heart Liked @else fa-heart-o UnLiked  @endif fav{{$featured->id}} "  style=" cursor:pointer">

                                                    </i>
                                                    {{--                                                    </button>--}}

                                                @endauth
{{--                                                <i class="fa fa-heart Liked"></i>--}}
                                            </div>
                                            <div class="AddDescribe AddDescribe1 mb-2">
                                                <p class="m-0 CariaRegular">
                                                    {{$featured->$note}}
                                                </p>
                                            </div>
                                            <div class="AddDetails AddDetails1 CairoSemiBold d-flex align-items-center justify-content-between">
                            <span class="price">
                              {{$featured->price}} د.ك
                            </span>
                                                <a href="{{route('chat',$featured->user->id)}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                                         height="22.274"
                                                         viewBox="0 0 32.957 32.274">
                                                        <path id="Chat"
                                                              d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                              transform="translate(-2 -2)" fill="#5c4db1"
                                                              fill-rule="evenodd"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="text-danger" target="_blank">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                                <a href="" class="text-primary" target="_blank">
                                                    <i class="fas fa-sms"></i>
                                                </a>
                                                <a href="" class="text-success" target="_blank">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(($data['most_watched'])->count() > 0)
                <div class="FeaturesAdds fixedMarg">
                    <div class="headerShare p-3  mb-3 d-flex justify-content-between align-items-center CairoSemiBold">
                        <h4 class="m-0">الأكثر مشاهدة</h4>
                        <div class="navgation">
                            <a class="ms-1 bg-none border-0 text-decoration-none" href="All.html">
                                <span class="CariaRegular me-2">عرض الجميع</span>
                                <i class="far fa-angle-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="AddsItems">
                        <div class="owl-carousel owl-theme SliderProducts" id="SliderProducts">
                            @foreach($data['most_watched'] as $most_watched)
                                <div class="item mb-md-3 mb-lg-0 mb-sm-4 mb-3 AddsItem">
                                    <div class="card cardShare border-0">
                                        <a href="{{route("DetailProduct").'?id='.$most_watched->id . '&cat_id='.$most_watched->cat_id}}">
                                            <div class="card-img card-img1 position-relative"
                                                 style="background-image: url( {{url('/').'/assets/tmp/'.$most_watched->image}});">
                                            </div>
                                        </a>
                                        <div class="card-body bg-none">
                                            <div class="NameAdd d-flex align-items-center justify-content-between mb-2">
                                                <p class="CairoSemiBold m-0">
                                                    {{$most_watched->$title}}
                                                </p>
                                                @auth('user')



                                                    {{--                                                        <button class="  fav "    data-id="{{$fixed_add->clothes->id}}" >--}}
                                                    <i    data-id="{{$most_watched->id}}"  id="fav{{$most_watched->id}}" class="fav fa  @if($most_watched->favorites->where('user_id', auth('user')->user()->id)->count() > 0)fa-heart Liked @else fa-heart-o UnLiked  @endif fav{{$most_watched->id}} "  style="cursor:pointer">

                                                    </i>
                                                    {{--                                                    </button>--}}

                                                @endauth
{{--                                                <i class="fa fa-heart Liked"></i>--}}
                                            </div>
                                            <div class="AddDescribe AddDescribe1 mb-2">
                                                <p class="m-0 CariaRegular">
                                                    {{$most_watched->$note}}
                                                </p>
                                            </div>
                                            <div class="AddDetails AddDetails1 CairoSemiBold d-flex align-items-center justify-content-between">
                        <span class="price">
                            <span>{{$most_watched->price}}</span> د.ك
                        </span>
                                                <a href="{{route('chat',$most_watched->user->id)}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                                         height="22.274"
                                                         viewBox="0 0 32.957 32.274">
                                                        <path id="Chat"
                                                              d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                              transform="translate(-2 -2)" fill="#5c4db1"
                                                              fill-rule="evenodd"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="text-danger" target="_blank">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                                <a href="" class="text-primary" target="_blank">
                                                    <i class="fas fa-sms"></i>
                                                </a>
                                                <a href="" class="text-success" target="_blank">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if(($data['latest_ads'])->count() > 0)
                <div class="FeaturesAdds fixedMarg">
                    <div class="headerShare p-3  mb-3 d-flex justify-content-between align-items-center CairoSemiBold">
                        <h4 class="m-0">اخر الاعلانات</h4>
                        <div class="navgation">
                            <a class="ms-1 bg-none border-0 text-decoration-none" href="All.html">
                                <span class="CariaRegular me-2">عرض الجميع</span>
                                <i class="far fa-angle-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="AddsItems">
                        <div class="owl-carousel owl-theme SliderProducts" id="SliderProducts">
                            @foreach($data['latest_ads'] as $latest_ads)
                                <div class="item mb-md-3 mb-lg-0 mb-sm-4 mb-3 AddsItem">
                                    <div class="card cardShare border-0">
                                        <a href="{{route("DetailProduct").'?id='.$latest_ads->id . '&cat_id='.$latest_ads->cat_id}}">
                                            <div class="card-img card-img1 position-relative"
                                                 style="background-image: url({{url('/').'/assets/tmp/'.$latest_ads->image}});">
                                            </div>
                                        </a>
                                        <div class="card-body bg-none">
                                            <div class="NameAdd d-flex align-items-center justify-content-between mb-2">
                                                <p class="CairoSemiBold m-0">
                                                    {{$latest_ads->$title}}
                                                </p>
                                                @auth('user')



                                                    {{--                                                        <button class="  fav "    data-id="{{$fixed_add->clothes->id}}" >--}}
                                                    <i    data-id="{{$latest_ads->id}}"  id="fav{{$latest_ads->id}}" class="fav fa  @if($latest_ads->favorites->where('user_id', auth('user')->user()->id)->count() > 0)fa-heart Liked @else fa-heart-o UnLiked  @endif fav{{$latest_ads->id}} "  style=" cursor:pointer">

                                                    </i>
                                                    {{--                                                    </button>--}}

                                                @endauth
{{--                                                <i class="fa fa-heart Liked"></i>--}}
                                            </div>
                                            <div class="AddDescribe AddDescribe1 mb-2">
                                                <p class="m-0 CariaRegular">
                                                    {{$latest_ads->$note}}
                                                </p>
                                            </div>
                                            <div class="AddDetails AddDetails1 CairoSemiBold d-flex align-items-center justify-content-between">
                            <span class="price">
                              {{$latest_ads->price}} د.ك
                            </span>
                                    <a href="{{route('chat',$latest_ads->user->id)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                             height="22.274"
                                             viewBox="0 0 32.957 32.274">
                                            <path id="Chat"
                                                  d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                  transform="translate(-2 -2)" fill="#5c4db1"
                                                  fill-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-danger" target="_blank">
                                        <i class="far fa-envelope"></i>
                                    </a>
                                    <a href="" class="text-primary" target="_blank">
                                        <i class="fas fa-sms"></i>
                                    </a>
                                    <a href="" class="text-success" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
@section('js')
<script>

    // function myFunction(x) {
    //     x.classList.toggle("fa-heart");
    // }

    $(document).on('click', '.fav', function(e) {
        e.preventDefault();
        var id = $(this).data('id');


        var data = {
            "_token": "{{ csrf_token() }}",
            id: id,

        };
        // console.log(status);

        $.ajax({
            type: 'POST',
            url: '{{ route("front.fav") }}',
            data: data,
            success: function(data) {
                if (data.message == 'add'){
                    $('.fav'+data.clothes_id).removeClass("fa-heart-o");
                    $('.fav'+data.clothes_id).removeClass("UnLiked");
                    $('.fav'+data.clothes_id).addClass("fa-heart");
                    $('.fav'+data.clothes_id).addClass("Liked");
                }else {
                    // $('.fav'+data.clothes_id).addClass("fa-heart-o");
                    // $('.fav'+data.clothes_id).removeClass("fa-heart");
                    $('.fav'+data.clothes_id).addClass("fa-heart-o");
                    $('.fav'+data.clothes_id).addClass("UnLiked");
                    $('.fav'+data.clothes_id).removeClass("fa-heart");
                    $('.fav'+data.clothes_id).removeClass("Liked");
                }


            }
        });
    });
    var owl = $('#SliderClassfication');
    $('.owl-prev-custom').click(function() {
        owl.trigger('prev.owl.carousel');
    })
    // Go to the previous item
    $('.owl-next-custom').click(function() {
        owl.trigger('next.owl.carousel');
    })

</script>
@endsection
