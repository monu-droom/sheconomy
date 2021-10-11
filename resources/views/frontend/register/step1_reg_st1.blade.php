@extends('frontend.layouts.app')

@section('content')

@if(empty($shop->seller_type))
<script>
$(function() {
    $('#sellerType').modal('show');
});
</script>
@endif

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
                                        <li class="active"><a href="">{{ translate('Step 1')}}</a></li>
                                    </ul> -->
                                </div>
                            </div>
                        </div>
                    </div>
                        <div>
                            <div class="arrow-steps clearfix">
                                <div class="step current"> <span> Step 1</span> </div>
                                <div class="step"> <span>Step 2</span> </div>
                                <div class="step"> <span> Step 3</span> </div>
                                <div class="step"> <span>Step 4</span> </div>
                                <div class="step"> <span>Step 5</span> </div>
                                <div class="step"> <span>Step 6</span> </div>
                            </div>
                        </div>

                        <div class="container my-4">
                        <!--Accordion wrapper-->
                        <div class="accordion sm-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                        <!-- Accordion card -->
                        <div class="card">
                        <!-- Card header -->
                        <div class="card-header btn-danger" role="tab" id="headingOne1">
                            <a cla data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                                <h5 class="mb-0 text-center">Basic Info<i class="fa fa-angle-down right rotate-icon"></i></h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapseOne1" class="collapse show fade" role="tabpanel" aria-labelledby="headingOne1"
                        data-parent="#accordionEx">
                        <div class="card-body">
                        <form class="" action="{{ route('steps.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                            <div class="form-box bg-white mt-4">
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
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Company Name')}}" name="company_name" value="{{ $shop->name }}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your company name here</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                 
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Logo')}} <small>({{ translate('120x120')}})</small></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="logo" id="file-2" onchange="shopLogo(this);" value="{{$shop->logo}}" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
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

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Address')}} <span class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Address')}}" name="address" value="{{ $shop->address }}"required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your address here.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
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
                                <button type="button" onclick="location.href='{{ route('steps.home-info') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Save')}}</button>
                            </div>
                            </form>
                        </div>
                        </div>

                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card">
                        <!-- Card header -->
                        <div class="card-header btn-danger" role="tab" id="headingTwo2">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo2"
                                aria-expanded="false" aria-controls="collapseTwo2">
                                <h5 class="mb-0 text-center">Home Page Setup <i class="fa fa-angle-down right rotate-icon"></i></h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapseTwo" class="collapse fade" role="tabpanel" aria-labelledby="headingTwo2" data-parent="#accordionEx">
                            <div class="card-body">
                                <!-- Home Page Form -->
                            </div>
                        </div>

                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card">
                        <!-- Card header -->
                        <div class="card-header btn-danger" role="tab" id="headingThree3">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree3"
                                aria-expanded="false" aria-controls="collapseThree3">
                                <h5 class="mb-0 text-center ">About Us<i class="fa fa-angle-down right rotate-icon"></i></h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapseThree" class="collapse fade" role="tabpanel" aria-labelledby="headingThree3" data-parent="#accordionEx">
                            <div class="card-body">
                                <!-- About Us Form -->
                            </div>
                        </div>

                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card">

                        <!-- Card header -->
                        <div class="card-header btn-danger" style="color: #fff !important;" role="tab" id="headingFour4">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseFour4" aria-expanded="false" aria-controls="collapseFour4">
                                <h5 class="mb-0 text-center">Policy<i class="fa fa-angle-down right rotate-icon"></i></h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapseFour" class="collapse fade" role="tabpanel" aria-labelledby="headingFour4" data-parent="#accordionEx">
                            <div class="card-body">
                                <!-- Policy Form -->
                            </div>
                        </div>
                        </div>
                        <!-- Accordion card -->
                        </div>
                        <!-- Accordion wrapper -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- For Seller Type empty modal -->

<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="sellerType" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
           <form action="{{ route('dashboard.seller_type') }}" method="POST">
            @csrf
                <div class="form-group">
                <label class="justify-content-center d-flex" style="font-weight: bold; font-size: 16px; text-align:" for="country">Please Select Seller Type</label>
                    <select class ='form-control' name="seller_type" id="seller_type" required>
                        <option value="">-- Select Seller Type --</option>
                        <option value="goods">Want to sell goods</option>
                        <option value="services">Want to provide services</option>
                        <option value="both">Both</option>
                    </select>
                    <div class="text-center mt-2 ">
                        <button class="btn btn-danger">Submit</button>
                    </div>
                </div>
           </form>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        </div>
      </div>
      
    </div>
  </div>  
</div>

<script>
    $('#hide').hide();
        function shopLogo(input){
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