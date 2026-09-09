<?php
$title = 'title_' . app()->getLocale();
$note = 'note' . app()->getLocale();
?>
    <div class="FixedAdds mb-5 fixedMarg">
        <div class="headerShare p-3  mb-3 d-flex justify-content-between align-items-center CairoSemiBold">
            <h4 class="m-0">الإعلانات المثبتة</h4>
            <div class="navgation">
                <a class="ms-1 bg-none border-0 text-decoration-none" href="All.html">
                    <span class="CariaRegular me-2">عرض الجميع</span>
                    <i class="far fa-angle-left"></i>
                </a>
            </div>
        </div>
        <div class="AddsItems">
            <div class="row">
                @foreach($data['fixed_ads'] as $fixed_add)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-md-3 mb-lg-0 mb-sm-4 mb-3 AddsItem">
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
                                    <i class="fa fa-heart Liked"></i>
                                </div>
                                <div class="AddDescribe AddDescribe1 mb-2">
                                    <p class="m-0 CariaRegular">
                                        {{$fixed_add->clothes->$note}}
                                    </p>
                                </div>
                                <div class="AddDetails AddDetails1 CairoSemiBold d-flex align-items-center justify-content-between">
                            <span>
                              {{$fixed_add->clothes->price}} د.ك
                            </span>
                                    <a href="Chat.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                             height="22.274"
                                             viewBox="0 0 32.957 32.274">
                                            <path id="Chat"
                                                  d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                  transform="translate(-2 -2)" fill="#5c4db1"
                                                  fill-rule="evenodd"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="FeaturesAdds PaginateItem fixedMarg">
        <div class="AddsItems">
            <div class="row">
                @foreach($data['products'] as $product)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-md-3 mb-lg-0 mb-sm-4 mb-3 AddsItem">
                        <div class="card cardShare border-0">
                            <a href="{{route("DetailProduct").'?id='.$product->id . '&cat_id='.$product->cat_id}}">
                                <div class="card-img card-img1 position-relative"
                                     style="background-image: {{url('/').'/assets/tmp/'.$product->image}};">
                                </div>
                            </a>
                            <div class="card-body bg-none">
                                <div class="NameAdd d-flex align-items-center justify-content-between mb-2">
                                    <p class="CairoSemiBold m-0">
                                        {{$product->$title}}
                                    </p>
                                    <i class="fa fa-heart Liked"></i>
                                </div>
                                <div class="AddDescribe AddDescribe1 mb-2">
                                    <p class="m-0 CariaRegular">
                                        {{$product->$note}}
                                    </p>
                                </div>
                                <div class="AddDetails AddDetails1 CairoSemiBold d-flex align-items-center justify-content-between">
                            <span>
                              {{$product->price}} د.ك
                            </span>
                                    <a href="Chat.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                             height="22.274"
                                             viewBox="0 0 32.957 32.274">
                                            <path id="Chat"
                                                  d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                  transform="translate(-2 -2)" fill="#5c4db1"
                                                  fill-rule="evenodd"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>