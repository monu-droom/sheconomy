<!-- Reject KYC -->
@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('Manage Profile')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('profile') }}">{{ translate('Manage Profile')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($country == 'india' && $kyc_status == 'rejected')
                        <form class="form-default" action="{{ route('seller.kyc') }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Kyc Setting')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row" style="margin-top:5px">
                                        <div class="col-md-2">
                                            <label>{{ translate('Account Type')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class="form-control" id="account_type" name="account_type" disabled>
                                              @if($seller_account_type != '')
                                              <option value="">--Select Account Type--</option>
                                              <option selected="selected" value="{{$seller_account_type->account_type}}">{{$seller_account_type->account_type}}</option>
                                              @foreach($account_type as $account)
                                              <option value="{{$account['account_type']}}">{{$account['account_type']}}</option>
                                              @endforeach
                                              @endif
                                            </select>
                                        </div>
                                    </div>
                                    <br>                        
                                    @if($seller_type == 'pro')
                                    @if($seller_kyc->gst_verified == 0)  
                                    <div class="row" id="gst">
                                        <div class="col-md-2">
                                            <label>{{ translate('GST Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" required class="form-control mb-3" placeholder="GST Number" name="gst">
                                        </div>
                                    </div>  
                                    @endif
                                    @if($seller_kyc->cin_verified == 0) 
                                    <div class="row" id="cin">
                                        <div class="col-md-2">
                                            <label>{{ translate('CIN Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" required class="form-control mb-3" placeholder="CIN Number" name="cin">
                                        </div>
                                    </div>     
                                    @endif
                                    @endif
                                    @if($seller_kyc->aadhar_verified == 0)                                     
                                    <div class="row" id="aadhar_number">
                                        <div class="col-md-2">
                                            <label>{{ translate('Aadhar Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" required class="form-control mb-3" placeholder="1234-5678-9000" name="aadhar_number">
                                        </div>
                                    </div>
                                    @endif
                                    @if($seller_kyc->aadhar_pre_verified == 0) 
                                    <div class="row" id="aadhar_upload">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload Aadhar')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="aadhar" id="aadhar" onchange="aadharCard(event)" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" />
                                            <label for="aadhar" class="mw-100 mb-3">
                                                <span class="ms-error-1"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="aadhar_error" timeout=5000></div>
                                        <div>
                                            <img id="aadhar_pre">
                                        </div>
                                    </div>  
                                    @endif
                                    <br>
                                    @if($seller_kyc->pan_verified == 0) 
                                    <div class="row" id="pan_number">
                                        <div class="col-md-2">
                                            <label>{{ translate('PAN Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" required class="form-control mb-3" placeholder="CGJ6753GH65" name="pan_number">
                                        </div>
                                    </div>
                                    @endif
                                    @if($seller_kyc->pan_pre_verified == 0)
                                    <div class="row" id="pan_upload">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload PAN')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="pan" id="pan" onchange="panCard(event)" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*, application/pdf" />
                                            <label for="pan" class="mw-100 mb-3">
                                                <span class="ms-error-2"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="pan_error" timeout=5000></div>
                                        <div>
                                            <img id="pan_pre">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="text-right mt-4">
                                    <button class="btn btn-styled btn-base-1" type="submit" >Verify</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @elseif($country != 'india' && $kyc_status == 'rejected')
                        <form class="form-default" action="{{ route('seller.kyc.non.india') }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Kyc Setting')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row" style="margin-top:5px">
                                        <div class="col-md-2">
                                            <label>{{ translate('Account Type')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class="form-control" id="account_type_non_india" name="account_type">
                                              @if($seller_account_type != '')
                                              <option value="">--Select Account Type--</option>
                                              <option selected="selected" value="{{$seller_account_type->account_type}}">{{$seller_account_type->account_type}}</option>
                                              @foreach($account_type as $account)
                                              <option value="{{$account['account_type']}}">{{$account['account_type']}}</option>
                                              @endforeach
                                              @endif
                                            </select>
                                        </div>
                                    </div>
                                    <br>                  
                                    @if($seller_type == 'pro')
                                    @if($seller_kyc->business_verified == 0)
                                    <div class="row" id="business_proof">
                                        <div class="col-md-2">
                                            <label>{{ translate('Business Existance Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" required class="form-control mb-3" placeholder="Company Registration Number" name="business_proof">
                                        </div>
                                    </div>  
                                    @endif
                                    <br>
                                    @if($seller_kyc->tax_verified == 0)
                                    <div class="row" id="tax_proof">
                                        <div class="col-md-2">
                                            <label>{{ translate('Tax Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="tax" id="tax" onchange="taxProof(event)" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" />
                                            <label for="tax" class="mw-100 mb-3">
                                                <span class="ms-error-3"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="tax_error" timeout=5000>
                                        <div>
                                            <img id="tax_pre">
                                        </div>
                                    </div>   
                                    @endif
                                    @endif
                                    @if($seller_kyc->age_proof_verified == 0)
                                    <div class="row" id="age_proof">
                                        <div class="col-md-2">
                                            <label>{{ translate('Age Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="age" id="age" onchange="ageProof(event)" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf"/>
                                            <label for="age" class="mw-100 mb-3">
                                                <span class="ms-error-4"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="age_error" timeout=5000>
                                        <div>
                                            <img id="age_pre">
                                        </div>
                                    </div>  
                                    @endif
                                    <br>
                                    @if($seller_kyc->address_proof_verified == 0)
                                    <div class="row" id="address_proof">
                                        <div class="col-md-2">
                                            <label>{{ translate('Address Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="address" id="address" onchange="addressProof(event)" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" />
                                            <label for="address" class="mw-100 mb-3">
                                                <span class="ms-error-5"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="address_error" timeout=5000>
                                        <div>
                                            <img id="address_pre">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="text-right mt-4">
                                    <button class="btn btn-styled btn-base-1" type="submit">Verify</button>
                                    </div>
                                </div>
                            </div>
                        </form> 
                        @else
                        <div class="form-box bg-white mt-4">
                            <div class="form-box-title px-3 py-2">
                                {{ translate('Kyc Setting')}}
                            </div>
                            <div class="form-box-content p-3">
                                <div class="row" style="margin-top:5px">
                                    <div class="col-md-5">
                                        <label class='kyc_status'>{{ translate('Status : ').\Str::ucfirst($kyc_status)}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
              
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function add_new_address(){
            $('#new-address-modal').modal('show');
        }

        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                console.log('response si :'.data.status)
                if(data.status == 2)
                    showFrontendAlert('warning', data.message);
                else if(data.status == 1)
                    showFrontendAlert('success', data.message);
                else
                    showFrontendAlert('danger', data.message);
            });
        });
        var aadharFile = function(event) {
          var aadhar_pre = document.getElementById('aadhar');
          if(aadhar_pre.files[0].name.match(/\.(jpg|jpeg|png|pdf)$/)){
            var size = aadhar_pre.files[0].size / 1024;
            if(size < 180){
              var aadhar_pre = document.getElementById('aadhar_pre');
              aadhar_pre.src = URL.createObjectURL(event.target.files[0]);
              aadhar_pre.onload = function() {
                URL.revokeObjectURL(aadhar_pre.src) // free memory
              }                  
            }else{
                alert('Document size is More than 180 KB!');
            }
          }else{
              alert('Document type not matched!')
          }
        };
        var panFile = function(event) {
          var pan_pre = document.getElementById('pan');
          if(pan_pre.files[0].name.match(/\.(jpg|jpeg|png|pdf)$/)){
            var size = pan_pre.files[0].size / 1024;
            if(size < 180){
              var pan_pre = document.getElementById('pan_pre');
              pan_pre.src = URL.createObjectURL(event.target.files[0]);
              pan_pre.onload = function() {
                URL.revokeObjectURL(pan_pre.src) // free memory
              }                  
            }else{
                alert('Document size is More than 180 KB!');
            }
          }else{
              alert('Document type not matched!')
          }
        };
        //Non-Indian Seller
        var taxFile = function(event) {
          var tax_pre = document.getElementById('tax');
          if(tax_pre.files[0].name.match(/\.(jpg|jpeg|png|pdf)$/)){
            var size = tax_pre.files[0].size / 1024;
            if(size < 180){
              var tax_pre = document.getElementById('tax_pre');
              tax_pre.src = URL.createObjectURL(event.target.files[0]);
              tax_pre.onload = function() {
                URL.revokeObjectURL(tax_pre.src) // free memory
              }                  
            }else{
                alert('Document size is More than 180 KB!');
            }
          }else{
              alert('Document type not matched!')
          }
        };
        var ageFile = function(event) {
          var age_pre = document.getElementById('age');
          if(age_pre.files[0].name.match(/\.(jpg|jpeg|png|pdf)$/)){
            var size = age_pre.files[0].size / 1024;
            if(size < 180){
              var age_pre = document.getElementById('age_pre');
              age_pre.src = URL.createObjectURL(event.target.files[0]);
              age_pre.onload = function() {
                URL.revokeObjectURL(age_pre.src) // free memory
              }                  
            }else{
                alert('Document size is More than 180 KB!');
            }
          }else{
              alert('Document type not matched!')
          }
        };
        var addressFile = function(event) {
          var address_pre = document.getElementById('address');
          if(address_pre.files[0].name.match(/\.(jpg|jpeg|png|pdf)$/)){
            var size = address_pre.files[0].size / 1024;
            if(size < 180){
              var address_pre = document.getElementById('address_pre');
              address_pre.src = URL.createObjectURL(event.target.files[0]);
              address_pre.onload = function() {
                URL.revokeObjectURL(address_pre.src) // free memory
              }                  
            }else{
                alert('Document size is More than 180 KB!');
            }
          }else{
              alert('Document type not matched!')
          }
        };
        $("#account_type_non_india").change(function(){
            var select = $("#account_type_non_india").val();
            if(select == 'registered business/company' ||
                select == 'authorized reseller' ||
                select == 'partnership' ||
                select == 'trading company' ||
                select == 'ngo'
               ){
                if ($('#business_proof').css('display') == 'none') {
                    $('#business_proof').toggle();
                }
                if($('#business_proof').css('display') != 'none'){
                    $('#business_proof').hide();
                    $('#business_proof').toggle();
                }
                if ($('#age_proof').css('display') == 'none') {
                    $('#age_proof').toggle();
                }
                if($('#age_proof').css('display') != 'none'){
                    $('#age_proof').hide();
                    $('#age_proof').toggle();
                }
                if ($('#tax_proof').css('display') == 'none') {
                    $('#tax_proof').toggle();
                }
                if($('#tax_proof').css('display') != 'none'){
                    $('#tax_proof').hide();
                    $('#tax_proof').toggle();
                }
                if ($('#address_proof').css('display') == 'none') {
                    $('#address_proof').toggle();
                }
                if($('#address_proof').css('display') != 'none'){
                    $('#address_proof').hide();
                    $('#address_proof').toggle();
                }
            }
            if(select == 'individuals' || 
                select == 'sole proprietors' ||
                select == 'freelancers' ||
                select == 'consultants'
                ){
                if ($('#age_proof').css('display') == 'none') {
                    $('#age_proof').toggle();
                }
                if($('#age_proof').css('display') != 'none'){
                    $('#age_proof').hide();
                    $('#age_proof').toggle();
                }
                if ($('#address_proof').css('display') == 'none') {
                    $('#address_proof').toggle();
                }
                if($('#address_proof').css('display') != 'none'){
                    $('#address_proof').hide();
                    $('#address_proof').toggle();
                }
                if($('#business_proof').css('display') != 'none'){
                    $('#business_proof').hide();
                }
                if($('#tax_proof').css('display') != 'none'){
                    $('#tax_proof').hide();
                }                
            }
        });
        $("#account_type").change(function(){
            var select = $("#account_type").val();
            if(select == 'registered business/company' ||
                select == 'authorized reseller' ||
                select == 'partnership' ||
                select == 'trading company' ||
                select == 'ngo'
               ){   
                if ($('#gst').css('display') == 'none') {
                    $('#gst').toggle();
                }
                if($('#gst').css('display') != 'none'){
                    $('#gst').hide();
                    $('#gst').toggle();
                }
                if ($('#cin').css('display') == 'none') {
                    $('#cin').toggle();
                }
                if($('#cin').css('display') != 'none'){
                    $('#cin').hide();
                    $('#cin').toggle();
                }
                if ($('#aadhar_number').css('display') == 'none') {
                    $('#aadhar_number').toggle();
                }
                if($('#aadhar_number').css('display') != 'none'){
                    $('#aadhar_number').hide();
                    $('#aadhar_number').toggle();
                }
                if ($('#pan_number').css('display') == 'none') {
                    $('#pan_number').toggle();
                }
                if($('#pan_number').css('display') != 'none'){
                    $("[name='pan_number']").prop("required", true);
                    $('#pan_number').hide();
                    $('#pan_number').toggle();
                }
                if ($('#aadhar_upload').css('display') == 'none') {
                    $('#aadhar_upload').toggle();
                }
                if($('#aadhar_upload').css('display') != 'none'){
                    $('#aadhar_upload').hide();
                    $('#aadhar_upload').toggle();
                }
                if ($('#pan_upload').css('display') == 'none') {
                    $('#pan_upload').toggle();
                }
                if($('#pan_upload').css('display') != 'none'){
                    $('#pan_upload').hide();
                    $('#pan_upload').toggle();
                }
            }
            if(select == 'individuals' || 
                select == 'sole proprietors' ||
                select == 'freelancers' ||
                select == 'consultants'
                ){
                if ($('#aadhar_number').css('display') == 'none') {
                    $('#aadhar_number').toggle();
                }
                if($('#aadhar_number').css('display') != 'none'){
                    console.log('a');
                    $('#aadhar_number').hide();
                    $('#aadhar_number').toggle();
                }
                if ($('#pan_number').css('display') == 'none') {
                    $('#pan_number').toggle();
                }
                if($('#pan_number').css('display') != 'none'){
                    $('#pan_number').hide();
                    $('#pan_number').toggle();
                }
                if ($('#aadhar_upload').css('display') == 'none') {
                    $('#aadhar_upload').toggle();
                }
                if($('#aadhar_upload').css('display') != 'none'){
                    $('#aadhar_upload').hide();
                    $('#aadhar_upload').toggle();
                }
                if ($('#pan_upload').css('display') == 'none') {
                    $('#pan_upload').toggle();
                }
                if($('#pan_upload').css('display') != 'none'){
                    $('#pan_upload').hide();
                    $('#pan_upload').toggle();
                }
                if($('#gst').css('display') == 'none'){
                    $('#gst').toggle();
                    $('#gst').hide();
                }
                if($('#gst').css('display') != 'none'){
                    $('#gst').hide();
                }
                if($('#cin').css('display') == 'none'){
                    $('#cin').toggle();
                    $('#cin').hide();
                }
                if($('#cin').css('display') != 'none'){
                    $('#cin').hide();
                }
            }
        });
    </script>

<script type="text/javascript">
    function aadharCard(){
        //Get reference of FileUpload.
        var fileUpload = document.getElementById("aadhar");
    
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
                            var size = fileUpload.files[0].size / 1024;
                            if(size > 180){
                            alert("Aadhar Image Size must not be greater than 180px");
                            $('#aadhar_error').append('<span id="aadhar-err" style="color:red">**Aadhar Image : Size must not be greater than 180px **</span>');
                            var spans = $('.ms-error-1');
                            spans.hide();
                            return false;
                        }else{
                            $('#aadhar_error').remove();
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
    function panCard(){
        //Get reference of FileUpload.
        var fileUpload = document.getElementById("pan");
    
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
                            var size = fileUpload.files[0].size / 1024;
                            if(size > 180){
                            alert("Pan Image Size must not be greater than 180px");
                            $('#pan_error').append('<span id="pan-err" style="color:red">**Pan Image : Size must not be greater than 180px **</span>');
                            var spans = $('.ms-error-2');
                            spans.hide();
                            return false;
                        }else{
                            $('#pan_error').remove();
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

<script type="text/javascript">
    function taxProof(){
        //Get reference of FileUpload.
        var fileUpload = document.getElementById("tax");
    
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
                            var size = fileUpload.files[0].size / 1024;
                            if(size > 180){
                            alert("Tax Image Size must not be greater than 180px");
                            $('#tax_error').append('<span id="tax-err" style="color:red">**Tax Image : Size must not be greater than 180px **</span>');
                            var spans = $('.ms-error-3');
                            spans.hide();
                            return false;
                        }else{
                            $('#tax_error').remove();
                            var spans = $('.ms-error-3');                        
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
    function ageProof(){
        //Get reference of FileUpload.
        var fileUpload = document.getElementById("age");
    
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
                            var size = fileUpload.files[0].size / 1024;
                            if(size > 180){
                            alert("Age Image Size must not be greater than 180px");
                            $('#age_error').append('<span id="age-err" style="color:red">**Age Image : Size must not be greater than 180px **</span>');
                            var spans = $('.ms-error-4');
                            spans.hide();
                            return false;
                        }else{
                            $('#age_error').remove();
                            var spans = $('.ms-error-4');                        
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
    function addressProof(){
        //Get reference of FileUpload.
        var fileUpload = document.getElementById("address");
    
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
                            var size = fileUpload.files[0].size / 1024;
                            if(size > 180){
                            alert("Address Image Size must not be greater than 180px");
                            $('#address_error').append('<span id="address-err" style="color:red">**Address Image : Size must not be greater than 180px **</span>');
                            var spans = $('.ms-error-5');
                            spans.hide();
                            return false;
                        }else{
                            $('#address_error').remove();
                            var spans = $('.ms-error-5');                        
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
    <style>
        img{
            width: 40%;
            height: auto;
        }
        .kyc_status{
            text-align: center;
            color: green;
            font-weight: bold;
        }
    </style>
@endsection
