@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class="mt-4 mb-5">
            <div class="container">
                <div class="MyInfo mb-4">
                    <div class="MyInfoContainer">
                        <div class="MyData d-flex">
                            <div class="MyImg text-center position-relative">
                                <img src="img/Public/ss.jpg" class="w-100">
                            </div>
                            <div class="MyMainInfo mt-md-2 mt-4 ms-md-4 me-md-0 ms-2 me-2 CairoSemiBold  d-flex flex-column">
                                <div class="TopDiv d-flex justify-content-between mb-3">
                                    <div >
                                        <h4 class="mb-2">عزالدين أيمن حرزالله</h4>
                                        <div class="SocialLinks d-flex align-items-center justify-content-start">
                                            <a href="#" class="text-decoration-none me-2" style="color: #459652;">
                                                <i class="fas fa-sms"></i>
                                            </a>
                                            <a href="Chat.html" class="text-decoration-none me-2" style="color: #5C4DB1;">
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
                                    <div class="EditMyData mt-3">
                                        <a href="Edit.html" class="text-decoration-none">
                                            <i class="fas fa-edit me-md-2 me-0"></i>
                                            <span>تعديل المعلومات</span>
                                        </a>
                                    </div>
                                </div>
                                <p class="MyDescribe">
                                    هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="FollowData">
                        <div class="DataItems d-flex align-items-center justify-content-around CairoSemiBold bg-white m-auto">
                            <div class="Item text-center">
                                <h5>المنشورات</h5>
                                <h5>23</h5>
                            </div>
                            <div class="Item text-center">
                                <h5>المتابعون</h5>
                                <h5>333</h5>
                            </div>
                            <div class="Item text-center">
                                <h5>يتابع</h5>
                                <h5>850</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="PostAd EditPersonalData">
                    <h6 class="CairoSemiBold mb-4">
                        <i class="fa fa-plus me-2"></i>
                        <span>معلومات أساسية </span>
                    </h6>
                    <form action="" method="post">
                        <div class="row w-100 m-auto">
                            <div class="ItemAd col-12 CairoSemiBold mb-4">
                                <div class="Imgs">
                                    <div class=" row" id="ImgAds">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-6 addImgsAd">
                                            <div class="StyledAddImgs p-1">
                                                <input type="file" class="form-control delEffect w-25 d-none" id="uploadPersonImg" name="ImgsAds">
                                                <label class="CustomFile text-center d-flex flex-column align-items-center justify-content-center" for="uploadPersonImg">
                                                    <i class="fal fa-image"></i>
                                                    <p class="m-0">صورتك الشخصية </p>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="ImgDisplay mt-0 col-lg-2 col-md-2 col-sm-6 col-6 d-flex" id="PersonImgDisplay">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold">
                                <label class="mb-2">اسم ثلاثي </label>
                                <input type="text" class="form-control delEffect" name="Name" placeholder="(Porsche AG)بورشه " fdprocessedid="0c2mvc">
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2">الايميل</label>
                                <input type="email" class="form-control text-start delEffect" name="email" placeholder="(Porsche AG)بورشه " fdprocessedid="rualh">
                            </div>
                            <div class="ItemAd col-md-6 col-12 CairoSemiBold mt-4">
                                <label class="mb-2">رقم الجوال</label>
                                <input type="phone" class="form-control text-start delEffect" name="email" placeholder="(Porsche AG)بورشه " fdprocessedid="rualh">
                            </div>
                            <div class="ItemAd col-md-6 col-12 CairoSemiBold mt-4">
                                <label class="mb-2">رقم الواتس</label>
                                <input type="phone" class="form-control text-start delEffect" name="email" placeholder="(Porsche AG)بورشه " fdprocessedid="rualh">
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2"> السيرة الذاتية </label>
                                <textarea class="form-control delEffect" name="DescribeAd" rows="4" placeholder="هذا النص هو مثال لنص يمكن"></textarea>
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <button type="submit" class="btn BtnSubmit CairoSemiBold delEffect" fdprocessedid="vvn90n">حفظ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection