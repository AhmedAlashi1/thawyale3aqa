@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class="mt-4 mb-5">
            <div class="container">
                <div class="MyInfo mb-4">
                    <div class="MyInfoContainer">
                        <div class="MyData d-flex">
                            <div class="MyImg text-center position-relative">
                                <img src="{{asset("assets/tmp/" .  $user->avatar)}}" class="w-100">
                            </div>
                            <div class="MyMainInfo mt-md-2 mt-4 ms-md-4 me-md-0 ms-2 me-2 CairoSemiBold  d-flex flex-column">
                                <div class="TopDiv d-flex justify-content-between mb-3">
                                    <div>
                                        <h4 class="mb-2">{{$user->first_name}}</h4>
                                        <div class="SocialLinks d-flex align-items-center justify-content-start">
                                            <a href="#" class="text-decoration-none me-2" style="color: #459652;">
                                                <i class="fas fa-sms"></i>
                                            </a>
                                            <a href="Chat.html" class="text-decoration-none me-2"
                                               style="color: #5C4DB1;">
                                                <i class="fas fa-comment-alt-dots"></i>
                                            </a>
                                            <a href="#" class="text-decoration-none me-2" style="color: #459652;">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                            <a href="#" class="text-decoration-none me-2" style="color: #FF5A5A;">
                                                <i class="far fa-envelope"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="SellerFollow mt-3">
                                        <a href="Edit.html" class="text-decoration-none">
                                            متابعة
                                        </a>
                                    </div>
                                </div>
                                <p class="MyDescribe">
                                    {{$user->note}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="FollowData">
                        <div class="DataItems d-flex align-items-center justify-content-around CairoSemiBold bg-white m-auto">
                            <div class="Item text-center">
                                <h5>المنشورات</h5>
                                <h5>{{$count_total}}</h5>
                            </div>
                            <div class="Item text-center">
                                <h5>المتابعون</h5>
                                <h5>{{$followers}}</h5>
                            </div>
                            <div class="Item text-center">
                                <h5>يتابع</h5>
                                <h5>{{$follow}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="MyProducts MyProductsspecial">
                    <div class="row">
                        @foreach($clothes_items as $items)
                            <div class="col-lg-6 col-md-12 MyProductsItem col-sm-6 col-12">
                                <div class="ProductItem d-flex align-items-center ">
                                    <a class="ImgProduct" href="{{route("DetailProduct").'?id='.$items->id . '&cat_id='.$items->cat_id}}">
                                        <img src="{{asset('/assets/tmp/'.$items->image)}}">
                                    </a>
                                    <div class="InfoProduct mt-2 CariaRegular ms-md ms-sm-3 ms-0">
                                        <div class="nameProduct mb-2 d-flex align-items-center justify-content-between">
                                            <h6>{{$items->title_ar}}</h6>
                                            <i class="fal fa-heart"></i>
                                        </div>
                                        <div class="describeProduct">
                                            <p>
                                               {{$items->note_ar}}
                                            </p>
                                        </div>
                                        <div class="LinksProduct d-flex align-items-center justify-content-between">
                                            <p class="m-0 text-center">
                                                {{$items->price}} د.ك
                                            </p>
                                            <div class="SocialLinks d-flex align-items-center justify-content-start">
                                                <a href="#" class="text-decoration-none me-2" style="color: #459652;">
                                                    <i class="fas fa-sms"></i>
                                                </a>
                                                <a href="Chat.html" class="text-decoration-none me-2"
                                                   style="color: #5C4DB1;">
                                                    <i class="fas fa-comment-alt-dots"></i>
                                                </a>
                                                <a href="#" class="text-decoration-none me-2" style="color: #459652;">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                                <a href="#" class="text-decoration-none me-2" style="color: #FF5A5A;">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection