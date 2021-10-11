@extends('frontend.layouts.app')

@section('content')

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
                                            <li class="active"><a href="">{{ translate('Step 2')}}</a></li>
                                        </ul> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="arrow-steps clearfix">
                                <div class="step"> <span> Step 1</span> </div>
                                <div class="step current"> <span>Step 2</span> </div>
                                <div class="step"> <span> Step 3</span> </div>
                                <div class="step"> <span>Step 4</span> </div>
                                <div class="step"> <span>Step 5</span> </div>
                                <div class="step"> <span>Step 6</span> </div>
                            </div>
                          </div>
                          @if($country == 'india' && $kyc_status == 'pending')
                        <form class="form-default" action="{{ route('steps.seller.kyc') }}" method="POST" enctype="multipart/form-data">
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
                                            <select class="form-control" id="account_type" name="account_type" >
                                              <option value="">--Select Account Type--</option>
                                              @foreach($account_type as $account)
                                              <option value="{{$account['account_type']}}">{{$account['account_type']}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>      
                                    <div class="row" id="gst" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('GST Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="GST Number" name="gst" >
                                        </div>
                                    </div>                                    
                                    <div class="row" id="cin" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('CIN Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="CIN Number" name="cin" >
                                        </div>
                                    </div>                                    
                                    <div class="row" id="aadhar_number" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Aadhar Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="1234-5678-9000" name="aadhar_number" >
                                        </div>
                                    </div>
                                    <div class="row" id="aadhar_upload" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload Aadhar')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="aadhar" id="aadhar" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="aadharFile(event)"  />
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
                                    <br>
                                    <div class="row" id="pan_number" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('PAN Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="CGJ6753GH65" name="pan_number" >
                                        </div>
                                    </div>
                                    <div class="row" id="pan_upload" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload PAN')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="pan" id="pan" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*, application/pdf" onchange="panFile(event)" />
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
                                    <div class="text-right mt-4">
                                    <button type="button" onclick="location.href='{{ route('steps.payments') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
                                    <button class="btn btn-styled btn-base-1" type="submit" >Verify</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @elseif($country != 'india' && $kyc_status == 'pending')
                        <form class="form-default" action="{{ route('steps.seller.kyc.non.india') }}" method="POST" enctype="multipart/form-data">
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
                                            <select class="form-control" id="account_type_non_india" name="account_type" >
                                              <option value="">--Select Account Type--</option>
                                              @foreach($account_type as $account)
                                              <option value="{{$account['account_type']}}">{{$account['account_type']}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>                                      
                                    <div class="row" id="business_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Business Existance Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="Company Registration Number" name="business_proof" >
                                        </div>
                                    </div>  
                                    <br>
                                    <div class="row" id="tax_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Tax Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="tax" id="tax" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="taxFile(event)"  />
                                            <label for="tax" class="mw-100 mb-3">
                                                <span class="ms-error-3"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="tax_error" timeout=5000></div>
                                        <div>
                                            <img id="tax_pre">
                                        </div>
                                    </div>                                  
                                    <div class="row" id="age_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Age Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="age" id="age" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="ageFile(event)"  />
                                            <label for="age" class="mw-100 mb-3">
                                                <span class="ms-error-4"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="age_error" timeout=5000></div>
                                        <div>
                                            <img id="age_pre">
                                        </div>
                                    </div>  
                                    <br>
                                    <div class="row" id="address_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Address Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="address" id="address" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="addressFile(event)"  />
                                            <label for="address" class="mw-100 mb-3">
                                                <span class="ms-error-5"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="address_error" timeout=5000></div>
                                        <div>
                                            <img id="address_pre">
                                        </div>
                                    </div>
                                    <div class="text-right mt-4">
                                    <button type="button" onclick="location.href='{{ route('steps.payments') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
                                    <button class="btn btn-styled btn-base-1" type="submit">Verify</button>
                                    </div>
                                </div>
                            </div>
                        </form> 
                        @elseif($country == 'india' && $kyc_status == 'rejected')
                        <form class="form-default" action="{{ route('steps.seller.kyc') }}" method="POST" enctype="multipart/form-data">
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
                                            <select class="form-control" id="account_type" name="account_type" >
                                              <option value="">--Select Account Type--</option>
                                              @foreach($account_type as $account)
                                              <option value="{{$account['account_type']}}">{{$account['account_type']}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>      
                                    <div class="row" id="gst" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('GST Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="GST Number" name="gst" >
                                        </div>
                                    </div>                                    
                                    <div class="row" id="cin" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('CIN Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="CIN Number" name="cin" >
                                        </div>
                                    </div>                                    
                                    <div class="row" id="aadhar_number" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Aadhar Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="1234-5678-9000" name="aadhar_number" >
                                        </div>
                                    </div>
                                    <div class="row" id="aadhar_upload" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload Aadhar')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="aadhar" id="aadhar" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="aadharFile(event)"  />
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
                                    <br>
                                    <div class="row" id="pan_number" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('PAN Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="CGJ6753GH65" name="pan_number" >
                                        </div>
                                    </div>
                                    <div class="row" id="pan_upload" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload PAN')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="pan" id="pan" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*, application/pdf" onchange="panFile(event)" />
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
                                    <div class="text-right mt-4">
                                    <button type="button" onclick="location.href='{{ route('steps.payments') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
                                    <button class="btn btn-styled btn-base-1" type="submit" >Verify</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @elseif($country != 'india' && $kyc_status == 'rejected')
                        <form class="form-default" action="{{ route('steps.seller.kyc.non.india') }}" method="POST" enctype="multipart/form-data">
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
                                            <select class="form-control" id="account_type_non_india" name="account_type" >
                                              <option value="">--Select Account Type--</option>
                                              @foreach($account_type as $account)
                                              <option value="{{$account['account_type']}}">{{$account['account_type']}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>                                      
                                    <div class="row" id="business_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Business Existance Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="Company Registration Number" name="business_proof" >
                                        </div>
                                    </div>  
                                    <br>
                                    <div class="row" id="tax_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Tax Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="tax" id="tax" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="taxFile(event)"  />
                                            <label for="tax" class="mw-100 mb-3">
                                                <span class="ms-error-3"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="tax_error" timeout=5000></div>
                                        <div>
                                            <img id="tax_pre">
                                        </div>
                                    </div>                                  
                                    <div class="row" id="age_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Age Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="age" id="age" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="ageFile(event)"  />
                                            <label for="age" class="mw-100 mb-3">
                                                <span class="ms-error-4"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="age_error" timeout=5000></div>
                                        <div>
                                            <img id="age_pre">
                                        </div>
                                    </div>  
                                    <br>
                                    <div class="row" id="address_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Address Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="address" id="address" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" onchange="addressFile(event)"  />
                                            <label for="address" class="mw-100 mb-3">
                                                <span class="ms-error-5"></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div id="address_error" timeout=5000></div>
                                        <div>
                                            <img id="address_pre">
                                        </div>
                                    </div>
                                    <div class="text-right mt-4">
                                    <button type="button" onclick="location.href='{{ route('steps.payments') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
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
                $('#aadhar_error').remove();
                var spans = $('.ms-error-1');                        
                spans.show();     
            }else{
                alert("Aadhar Image Size must not be greater than 180px");
                $('#aadhar_error').append('<span id="aadhar-err" style="color:red">**Aadhar Image : Size must not be greater than 180px **</span>');
                var spans = $('.ms-error-1');
                spans.hide();
                return false;
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
                $('#pan_error').remove();
                var spans = $('.ms-error-2');                        
                spans.show();      
            }else{
                alert("Pan Image Size must not be greater than 180px");
                $('#pan_error').append('<span id="pan-err" style="color:red">**Pan Image : Size must not be greater than 180px **</span>');
                var spans = $('.ms-error-2');
                spans.hide();
                return false;
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
                $('#tax_error').remove();
                var spans = $('.ms-error-3');                        
                spans.show(); 
            }else{
                alert("Tax Image Size must not be greater than 180px");
                $('#tax_error').append('<span id="tax-err" style="color:red">**Tax Image : Size must not be greater than 180px **</span>');
                var spans = $('.ms-error-3');
                spans.hide();
                return false;
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
                $('#age_error').remove();
                var spans = $('.ms-error-4');                        
                spans.show();                
            }else{
                alert("Age Image Size must not be greater than 180px");
                $('#age_error').append('<span id="age-err" style="color:red">**Age Image : Size must not be greater than 180px **</span>');
                var spans = $('.ms-error-4');
                spans.hide();
                return false;
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
                $('#address_error').remove();
                var spans = $('.ms-error-5');                        
                spans.show();            
            }else{
                alert("Address Image Size must not be greater than 180px");
                $('#address_error').append('<span id="address-err" style="color:red">**Address Image : Size must not be greater than 180px **</span>');
                var spans = $('.ms-error-5');
                spans.hide();
                return false;
            }
          }else{
              alert('Document type not matched!')
          }
        };
        $('#docs_submit_non_india').on('click', function() {
            var tax_img = $("input[name=tax]").val();
            var age_img = $("input[name=age]").val();
            var address_img = $("input[name=address]").val();
            var business_proof = $("input[name=business_proof]").val();
            var account_type = $("#account_type_non_india").val();
            $.post('{{ route('seller.kyc.non.india') }}', {_token:'{{ csrf_token() }}',
                tax_img: tax_img, 
                age_img:age_img,
                address_img:address_img,
                business_proof:business_proof,
                account_type:account_type,
                },
                function(data){
                data = JSON.parse(data);
                if(data.status == 1){
                    showFrontendAlert('success', data.message);
                }else
                    showFrontendAlert('danger', data.message);
                location.reload();
            });
        });
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
        $('#docs_submit').on('click', function() {
            var aadhar_img = $("input[name=aadhar]").val();
            var pan_img = $("input[name=pan]").val();
            var aadhar = $("input[name=aadhar_number]").val();
            var pan = $("input[name=pan_number]").val();
            var gst = $("input[name=gst]").val();
            var cin = $("input[name=cin]").val();
            var account_type = $("#account_type").val();
            $.post('{{ route('seller.kyc') }}', {_token:'{{ csrf_token() }}',
                aadhar_img: aadhar_img, 
                pan_img:pan_img,
                aadhar_number:aadhar,
                pan_number:pan,
                gst_number:gst,
                cin_number:cin,
                account_type:account_type,
                },
                function(data){
                data = JSON.parse(data);
                if(data.status == 1){
                    showFrontendAlert('success', data.message);
                }else
                    showFrontendAlert('danger', data.message);
                location.reload();
            });
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
                            console.log(size);
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
