@if($category->number_rooms != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> عدد الغرف </label>
        <input type="text" class="form-control delEffect" name="RoomCount"
               placeholder="عدد الغرف">
    </div>
@else
    {{''}}
@endif
@if($category->swimming_pool != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> حمام السباحة </label>
{{--        <input type="number" class="form-control delEffect" name="CountSwimmingPool"--}}
{{--               placeholder="عدد برك سباحه">--}}
        <select name="CountSwimmingPool" class="form-control ">
            <option value=""> </option>
            <option value="1"> يوجد</option>
            <option value="0">لا يوجد</option>

        </select>
    </div>
@else
    {{''}}
@endif
@if($category->Jim != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> صالة رياضة </label>
{{--        <input type="text" class="form-control delEffect" name="CountJim"--}}
{{--               placeholder="صالة رياضة">--}}
        <select name="CountJim" class="form-control ">
            <option value=""> </option>
            <option value="1"> يوجد</option>
            <option value="0">لا يوجد</option>

        </select>
    </div>
@else
    {{''}}
@endif
@if($category->working_condition != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> حالة العمل </label>
{{--        <input type="text" class="form-control delEffect" name="working_condition"--}}
{{--               placeholder="حالة العمل">--}}
        <select name="working_condition" class="form-control " >
            <option value=""> </option>
            <option value="0"> مستعمل </option>
            <option value="1">جديد</option>

        </select>
    </div>
@else
    {{''}}
@endif
@if($category->year != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> السنة </label>
        <input type="text" class="form-control delEffect" name="year"
               placeholder="السنة">
    </div>
@else
    {{''}}
@endif
@if($category->cere != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> قير </label>
{{--        <input type="text" class="form-control delEffect" name="cere"--}}
{{--               placeholder="القير">--}}
        <select name="cere" class="form-control ">
            <option value=""> </option>
            <option value="0"> اتوماتك</option>
            <option value="1">عادي</option>
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->number_cylinders != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> عدد الاسطوانات </label>
        <input type="number" class="form-control delEffect" name="number_cylinders"
               placeholder="عدد الاسطوانات">
    </div>
@else
    {{''}}
@endif
@if($category->brand != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> نوع الماركة </label>
{{--        <input type="text" class="form-control delEffect" name="brand"--}}
{{--               placeholder="ماركة">--}}
        <select name="brand" class="form-control ">
            <option value="2 "> ايفون </option>
            <option value="4 "> تست </option>
            <option value="10 "> ريالمي 10 </option>
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->salary != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> الراتب </label>
        <input type="text" class="form-control delEffect" name="salary"
               placeholder="الراتب">
    </div>
@else
    {{''}}
@endif
@if($category->educational_level != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> المستوى التعليمي </label>
{{--        <input type="text" class="form-control delEffect" name="educational_level"--}}
{{--               placeholder=" المستوى التعليمي">--}}
        <select name="educational_level" class="form-control ">
            <option value="7 "> دبلوم </option>
            <option value="8 "> بكالوريس </option>
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->specialization != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> التخصص </label>
{{--        <input type="text" class="form-control delEffect" name="specialization"--}}
{{--               placeholder="التخصص">--}}
        <select name="specialization" class="form-control " fdprocessedid="xdk6ho">
            <option value="3 "> تكنولوجيا المعلومات </option>
            <option value="9 "> هندسة حاسوب </option>
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->biography != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> سيرة شخصية </label>
        <input type="file" class="form-control delEffect" name="biography"
               placeholder="سيرة شخصية">
    </div>
@else
    {{''}}
@endif
@if($category->animal_type != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> نوع الحيوان </label>
{{--        <input type="text" class="form-control delEffect" name="animal_type"--}}
{{--               placeholder="نوع الحيوان">--}}
        <select name="animal_type" class="form-control ">
            <option value="5 "> كلب </option>
            <option value="6 "> قطة </option>
            <option value="19 "> غير ذلك </option>
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->fashion_type != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> نوع الملابس </label>
{{--        <input type="text" class="form-control delEffect" name="fashion_type"--}}
{{--               placeholder="نوع الملابس">--}}
        <select name="fashion_type" class="form-control ">
            <option value="11 "> زارا </option>
            <option value="12 "> Polo </option>
            <option value="13 "> دولتشي اند جابانا </option>
            <option value="14 "> فيرساتشي </option>
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->subjects != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> الموضوع </label>
        <input type="text" class="form-control delEffect" name="subjects"
               placeholder="الموضوع">
    </div>
@else
    {{''}}
@endif
