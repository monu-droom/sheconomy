@extends('frontend.layouts.app')

@section('content')
<?php 
    $user = Auth::user();
    $seller = \App\Shop::where('user_id', $user->id)->first();
?>
<section class="gry-bg py-4 profile">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-lg-12">
                <div class="main-content">
                    <!-- Page title -->
                    <div class="page-title">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                
                            </div>
                            <div class="col-md-6">
                                <div class="float-md-right">
                                    <!-- <ul class="breadcrumb">
                                        <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                        <li><a href="">{{ translate('Dashboard')}}</a></li>
                                        <li><a href="">{{ translate('Step 1')}}</a></li>
                                        <li><a href="">...</a></li>
                                        <li class="active"><a href="">{{ translate('Step 6')}}</a></li>
                                    </ul> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="arrow-steps clearfix">
                            <div class="step"> <span> Step 1</span> </div>
                            <div class="step"> <span>Step 2</span> </div>
                            <div class="step"> <span> Step 3</span> </div>
                            <div class="step"> <span>Step 4</span> </div>
                            <div class="step"> <span>Step 5</span> </div>
                            <div class="step current"> <span>Step 6</span> </div>
                        </div>
                    </div>
                    <div class="form-box bg-white mt-4">
                        <!-- <div class="form-box-title px-3 py-2">
                            {{ translate('Add Shipping Details')}}
                        </div> -->
                        <div class="form-box-content p-3">   
                            <!-- Nav tabs -->
                            <?php $shop = Auth::user()->shop; ?>
                            <ul class="nav nav-tabs" role="tablist">
                            @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                                <li class="col-md-6 nav-item">
                                    <a class="nav-link" href="{{ route('steps.product') }}">Products</a>
                                </li> 
                            @endif
                            @if($shop->seller_type == 'services' || $shop->seller_type == 'both')
                                <li class="col-md-6 nav-item">
                                    <a class="nav-link active" href="{{ route('steps.service') }}">Services</a>
                                </li>
                            @endif
                            </ul>     

                            <!-- Tab panes -->
                            <div class="tab-content">
                            <!-- Tab on start -->
                                <div id="goods" class="container tab-pane"><br>

                                <!-- Goods Item Form Start -->
                                
                    </div>
                </div>

                <div id="services" class="container tab-pane active"><br>

                <form class="" action="{{route('steps.add-service')}}" method="POST" enctype="multipart/form-data" id="choice_form">
                            @csrf
                    		<input type="hidden" name="added_by" value="seller">

                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('General')}} 
                                    <small><span>(Click <i class="fa fa-question-circle"></i> for guidelines)</span></small>
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Service Name')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" name="name" value="{{ old('name') }}" placeholder="{{ translate('Type a service name/title')}}" required>
                                            <!-- <button id="showHelp">?</button> -->
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter name/title of service to appear on this listing.</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Category')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-control mb-3 c-pointer" data-toggle="modal" data-target="#categorySelectModal" id="product_category">{{ translate('Click here to assign listing to a category')}}</div>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Assign closest category (subcategory 1 or 2) from predefined list. Your listing will be assigned under the category selected.</p>"><i class="fa fa-question-circle"></i></button>
                                            <input type="hidden" name="category_id" id="category_id" value="" required>
                                            <input type="hidden" name="subcategory_id" id="subcategory_id" value="" required>
                                            <input type="hidden" name="subsubcategory_id" id="subsubcategory_id" value="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Search Text')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3 tagsInput" name="tags[]" placeholder="{{ translate('Type search keywords') }}" data-role="tagsinput" required>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>When searching on Sheconomy homepage, buyers type general search words for a service. Predict & type search words that buyers may type for this listing. This listing will show up if search words in this field match words typed by buyers on Sheconomy searchbar on homepage.</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload Brochure')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="file" id="file-6" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" />
                                            <label for="file-6" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>As an option you can upload an existing brochure of the service in this listing. Your brochure will appear next to description in your listing.</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Upload Images ')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div id="product-images">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Thumbnail Image')}} <small>(290x300)</small> <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="thumbnail_img" id="file-2" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" onchange="thumbnailImageFile(event)" />
                                            <label for="file-2" class="mw-100 mb-3">
                                                <span class="ms-error-1"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image')}}
                                                </strong>
                                            </label>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>When your listing appears at a summary level, alongwith listings of other sellers in this category, single image uploaded here (thumbnail image) will show for this listing.</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Main Images')}} <small>(1100x1100)</small> <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="photos[]" id="photos-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" onchange="mainImageFile(event)" />
                                            <label for="photos-1" class="mw-100 mb-3">
                                                <span class="ms-error-2"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image')}}
                                                </strong>
                                            </label>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add additional images for your listing that will show once a buyer clicks this listing to view in detail. The first image you upload here can be the same image as your thumbnail image. The order in which you upload images here will be the order shown on the detail listing page.</p>"><i class="fa fa-question-circle"></i></button>                                                
                                        </div>
                                    </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{  translate('Add More') }}</button>
                                    </div>
                                    <div id="main_error" timeout=5000>
                                    <div id="thumb_error" timeout=5000>                                    
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Upload Videos')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Video From')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                                <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="video_provider">
                                                    <option value="youtube" {{ old('video_provider') == "youtube" ? 'selected' : '' }}>{{ translate('Youtube')}}</option>
            										<option value="dailymotion" {{ old('video_provider') == "dailymotion" ? 'selected' : '' }}>{{ translate('Dailymotion')}}</option>
            										<option value="vimeo" {{ old('video_provider') == "vimeo" ? 'selected' : '' }}>{{ translate('Vimeo')}}</option>
                                                </select>
                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>As an option, you can upload videos from youtube/dailymotion/vimeo. Select website to link video in case you wish to do so.</p>"><i class="fa fa-question-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Video URL')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" name="video_link" value="{{old('video_link')}}" placeholder="{{ translate('Video link')}}">
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>You can copy/paste web link or url of a video on youtube / dailymotion / vimeo. Once you copy/paste a link, the video will appear as part of this listing. </p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Meta Tags for Google search')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Meta Title')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="meta_title" value="{{old('meta_title')}}" class="form-control mb-3" placeholder="{{ translate('Meta Title')}}">
                                            <button type="button" href="#" class="text-danger ibutton"  data-toggle="popover" data-img="{{ static_asset('frontend/img/meta.png') }}"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Description')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <textarea name="meta_description" rows="8" class="form-control mb-3">{{old('meta_description')}}</textarea>
                                            <button type="button" href="#" class="text-danger ibutton"  data-toggle="popover" data-img="{{ static_asset('frontend/img/meta.png') }}"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Meta Image')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="meta_img" id="file-5" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                            <label for="file-5" class="mw-100 mb-3">
                                            <button type="button" href="#" class="text-danger ibutton"  data-toggle="popover" data-img="{{ static_asset('frontend/img/meta.png') }}"><i class="fa fa-question-circle"></i></button>
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image')}}
                                                </strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Price')}}
                                </div>
                                <div class="form-box-content p-3">
                                <?php 
                                $currency = Session::get('currency_code');
                                ?>
                                @if(strtolower($currency) == 'rupee')
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Unit Price') }} <span class="required-star">* </span><small style="font-size: 10px;">(Included tax)</small></label>
                                        </div>
                                        <div class="col-md-10">
                                        <input type="number" min="0" class="form-control mb-3" name="unit_price_ei" value="{{ old('unit_price_ei') }}" placeholder="{{ translate('Unit Price') }} ({{ translate('Base Price') }})" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the price of product in this listing. You may include additional shipping cost not covered when using the shipping calculator section of seller dashboard (shipping calculator section of dashboard allows you to charge one fixed shipping rate for all your listings irrespective of size and weight in each seperate listing).</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Unit Price') }} <span class="required-star">* </span><small style="font-size: 10px;">(Included tax)</small></label>
                                        </div>
                                        <div class="col-md-10">
                                        <input type="number" min="0" class="form-control mb-3" name="unit_price_nei" value="{{ old('unit_price_nei') }}" placeholder="{{ translate('Unit Price') }} ({{ translate('Base Price') }})" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the price of product in this listing. You may include additional shipping cost not covered when using the shipping calculator section of seller dashboard (shipping calculator section of dashboard allows you to charge one fixed shipping rate for all your listings irrespective of size and weight in each seperate listing).</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                @endif
                                    <!-- <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Purchase Price')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" min="0" value="0" step="0.01" class="form-control mb-3" name="purchase_price" placeholder="{{ translate('Purchase Price')}}" required>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add a purchase price of your service.</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Tax')}}</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="number" min="0" value="0" step="0.01" class="form-control mb-3" name="tax" placeholder="{{ translate('Tax')}}" required>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter tax (if any) on service price.</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                        <div class="col-4 col-md-2">
                                            <div class="mb-3">
                                                <select class="form-control selectpicker" name="tax_type" data-minimum-results-for-search="Infinity">
                                                    <option value="amount_usd" {{ old('tax_type') == "amount_usd" ? 'selected' : '' }}>$</option>
                                                    <option value="amount_inr" {{ old('tax_type') == "amount_inr" ? 'selected' : '' }}>₹</option>
                                                    <option value="percent" {{ old('tax_type') == "percent" ? 'selected' : '' }}>%</option>
                                                </select>
                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Select whether to apply tax as a fixed amount or a percentage of the service price</p>"><i class="fa fa-question-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Discount')}}</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="number" min="0" value="0" step="0.01" class="form-control mb-3" name="discount" placeholder="{{ translate('Discount')}}" required>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter any discount you want to provide on service price</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                        <div class="col-4 col-md-2">
                                            <div class="mb-3">
                                                <select class="form-control selectpicker" name="discount_type" data-minimum-results-for-search="Infinity">
                                                    <option value="amount_usd" {{ old('discount_type') == "amount_usd" ? 'selected' : '' }}>$</option>
                                                    <option value="amount_inr" {{ old('discount_type') == "amount_inr" ? 'selected' : '' }}>₹</option>
                                                    <option value="percent" {{ old('discount_type') == "percent" ? 'selected' : '' }}>%</option>
                                                </select>
                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Select whether to apply discount as a fixed amount or a percentage of the service price</p>"><i class="fa fa-question-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Service Description')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Service Description')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                                <textarea class="editor" name="description"> {{old('description')}}</textarea>
                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter description of this listing to adequately explain service being provided including its benefits, features, terms, and what all is included or not included with this service. The more details you give, better are your chances of customers buying your services. Step into the shoes of your customers to cater to their needs. Be transparent and honest to win your customer's trust. Market your services responsibly so that buyer expectations are fulfilled correctly. Repeat business is the key to your success.</p>"><i class="fa fa-question-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box mt-4 text-right">
                                <button type="submit" class="btn btn-styled btn-base-1">{{  translate('Save') }}</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="categorySelectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('Select Category') }}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="target-category heading-6">
                        <span class="mr-3">{{ translate('Target Category')}}:</span>
                        <span>{{ translate('category')}} > {{ translate('subcategory')}} > {{ translate('subsubcategory')}}</span>
                    </div>
                    <div class="row no-gutters modal-categories mt-4 mb-2">
                        <div class="col-4">
                            <div class="modal-category-box c-scrollbar">
                                <div class="sort-by-box">
                                    <form role="form" class="search-widget">
                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search Category') }}" onkeyup="filterListItems(this, 'categories')">
                                        <button type="button" class="btn-inner">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="modal-category-list has-right-arrow">
                                    <ul id="categories">
                                        @foreach ($categories as $key => $category)
                                            <li onclick="get_subcategories_by_category(this, {{ $category->id }})">{{  __($category->name) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="modal-category-box c-scrollbar" id="subcategory_list">
                                <div class="sort-by-box">
                                    <form role="form" class="search-widget">
                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search SubCategory 1') }}" onkeyup="filterListItems(this, 'subcategories')">
                                        <button type="button" class="btn-inner">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="modal-category-list has-right-arrow">
                                    <ul id="subcategories">

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="modal-category-box c-scrollbar" id="subsubcategory_list">
                                <div class="sort-by-box">
                                    <form role="form" class="search-widget">
                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search SubCategory 2') }}" onkeyup="filterListItems(this, 'subsubcategories')">
                                        <button type="button" class="btn-inner">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="modal-category-list">
                                    <ul id="subsubcategories">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('cancel')}}</button>
                    <button type="button" class="btn btn-primary" onclick="closeModal()">{{ translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script type="text/javascript">
        var category_name = "";
        var subcategory_name = "";
        var subsubcategory_name = "";

        var category_id = null;
        var subcategory_id = null;
        var subsubcategory_id = null;

        $(document).ready(function(){
            $('#subcategory_list').hide();
            $('#subsubcategory_list').hide();
        });

        function list_item_highlight(el){
            $(el).parent().children().each(function(){
                $(this).removeClass('selected');
            });
            $(el).addClass('selected');
        }

        function get_subcategories_by_category(el, cat_id){
            list_item_highlight(el);
            category_id = cat_id;
            subcategory_id = null;
            subsubcategory_id = null;
            category_name = $(el).html();
            $('#subcategories').html(null);
            $('#subsubcategory_list').hide();
            $.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
                for (var i = 0; i < data.length; i++) {
                    $('#subcategories').append('<li onclick="get_subsubcategories_by_subcategory(this, '+data[i].id+')">'+data[i].name+'</li>');
                }
                $('#subcategory_list').show();
            });
        }

        function get_subsubcategories_by_subcategory(el, subcat_id){
            list_item_highlight(el);
            subcategory_id = subcat_id;
            subsubcategory_id = null;
            subcategory_name = $(el).html();
            $('#subsubcategories').html(null);
            $.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
                for (var i = 0; i < data.length; i++) {
                    $('#subsubcategories').append('<li onclick="confirm_subsubcategory(this, '+data[i].id+')">'+data[i].name+'</li>');
                }
                $('#subsubcategory_list').show();
            });
        }

        function confirm_subsubcategory(el, subsubcat_id){
            list_item_highlight(el);
            subsubcategory_id = subsubcat_id;
            subsubcategory_name = $(el).html();
    	}

        function get_attributes_by_subsubcategory(subsubcategory_id){
    		$.post('{{ route('subsubcategories.get_attributes_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
    		    $('#choice_attributes').html(null);
    		    for (var i = 0; i < data.length; i++) {
    		        $('#choice_attributes').append($('<option>', {
    		            value: data[i].id,
    		            text: data[i].name
    		        }));
    		    }
    		});
    	}

        function filterListItems(el, list){
            filter = el.value.toUpperCase();
            li = $('#'+list).children();
            for (i = 0; i < li.length; i++) {
                if ($(li[i]).html().toUpperCase().indexOf(filter) > -1) {
                    $(li[i]).show();
                } else {
                    $(li[i]).hide();
                }
            }
        }

        function closeModal(){
            if(category_id > 0 && subcategory_id > 0){
                $('#category_id').val(category_id);
                $('#subcategory_id').val(subcategory_id);
                $('#subsubcategory_id').val(subsubcategory_id);
                $('#product_category').html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
                $('#categorySelectModal').modal('hide');
            }
            else{
                alert('Please choose categories...');
                console.log(category_id);
                console.log(subcategory_id);
                console.log(subsubcategory_id);
            }
        }

        //var i = 0;
        function add_more_customer_choice_option(i, name){
    		$('#customer_choice_options').append('<div class="row mb-3"><div class="col-8 col-md-3 order-1 order-md-0"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly></div><div class="col-12 col-md-7 col-xl-8 order-3 order-md-0 mt-2 mt-md-0"><input type="text" class="form-control tagsInput" name="choice_options_'+i+'[]" placeholder="{{ translate('Enter choice values') }}" onchange="update_sku()"></div><div class="col-4 col-xl-1 col-md-2 order-2 order-md-0 text-right"><button type="button" onclick="delete_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button></div></div>');
    		// i++;
            $('.tagsInput').tagsinput('items');
    	}

    	$('input[name="colors_active"]').on('change', function() {
    	    if(!$('input[name="colors_active"]').is(':checked')){
    			$('#colors').prop('disabled', true);
    		}
    		else{
    			$('#colors').prop('disabled', false);
    		}
    		update_sku();
    	});

    	$('#colors').on('change', function() {
    	    update_sku();
    	});

    	$('input[name="unit_price"]').on('keyup', function() {
    	    update_sku();
    	});

        $('input[name="name"]').on('keyup', function() {
    	    update_sku();
    	});

        $('#choice_attributes').on('change', function() {
    		$('#customer_choice_options').html(null);
    		$.each($("#choice_attributes option:selected"), function(){
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
    		update_sku();
    	});

    	function delete_row(em){
    		$(em).closest('.row').remove();
    		update_sku();
    	}

    	function update_sku(){
            $.ajax({
    		   type:"POST",
    		   url:'{{ route('products.sku_combination') }}',
    		   data:$('#choice_form').serialize(),
    		   success: function(data){
    			   $('#sku_combination').html(data);
    			   if (data.length > 1) {
    				   $('#quantity').hide();
    			   }
    			   else {
    			   		$('#quantity').show();
    			   }
    		   }
    	   });
    	}

        var photo_id = 2;
        function add_more_slider_image(){
            var photoAdd =  '<div class="row">';
            photoAdd +=  '<div class="col-2">';
            photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>';
            photoAdd +=  '</div>';
            photoAdd +=  '<div class="col-10">';
            photoAdd +=  '<input type="file" name="photos[]" id="photos-'+photo_id+'" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
            photoAdd +=  '<label for="photos-'+photo_id+'" class="mw-100 mb-3">';
            photoAdd +=  '<span></span>';
            photoAdd +=  '<strong>';
            photoAdd +=  '<i class="fa fa-upload"></i>';
            photoAdd +=  "{{ translate('Choose image')}}";
            photoAdd +=  '</strong>';
            photoAdd +=  '</label>';
            photoAdd +=  '</div>';
            photoAdd +=  '</div>';
            $('#product-images').append(photoAdd);

            photo_id++;
            imageInputInitialize();
        }
        function delete_this_row(em){
            $(em).closest('.row').remove();
        }

    </script>


<script type="text/javascript">
    function thumbnailImageFile() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("file-2");
 
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
                    if (width > 290 || height > 300) {
                        $('#thumb_error').append('<span id="thumb" style="color:red">**Thumbnail Image : Width and height must be 290x300**</span>');
                        var spans = $('.ms-error-1');
                        spans.hide();
                        return false;
                    }else{
                        $('#thumb_error').remove();
                        var spans = $('.ms-error-1');
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

<script type="text/javascript">
    $('#file-2').on('change', function() { 
        $(".ms-usereditor span[class^='ms-error']:contains('External Data')").hide()      
    });
    $('#photos-1').on('change', function() {
        $(".ms-usereditor span[class^='ms-error']:contains('External Data')").hide()
    });
function mainImageFile() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("photos-1");
 
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
                    if (width < 1100 || height < 1100) {
                        $('#main_error').append('<span id="main" style="color:red">**Main Image : Width and height must be or greater than 1100px **</span>');
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
