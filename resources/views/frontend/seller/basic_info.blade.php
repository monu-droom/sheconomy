@extends('frontend.layouts.app')

@section('content')
<style>
img{
  max-width:180px;
}
input[type=file]{
padding:10px;
background:#2d2d2d;}
</style>
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
                                            <li class="active"><a href="">{{ translate('Store Heading')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <form class="" action="{{ route('shops.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="PATCH">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Store Heading')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Shop Name')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Shop Name')}}" name="name" value="{{ $shop->name }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your shopname here</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Company Name')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Company Name')}}" name="company_name"  value="{{ $shop->company_name }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your company name here</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    @if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping')
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>{{ translate('Shipping Cost')}} <span class="required-star">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="number" min="0" class="form-control mb-3" placeholder="{{ translate('Shipping Cost')}}" name="shipping_cost" value="{{ $shop->shipping_cost }}" required>
                                                <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your shopname here</p>"><i class="fa fa-question-circle"></i></a>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label>{{ translate('Pickup Points')}} <span class="required-star"></span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class="form-control mb-3 selectpicker" data-placeholder="{{ translate('Select Pickup Point') }}" id="pick_up_point" name="pick_up_point_id[]" multiple>
                                                @foreach (\App\PickupPoint::all() as $pick_up_point)
                                                    @if (Auth::user()->shop->pick_up_point_id != null)
                                                        <option value="{{ $pick_up_point->id }}" @if (in_array($pick_up_point->id, json_decode(Auth::user()->shop->pick_up_point_id))) selected @endif>{{ $pick_up_point->name }}</option>
                                                    @else
                                                        <option value="{{ $pick_up_point->id }}">{{ $pick_up_point->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Select your pickup points.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Logo')}} <small>({{ translate('120x120')}})</small></label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                @if($shop->logo != '')
                                                    <div class="col-md-3">
                                                        <div class="img-upload-preview">
                                                            <img loading="lazy"  src="{{ my_asset($shop->logo) }}" alt="" class="img-responsive">
                                                        </div>
                                                    </div>
                                                @endif

                                                <div id="hide" class="col-md-3">
                                                    <div class="img-upload-preview">
                                                        <img id="blah" src="http://placehold.it/180" alt="your image" />
                                                    </div>
                                                </div>
                                            </div>
                                            

                                            <input type="file" name="logo" id="file-2" onchange="readURL(this);" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                            <label for="file-2" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image')}}
                                                </strong>
                                            </label>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your logo here.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Shop Address')}}</label>
                                        </div>
                                        <div class="col-lg-10 mx-auto">    
                                            @if($shop->country == '' && $shop->state == '' && $shop->city == '') 
                                                <div class="border p-3 rounded mb-3 c-pointer text-center bg-light" onclick="add_new_address();">
                                                    <i class="la la-plus la-2x"></i>
                                                    <div class="alpha-7">{{ translate('Add Shop Address')}}</div>
                                                </div>
                                            @endif
                                            @if($shop->country != '' && $shop->state != '' && $shop->city != '')
                                            <div class="border p-3 pr-5 rounded mb-3 position-relative">
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Address')}}:</span>
                                                        <span class="strong-600 ml-2">{{ $shop->address }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Country')}}:</span>
                                                        <span class="strong-600 ml-2">{{ $shop->country }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('State')}}:</span>
                                                        <span class="strong-600 ml-2">{{ $shop->state }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('City')}}:</span>
                                                        <span class="strong-600 ml-2">{{ $shop->city }}</span>
                                                    </div>
                                                    <!-- <div>
                                                        <span class="alpha-6">{{ translate('Postal Code') }}:</span>
                                                        <span class="strong-600 ml-2">{{ $shop->postal_code }}</span>
                                                    </div> -->

                                                    <div class="dropdown position-absolute right-0 top-0">
                                                        <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                                                            <i class="la la-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            <span class="dropdown-item" onclick="add_new_address();">{{ translate('Edit')}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                    <!--  -->
                                             <div class="col-lg-10">
                                                <!-- <div class="border p-3 pr-5 rounded mb-3 position-relative">
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Address') }}:</span>
                                                        <span class="strong-600 ml-2"></span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Postal Code') }}:</span>
                                                        <span class="strong-600 ml-2"></span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('State')}}:</span>
                                                        <span class="strong-600 ml-2"></span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('City')}}:</span>
                                                        <span class="strong-600 ml-2"></span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Country')}}:</span>
                                                        <span class="strong-600 ml-2"></span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Phone')}}:</span>
                                                        <span class="strong-600 ml-2"></span>
                                                    </div>
                                                    
                                                        <div class="position-absolute right-0 bottom-0 pr-2 pb-3">
                                                            <span class="badge badge-primary bg-base-1">{{ translate('Default')}}</span>
                                                        </div>
                                                    
                                                    <div class="dropdown position-absolute right-0 top-0">
                                                        <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                                                            <i class="la la-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            {{-- <a class="dropdown-item" href="">Edit</a> --}}
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Address')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Address')}}" name="address" value="{{ $shop->address }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your address here.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Meta Title')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Meta Title')}}" name="meta_title" value="{{ $shop->meta_title }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add a meta title here.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Meta Description')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <textarea name="meta_description" rows="6" class="form-control mb-3" required>{{ $shop->meta_description }}</textarea>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add a meta description here.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Save')}}</button>
                            </div>
                        </form>
                        
                        <form class="" action="{{ route('shops.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="PATCH">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Social Media Link')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label ><i class="line-height-1_8 size-24 mr-2 fa fa-facebook bg-facebook c-white text-center"></i>{{ translate('Facebook')}} </label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Facebook')}}" name="facebook" @if($shop->facebook == '') value="https://facebook.com/" @else value="{{ $shop->facebook }}" @endif>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your facebook here</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label><i class="line-height-1_8 size-24 mr-2 fa fa-twitter bg-twitter c-white text-center"></i>{{ translate('Twitter')}} </label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Twitter')}}" name="twitter" @if($shop->twitter == '') value="https://twitter.com/" @else value="{{ $shop->twitter }}" @endif>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your twitter here</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label><i class="line-height-1_8 size-24 mr-2 fa fa-linkedin bg-facebook c-white text-center"></i>{{ translate('Linkedin')}} </label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Linkedin')}}" name="google" @if($shop->google == '') value="https://linkedin.com/" @else value="{{ $shop->google }}" @endif>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your linkedin here</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label><i class="line-height-1_8 size-24 mr-2 fa fa-youtube bg-youtube c-white text-center"></i>{{ translate('Youtube')}} </label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Youtube')}}" name="youtube" @if($shop->youtube == '') value="https://youtube.com/" @else value="{{ $shop->youtube }}" @endif>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your youtube here</p>"><i class="fa fa-question-circle"></i></a>
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
        </div>
    </section>

    <div class="modal fade" id="new-address-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('New Address')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-default" role="form" action="{{ route('shop.address') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Address')}}" name="address" value="{{ $shop->address }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Country')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="mb-3">
                                        <select class="form-control mb-3 selectpicker" data-placeholder="{{ translate('Select your country')}}" name="country" required>
                                            @foreach (\App\Country::where('status', 1)->get() as $key => $country)
                                                <option value="{{ $country->name }}" <?php if($shop->country == $country->name){ ?> selected <?php } ?>>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('State')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your State')}}" name="state" value="{{ $shop->state }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('City')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your City')}}" name="city" value="{{ $shop->city }}" required>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Postal code')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-base-1">{{  translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        function add_new_address(){
            $('#new-address-modal').modal('show');
        }
    </script>
    <script>
        $('#hide').hide();
         function readURL(input) {
            if (input.files && input.files[0]) {
                $('#hide').show();
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
