@extends('frontend.layouts.app')

@section('content')
    
    <?php
        $countries = \App\Country::all();
        $user = Auth::user();
        $seller = \App\Seller::where('user_id', $user->id)->first();
        $shop = \App\Shop::where('user_id', $user->id)->first();
    ?>
    <style>
    .ibutton{
  position:absolute;
  right: 20px;
  top: 4px;
  border:none;
  height:20px;
  width:20px;
  border-radius:100%;
  outline:none;
  text-align:center;
  font-weight:bold;
  padding:2px;
}

</style>
<style>

.outerDivFull { margin:50px; } 

.switchToggle input[type=checkbox]{height: 0; width: 0; visibility: hidden; position: absolute; }
.switchToggle label {cursor: pointer; text-indent: -9999px; width: 70px; max-width: 70px; height: 30px; background: #d1d1d1; display: block; border-radius: 100px; position: relative; }
.switchToggle label:after {content: ''; position: absolute; top: 2px; left: 2px; width: 26px; height: 26px; background: #fff; border-radius: 90px; transition: 0.3s; }
.switchToggle input:checked + label, .switchToggle input:checked + input + label  {background: #ED4C67; }
.switchToggle input + label:before, .switchToggle input + input + label:before {content: 'No'; position: absolute; top: 5px; left: 35px; width: 26px; height: 26px; border-radius: 90px; transition: 0.3s; text-indent: 0; color: #fff; }
.switchToggle input:checked + label:before, .switchToggle input:checked + input + label:before {content: 'Yes'; position: absolute; top: 5px; left: 10px; width: 26px; height: 26px; border-radius: 90px; transition: 0.3s; text-indent: 0; color: #fff; }
.switchToggle input:checked + label:after, .switchToggle input:checked + input + label:after {left: calc(100% - 2px); transform: translateX(-100%); }
.switchToggle label:active:after {width: 60px; } 
.toggle-switchArea { margin: 10px 0 10px 0; }
</style>
<script>
$(document).ready(function(){
	 $('[data-toggle="popover"]').popover({
          trigger: 'click',
          html: true,
          content: function () {
		return '<img class="img-fluid" src="'+$(this).data('img') + '" />';
          },
    }) 
});
</script>
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
                                        {{ translate('Update your product')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li><a href="{{ route('seller.products') }}">{{ translate('Products')}}</a></li>
                                            <li class="active"><a href="{{ route('seller.products.edit', $product->id) }}">{{ translate('Edit Product')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="" action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
                            <input name="_method" type="hidden" value="POST">
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            @csrf
                    		<input type="hidden" name="added_by" value="seller">

                            
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('General')}} 
                                    <small><span>(Click <i class="fa fa-question-circle text-danger"></i> for guidelines)</span></small>
                                    <div>
                                    <small><span>(<a href="{{ route('prohibited_list') }}" target="_blank"><span style="text-decoration: underline;">Click here</span></a> to check the prohibited product list which you can't upload on SHEconomy)</span></small> 
                                    </div>
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Product Name')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" id="first" name="name" placeholder="{{ translate('Type a product name/title')}}" value="{{ ($product->name) }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter name/title of product to appear on this listing.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('SKU')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" id="second" name="product_sku" value="{{ ($product->product_sku) }}" placeholder="{{ translate('Type a SKU here')}}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter SKU for your product</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Category')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                        @if ($product->subsubcategory != null)
                                                <div class="form-control mb-3 c-pointer" data-toggle="modal" data-target="#categorySelectModal" id="product_category">{{ $product->category->name.'>'.$product->subcategory->name.'>'.$product->subsubcategory->name }}</div>
                                            @else
                                                <div class="form-control mb-3 c-pointer" data-toggle="modal" data-target="#categorySelectModal" id="product_category">{{ $product->category->name.'>'.$product->subcategory->name }}</div>
                                            @endif
                                            <input type="hidden" name="category_id" id="category_id" value="{{ $product->category_id }}" required>
                                            <input type="hidden" name="subcategory_id" id="subcategory_id" value="{{ $product->subcategory_id }}" required>
                                            <input type="hidden" name="subsubcategory_id" id="subsubcategory_id" value="{{ $product->subsubcategory_id }}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Assign closest category (subcategory 1 or 2) from predefined list. Your listing will be assigned under the category selected.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Product Brand')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                                <select class="form-control mb-3 selectpicker" data-placeholder="{{ translate('Select a brand') }}" id="brands" name="brand_id">
                                                    <option value="">{{ ('Select Brand') }}</option>
                                                    @foreach (\App\Brand::all() as $brand)
                                                    <option value="{{ $brand->id }}" @if($brand->id == $product->brand_id) selected @endif>{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                                <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Optional field. Select a brand name of the product from the dropdown menu. If you want to register your brand to appear in the dropdown menu, you can <a href=''>click here  to register</a>.</p>"><i class="fa fa-question-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Manufactured By')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class ='form-control selectpicker' name="country" id="country">
                                            @if($product->country != null)
                                                <option value="{{$product->country}}">{{ $product->country }}</option>
                                            @endif
                                                <option value="">--Select Country--</option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->name}}">{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the product unit.</p>"><i class="fa fa-info-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Product Unit')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" name="unit" placeholder="{{ translate('Product unit') }}" value="{{ $product->unit }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your product unit in ml, gram, piece, each, etc. For example, it can be 1 Piece, 5 Pieces (500 grams each piece), 1 Piece (100 ml), 1 Piece (500 ml), etc. Please make sure total quantity and size of each product unit are clearly mentioned to buyers to avoid confusion.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Product Weight')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class ='form-control' name="weight" id="weight">
                                            @if($product->weight == '')
                                                <option value="">--Select Product Weight--</option>
                                            @endif
                                                <option value="rate_100" @if($product->weight == 'rate_100') selected @endif>0 to 100gm</option>
                                                <option value="rate_500" @if($product->weight == 'rate_500') selected @endif>101 to 500gm</option>
                                                <option value="rate_1000" @if($product->weight == 'rate_1000') selected @endif>501 to 1000gm</option>
                                                <option value="rate_1500" @if($product->weight == 'rate_1500') selected @endif>1001 to 1500gm</option>
                                                <option value="rate_2000" @if($product->weight == 'rate_2000') selected @endif>1501 to 2000gm</option>
                                                <option value="rate_2500" @if($product->weight == 'rate_2500') selected @endif>2001 to 2500gm</option>
                                                <option value="rate_3000" @if($product->weight == 'rate_3000') selected @endif>2501 to 3000gm</option>
                                                <option value="rate_3500" @if($product->weight == 'rate_3500') selected @endif>3001 to 3500gm</option>
                                                <option value="rate_4000" @if($product->weight == 'rate_4000') selected @endif>3501 to 4000gm</option>
                                                <option value="rate_4500" @if($product->weight == 'rate_4500') selected @endif>4001 to 4500gm</option>
                                                <option value="rate_5000" @if($product->weight == 'rate_5000') selected @endif>4501 to 5000gm</option>
                                            </select>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Select weight of product lies under which range</p>"><i class="fa fa-info-circle"></i></a>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Minimum Qty.')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control mb-3" name="min_qty" value="@if($product->min_qty <= 1){{1}}@else{{$product->min_qty}}@endif" min="1" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the minimum quantity a buyer must purchase under this listing. For example, if you are willing to ship just 1 product unit (as defined in product unit field above) per order, then enter 1 in this field. If a buyer must purchase 5 product units per order, then enter 5.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Search Text')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3 tagsInput" name="tags[]" placeholder="{{ translate('Type search keywords') }}" data-role="tagsinput" value="{{ $product->tags }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>When searching on Sheconomy homepage, buyers type general search words for a product. Predict & type search words that buyers may type for this listing. This listing will show up if search words in this field match words typed by buyers on Sheconomy searchbar on homepage.</p>"><i class="fa fa-question-circle"></i></a> 
                                        </div>
                                    </div>
                                    @php
                                        $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
                                    @endphp
                                    @if ($pos_addon != null && $pos_addon->activated == 1)
            							<div class="row mt-2">
            								<label class="col-md-2">{{ translate('Barcode')}}</label>
            								<div class="col-md-10">
            									<input type="text" class="form-control mb-3" name="barcode" placeholder="{{  translate('Barcode') }}">
            								</div>
            							</div>
                                    @endif

                                    @php
                                        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                                    @endphp
                                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
            							<div class="row mt-2">
            								<label class="col-md-2">{{ translate('Refundable')}}</label>
            								<div class="col-md-10">
            									<label class="switch" style="margin-top:5px;">
            										<input type="checkbox" name="refundable" checked>
            			                            <span class="slider round"></span></label>
            									</label>
                                                <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Switch to On (green highlight appears) if product in this listing is refundable. Or switch to Off (grey highlight appears) if product in this listing is non-refundable. In general, buyers prefer to buy products that are refundable.</p>"><i class="fa fa-question-circle"></i></a>
            								</div>
            							</div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Return Validity')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class ='form-control' name="return" id="return" required>
                                            @if($product->return_validity == '' || $product->return_validity == 0)
                                                <option value="">--Select Return Days--</option>                                                
                                            @endif
                                                <option value="7" @if($product->return_validity == 7)  selected @endif>7 Days</option>
                                                <option value="8" @if($product->return_validity == 8)  selected @endif>8 Days</option>
                                                <option value="9" @if($product->return_validity == 9)  selected @endif>9 Days</option>
                                                <option value="10" @if($product->return_validity == 10)  selected @endif>10 Days</option>
                                                <option value="11" @if($product->return_validity ==11)  selected @endif>11 Days</option>
                                                <option value="12" @if($product->return_validity == 12)  selected @endif>12 Days</option>
                                                <option value="13" @if($product->return_validity == 13)  selected @endif>13 Days</option>
                                                <option value="14" @if($product->return_validity == 14)  selected @endif>14 Days</option>
                                                <option value="15" @if($product->return_validity == 15)  selected @endif>15 Days</option>
                                            </select>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Select the number of days for returning a product.</p>"><i class="fa fa-info-circle"></i></a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Images')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div id="product-images">
                                      
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Thumbnail Image')}} <small>(290x300)</small> <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                @if ($product->thumbnail_img != null)
                                                    <div class="col-md-3">
                                                        <div class="img-upload-preview">
                                                            <img loading="lazy"  src="{{ my_asset($product->thumbnail_img) }}" alt="" class="img-responsive">
                                                            <input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
                                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" name="thumbnail_img" id="file-2" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" onchange="thumbnailImageFile(event)" />
                                            <label for="file-2" class="mw-100 mb-3">
                                                <span class="ms-error-1"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image')}}
                                                </strong>
                                            </label>
                                            @error('thumbnail_img')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-md-2">
                                                <label>{{ translate('Main Images')}} <small>(1100x1100)</small> <span class="required-star">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    @if ($product->photos != null)
                                                        @foreach (json_decode($product->photos) as $key => $photo)
                                                            <div class="col-md-3">
                                                                <div class="img-upload-preview">
                                                                    <img loading="lazy"  src="{{ my_asset($photo) }}" alt="" class="img-responsive">
                                                                    <input type="hidden" name="previous_photos[]" value="{{ $photo }}">
                                                                    <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <input type="file" name="photos[]" id="photos-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" onchange="mainImageFile(event)" />
                                                <label for="photos-1" class="mw-100 mb-3">
                                                <span class="ms-error-2"></span>
                                                    <strong>
                                                        <i class="fa fa-upload"></i>
                                                        {{ translate('Choose image')}}
                                                    </strong>
                                                </label>
                                                @error('photos')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
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
                                    {{ translate('Videos')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Video From')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                                <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="video_provider">
                                                    <option value="youtube" <?php if($product->video_provider == 'youtube') echo "selected";?> >{{ translate('Youtube')}}</option>
            										<option value="dailymotion" <?php if($product->video_provider == 'dailymotion') echo "selected";?> >{{ translate('Dailymotion')}}</option>
            										<option value="vimeo" <?php if($product->video_provider == 'vimeo') echo "selected";?> >{{ translate('Vimeo')}}</option>
                                                </select>
                                                <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>As an option, you can upload videos from youtube/dailymotion/vimeo. Select website to link video in case you wish to do so.</p>"><i class="fa fa-question-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Video URL')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                        <input type="text" class="form-control mb-3" name="video_link" placeholder="{{ translate('Video link')}}" value="{{ $product->video_link }}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>You can copy/paste web link or url of a video on youtube / dailymotion / vimeo. Once you copy/paste a link, the video will appear as part of this listing. </p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Meta Tags')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Meta Title')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="meta_title" class="form-control mb-3" value="{{ $product->meta_title }}" placeholder="{{ translate('Meta Title')}}">
                                            <a type="button" href="#" class="text-danger ibutton"  data-toggle="popover" data-img="{{ static_asset('frontend/img/meta.png') }}"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Description')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <textarea name="meta_description" rows="8" class="form-control mb-3">{{ $product->meta_description }}</textarea>
                                            <a type="button" href="#" class="text-danger ibutton"  data-toggle="popover" data-img="{{ static_asset('frontend/img/meta.png') }}"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Meta Image')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                @if ($product->meta_img != null)
                                                    <div class="col-md-3">
                                                        <div class="img-upload-preview">
                                                            <img loading="lazy"  src="{{ my_asset($product->meta_img) }}" alt="" class="img-responsive">
                                                            <input type="hidden" name="previous_meta_img" value="{{ $product->meta_img }}">
                                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" name="meta_img" id="file-5" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                            <label for="file-5" class="mw-100 mb-3">
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
                                    {{ translate('Customer Choice')}}
                                </div>
                                <div class="form-box-content p-3">
                                <div class="row mb-3">
                                        <div class="col-8 col-md-3 order-1 order-md-0">
        									<input type="text" class="form-control" value="{{ translate('Colors')}}" disabled>
        								</div>
        								<div class="col-12 col-md-7 col-xl-8 order-3 order-md-0 mt-2 mt-md-0">
        									<select class="form-control color-var-select" name="colors[]" id="colors" multiple>
                                                @foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
        											<option value="{{ $color->code }}" <?php if(in_array($color->code, json_decode($product->colors))) echo 'selected'?> >{{ $color->name }}</option>
        										@endforeach
        									</select>
        								</div>
        								<div class="col-4 col-xl-1 col-md-2 order-2 order-md-0 text-right">
        									<label class="switch" style="margin-top:5px;">
                                                <input value="1" type="checkbox" name="colors_active" <?php if(count(json_decode($product->colors)) > 0) echo "checked";?> >
        										<span class="slider round"></span>
        									</label>
        								</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>{{ translate('Attributes')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="">
                                                <select name="choice_attributes[]" id="choice_attributes" class="form-control selectpicker" multiple data-placeholder="{{ translate('Choose Attributes') }}">
                                                    @foreach (\App\Attribute::all() as $key => $attribute)
            											<option value="{{ $attribute->id }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>{{ $attribute->name }}</option>
            										@endforeach
            			                        </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
        								<p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
        							</div>
                                    <div id="customer_choice_options">
                                        @foreach (json_decode($product->choice_options) as $key => $choice_option)
        									<div class="row mb-3">
        										<div class="col-8 col-md-3 order-1 order-md-0">
        											<input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
        											<input type="text" class="form-control" name="choice[]" value="{{ \App\Attribute::find($choice_option->attribute_id)->name }}" placeholder="{{ translate('Choice Title') }}" disabled>
        										</div>
        										<div class="col-12 col-md-7 col-xl-8 order-3 order-md-0 mt-2 mt-md-0">
        											<input type="text" class="form-control" id="choice_option_id" name="choice_options_{{ $choice_option->attribute_id }}[]" placeholder="{{ translate('Enter choice values') }}" value="{{ implode(',', $choice_option->values) }}" data-role="tagsinput" onchange="update_sku()">
        										</div>
        										<div class="col-4 col-xl-1 col-md-2 order-2 order-md-0 text-right">
                                                    <button type="button" onclick="delete_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>
                                                </div>
        									</div>
        								@endforeach
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-2">
        									<button type="button" class="btn btn-info" onclick="add_more_customer_choice_option()">{{  translate('Add More Customer Choice Option') }}</button>
        								</div>
                                    </div> --}}
                                </div>
                            </div>
                            
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Shipping Details')}}
                                    <span><small>(Only applicable to the customers having same city)</small></span>
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="mt-1">{{ translate('Free Shipping')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="switchToggle">
                                                    <input type="checkbox" id="switch" name="free_shipping" >
                                                    <label for="switch">Toggle</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Price') }}
                                </div>
                                <div class="form-box-content p-3">
                                <?php 
                                $currency = Session::get('currency_code');
                                ?>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Unit Price') }} <span class="required-star">*</span><small style="font-size: 10px;">(Included tax)</small></label>
                                        </div>
                                        <div class="col-md-5">
                                        <input type="number" min="0" class="form-control mb-3" name="unit_price_ei" placeholder="{{ translate('Unit Price In INR') }}"  value="{{$product->unit_price}}" required>
                                        </div>
                                        <div class="col-md-5">
                                        <input type="number"min="0"  class="form-control mb-3" name="unit_price_nei" placeholder="{{ translate('Unit Price In USD') }}"  value="{{ $product->price_usd }}" required>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Purchase Price')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" min="0" step="0.01" class="form-control mb-3" name="purchase_price" placeholder="{{ translate('Purchase Price')}}" value="{{$product->purchase_price}}" required>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Tax')}}</label>
                                        </div>
                                        @if(strtolower($currency) == 'rupee')
                                        <?php 
                                            $pattern = 'Rs';
                                            $comma = ',';
                                            $tax_price_india = str_replace(array('Rs', '$', ','),'', single_price($product->tax));                                   
                                        ?>
                                        <div class="col-8">
                                            <input type="number" min="0" class="form-control mb-3" name="tax" placeholder="{{ translate('Tax')}}" value="@if($product->tax_type == 'percent'){{ $product->tax }}@else{{$tax_price_india}}@endif" required>
                                        </div>
                                        @else
                                        <div class="col-8">
                                            <input type="number" min="0" class="form-control mb-3" name="tax" placeholder="{{ translate('Tax')}}" value="{{$product->tax}}" required>
                                        </div>
                                        @endif
                                        <div class="col-md-2 col-4">
                                            <div class="mb-3">
                                                <select class="form-control selectpicker" name="tax_type" data-minimum-results-for-search="Infinity">
                                                    <option value="amount_usd" <?php if($product->tax_type == 'amount_usd') echo "selected";?> >$</option>
                                                    <option value="amount_inr" <?php if($product->tax_type == 'amount_inr') echo "selected";?> >₹</option>
                                                    <option value="percent" <?php if($product->tax_type == 'percent') echo "selected";?> >%</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Discount')}}</label>
                                        </div>
                                        @if(strtolower($currency) == 'rupee')
                                        <?php 
                                            $pattern = 'Rs';
                                            $comma = ',';
                                            $discount_price_india = str_replace(array('Rs', '$', ','), '', single_price($product->discount));                                   
                                        ?>
                                        <div class="col-8">
                                            <input type="number" min="0" class="form-control mb-3" name="discount" placeholder="{{ translate('Discount')}}" value="@if($product->discount_type == 'percent'){{ $product->discount }}@else{{ $discount_price_india }}@endif">
                                        </div>
                                        @else
                                        <div class="col-8">
                                            <input type="number" min="0" class="form-control mb-3" name="discount" placeholder="{{ translate('Discount')}}" value="{{ $product->discount }}">
                                        </div>
                                        @endif
                                        <div class="col-md-2 col-4">
                                            <div class="mb-3">
                                                <select class="form-control selectpicker" name="discount_type" data-minimum-results-for-search="Infinity">
                                                    <option value="amount_usd" <?php if($product->discount_type == 'amount_usd') echo "selected";?> >$</option>
                                                    <option value="amount_inr" <?php if($product->discount_type == 'amount_inr') echo "selected";?> >₹</option>
            	                                	<option value="percent" <?php if($product->discount_type == 'percent') echo "selected";?> >%</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="quantity">
                                        <div class="col-md-2">
                                            <label>{{ translate('Quantity')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" step="1" class="form-control mb-3" name="current_stock" placeholder="{{ translate('Quantity')}}" value="{{$product->current_stock}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12" id="sku_combination">

                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Description')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Description')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                            <textarea class="editor" name="description">{{$product->description}}</textarea>
                                                <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter description of this listing to adequately explain product being provided including its benefits, features, historical and cultural significance of product including manufacturing techniques (if any), terms, and what all is included or not included with this product. The more details you give, better are your chances of customers buying your product. Step into the shoes of your customers to cater to their needs. Be transparent and honest to win your customer's trust. Market your product responsibly so that buyer's expectations are fulfilled correctly.</p>"><i class="fa fa-question-circle"></i></a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Upload Product Brochure')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload Brochure')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="pdf" id="file-6" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
                                            <label for="file-6" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Upload Brochure')}}
                                                </strong>
                                            </label>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>As an option you can upload an existing brochure of the product in this listing. Your brochure will appear next to description in your listing.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box mt-4 text-right">
                                <button type="submit" class="btn btn-styled btn-base-1" id="submit_btn">{{  translate('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="categorySelectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('Select Category')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="target-category heading-6">
                        <span class="mr-3">{{ translate('Target Category')}}:</span>
                        <span>{{ translate('Category')}} > {{ translate('Subcategory')}} > {{ translate('Sub Subcategory')}}</span>
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
                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search SubCategory') }}" onkeyup="filterListItems(this, 'subcategories')">
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
                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search SubSubCategory') }}" onkeyup="filterListItems(this, 'subsubcategories')">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel')}}</button>
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
            //get_attributes_by_subsubcategory($('#subsubcategory_id').val());
            update_sku();

            $('.remove-files').on('click', function(){
                $(this).parents(".col-md-3").remove();
            });
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

        // function get_brands_by_subsubcategory(subsubcat_id){
        //     $('#brands').html(null);
    	// 	$.post('{{ route('subsubcategories.get_brands_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
    	// 	    for (var i = 0; i < data.length; i++) {
    	// 	        $('#brands').append($('<option>', {
    	// 	            value: data[i].id,
    	// 	            text: data[i].name
    	// 	        }));
    	// 	    }
    	// 	});
    	// }

        function get_attributes_by_subsubcategory(subsubcategory_id){
            // var subsubcategory_id = $('#subsubcategories').val();
    		$.post('{{ route('subsubcategories.get_attributes_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
    		    $('#choice_attributes').html(null);
    		    for (var i = 0; i < data.length; i++) {
    		        $('#choice_attributes').append($('<option>', {
    		            value: data[i].id,
    		            text: data[i].name
    		        }));
    		    }
    			$("#choice_attributes > option").each(function() {
    				var str = @php echo $product->attributes @endphp;
    		        $("#choice_attributes").val(str).change();
    		    });
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
            if(category_id > 0 && subcategory_id > 0 && subsubcategory_id > 0){
                $('#category_id').val(category_id);
                $('#subcategory_id').val(subcategory_id);
                $('#subsubcategory_id').val(subsubcategory_id);
                $('#product_category').html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
                $('#categorySelectModal').modal('hide');
                //get_brands_by_subsubcategory(subsubcategory_id);
                //get_attributes_by_subsubcategory(subsubcategory_id);
            }
            else{
                alert('Please choose categories...');
                console.log(category_id);
                console.log(subcategory_id);
                console.log(subsubcategory_id);
                //showAlert();
            }
        }

        // var i = $('input[name="choice_no[]"').last().val();
        // if(isNaN(i)){
    	// 	i =0;
    	// }

        
        function add_more_customer_choice_option(i, name){
            $.ajax({
                type:"POST",
                url:'{{ route('make.variant') }}',
                data:{_token:'{{ csrf_token() }}', i:i, name:name},
                success: function(data){
                    $('#customer_choice_options').append(data);
                }
            });    		
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

    	// $('input[name="unit_price"]').on('keyup', function() {
    	//     update_sku();
    	// });
        //
        // $('input[name="name"]').on('keyup', function() {
    	//     update_sku();
    	// });

        $('#choice_attributes').on('change', function() {
    		//$('#customer_choice_options').html(null);
    		$.each($("#choice_attributes option:selected"), function(j, attribute){
    			flag = false;
    			$('input[name="choice_no[]"]').each(function(i, choice_no) {
    				if($(attribute).val() == $(choice_no).val()){
    					flag = true;
    				}
    			});
                if(!flag){
    				add_more_customer_choice_option($(attribute).val(), $(attribute).text());
    			}
            });

    		var str = @php echo $product->attributes @endphp;

    		$.each(str, function(index, value){
    			flag = false;
    			$.each($("#choice_attributes option:selected"), function(j, attribute){
    				if(value == $(attribute).val()){
    					flag = true;
    				}
    			});
                if(!flag){
    				//console.log();
    				$('input[name="choice_no[]"][value="'+value+'"]').parent().parent().remove();
    			}
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
    		   url:'{{ route('products.sku_combination_edit') }}',
    		   data:$('#choice_form').serialize(),
    		   success: function(data){
                   $('#sku_combination').html(data);
                   if (!$('#colors').val() && !$('#choice_attributes').val()) {
                        $('#quantity').show();
                    }
    			   else if (data.length > 1) {
    				   $('#quantity').hide();
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

        var photo_id = 3;
        function add_more_variant_image(){
            var photoAdd =  '<div class="row">';
            photoAdd +=  '<div class="col-2">';
            photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>';
            photoAdd +=  '</div>';
            photoAdd +=  '<div class="col-10">';
            photoAdd +=  '<input type="file" name="variant_img[]" id="photo-'+photo_id+'" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
            photoAdd +=  '<label for="photo-'+photo_id+'" class="mw-100 mb-3">';
            photoAdd +=  '<span></span>';
            photoAdd +=  '<strong>';
            photoAdd +=  '<i class="fa fa-upload"></i>';
            photoAdd +=  "{{ translate('Choose image')}}";
            photoAdd +=  '</strong>';
            photoAdd +=  '</label>';
            photoAdd +=  '</div>';
            photoAdd +=  '</div>';
            $('#variant-images').append(photoAdd);

            photo_id++;
            imageInputInitialize();
        }
        function delete_this_row(em){
            $(em).closest('.row').remove();
        }

    </script>
    <script>
        $("#first").on('keyup',function(){
            $("#second").val( $(this).val().replace(/ /g, "-") );
        });
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