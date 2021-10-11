@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('Shop Settings')}}
                                        <a href="{{ route('shop.visit', $shop->domain) }}" class="btn btn-link btn-sm" target="_blank">({{ translate('Visit Shop')}})<i class="la la-external-link"></i>)</a>
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('shops.index') }}">{{ translate('Shop Settings')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="" action="{{ route('shop.post_home_settings', $shop->id) }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-box bg-white mt-4">
                            <div class="form-box-content p-3">
                                <div id="shop-slider-images">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload Home Page Banner Images')}} <small>(1400x400)</small></label>
                                            <a type="button" class="text-danger mb-4 ibutton" data-toggle="popover" data-content="<p>Under this section, you can upload multiple images/banners to appear on the homepage of your website. Banners will slide automatically if more than one banner is uploaded here. Click on choose image button to upload image file. Click on add more button to upload addtional image files.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                        <div class="offset-2 offset-md-0 col-10 col-md-10">
                                            <div class="row">
                                                @if ($shop->sliders != null)
                                                    @foreach (json_decode($shop->sliders) as $key => $sliders)
                                                        <div class="col-md-6">
                                                            <div class="img-upload-preview">
                                                                <img loading="lazy"  src="{{ my_asset($sliders) }}" alt="" class="img-fluid">
                                                                <input type="hidden" name="previous_sliders[]" value="{{ $sliders }}">
                                                                <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            
                                            <input type="file" name="sliders[]" id="slide-0" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" onchange="slideImageFile(event)" />
                                            
                                            <label for="slide-0" class="mw-100 mb-3">
                                                <span class="ms-error-2"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image')}}
                                                </strong>
                                            </label>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{  translate('Add More') }}</button>
                                </div>
                                <div id="main_error" timeout=5000></div>
                                <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Home Page Text Box')}}
                                        </div>
                                        <div class="col-md-10">
                                            <textarea name="home_text" rows="6" placeholder="Add text here to appear on your homepage" class="editor form-control mb-3">{{ $shop->home_text }}</textarea>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>You can enter text/welcome message that will appear on the homepage of your website.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    <script>
        var slide_id = 1;
        function add_more_slider_image(){
            var shopSliderAdd =  '<div class="row">';
            shopSliderAdd +=  '<div class="col-2">';
            shopSliderAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>';
            shopSliderAdd +=  '</div>';
            shopSliderAdd +=  '<div class="col-10">';
            shopSliderAdd +=  '<input type="file" name="sliders[]" id="slide-'+slide_id+'" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
            shopSliderAdd +=  '<label for="slide-'+slide_id+'" class="mw-100 mb-3">';
            shopSliderAdd +=  '<span></span>';
            shopSliderAdd +=  '<strong>';
            shopSliderAdd +=  '<i class="fa fa-upload"></i>';
            shopSliderAdd +=  "{{ translate('Choose image')}}";
            shopSliderAdd +=  '</strong>';
            shopSliderAdd +=  '</label>';
            shopSliderAdd +=  '</div>';
            shopSliderAdd +=  '</div>';
            $('#shop-slider-images').append(shopSliderAdd);

            slide_id++;
            imageInputInitialize();
        }
        function delete_this_row(em){
            $(em).closest('.row').remove();
        }


        $(document).ready(function(){
            $('.remove-files').on('click', function(){
                $(this).parents(".col-md-6").remove();
            });
        });
    </script>


<script type="text/javascript">
    function slideImageFile() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("slide-0");
 
    //Check whether the file is valid Image.
    var regex = new RegExp("([a-zA-Z0-9\s_()\\.\-:])+(.jpg|.png|.jpeg|.gif)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
        //Check whether HTML5 is supported.
        if (typeof (fileUpload.files) != "undefined") {
            //Initiate the FileReader object.
            var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                        
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
                    if (width != 1400 || height != 400) {
                        $('#main_error').append('<span id="main" style="color:red">**Slider Image : Width and height must be 1400px x 400px **</span>');
                        var spans = $('.ms-error-2');
                        spans.hide();
                        return false;
                    }else{
                        $('#main_error').remove();
                        var spans = $('.ms-error-2');                        
                        spans.show();                      
                    }
                };
 
            }
        } else {
            alert("This browser does not support HTML5.");
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        return false;
    }
}
</script>
@endsection
