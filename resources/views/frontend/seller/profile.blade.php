@extends('frontend.layouts.app')

@section('content')

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

<style>
.selectpicker option
{
        border: none;
        background-color: white;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance : none;
        color: #14B1B2;
        font-weight: bold;
        font-size: 30px;
        margin: 0;
        padding-left: 0;
        margin-top: -20px;
        background: none;
    }
select.selectpicker
{
        border: none;
        background-color: white;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance : none;
        color: #14B1B2;
        font-weight: bold;
        font-size: 30px;
        margin: 0;
        padding-left: 0;
        margin-top: -20px;
        background: none;
    }
    

.selectpicker option
{
        border: none;
        background-color: white;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance : none;
        color: #14B1B2;
        font-weight: bold;
        font-size: 30px;
        margin: 0;
        padding-left: 0;
        margin-top: -20px;
        background: none;
    }
.selectpicker
{
        border: none;
        background-color: white;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance : none;
        color: #14B1B2;
        font-weight: bold;
        font-size: 30px;
        margin: 0;
        padding-left: 0;
        margin-top: -20px;
        background: none;
    }

    </style>
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
                        
                        <form class="" action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Basic info')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Name')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Name')}}" name="name" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Phone')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div style='width: 100px;' class="box">
                                                    <?php 
                                                    // For country code
                                                        $ph = Auth::user()->phone;
                                                        $cc = substr($ph, 0, 4);
                                                    ?>
                                                    <select class="selectpicker des" required name="country_code" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;">
                                                            @if(Auth::user()->phone == "")
                                                                <option value="+91">+91</option>
                                                            @else
                                                                <option value="{{ $cc }}">{{ $cc }}</option>
                                                            @endif
                                                        @foreach($country_codes as $country_code)
                                                            <option value="+{{ $country_code->phonecode }}">+{{ $country_code->phonecode }}</option>
                                                        @endforeach
                                                    </select>  
                                                </div>
                                            </div>
                                            <input type="text" name="phone" class="form-control numbers" value='{{ substr(Auth::user()->phone, 4) }}' placeholder="Mobile" autocomplete="off" minlength="4" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Photo')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="photo" id="file-3" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                            <label for="file-3" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image')}}
                                                </strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Password')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="password" class="form-control mb-3" placeholder="{{ translate('New Password')}}" name="new_password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Confirm Password')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="password" class="form-control mb-3" placeholder="{{ translate('Confirm Password')}}" name="confirm_password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Addresses')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row gutters-10">
                                        @foreach (Auth::user()->addresses as $key => $address)
                                            <div class="col-lg-6">
                                                <div class="border p-3 pr-5 rounded mb-3 position-relative">
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Address') }}:</span>
                                                        <span class="strong-600 ml-2">{{ isset($address->address) ? $address->address : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Postal Code') }}:</span>
                                                        <span class="strong-600 ml-2">{{ isset($address->postal_code) ? $address->postal_code : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('State')}}:</span>
                                                        <span class="strong-600 ml-2">{{ isset($address->state) ? $address->state : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('City')}}:</span>
                                                        <span class="strong-600 ml-2">{{ isset($address->city) ? $address->city : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Country')}}:</span>
                                                        <span class="strong-600 ml-2">{{ isset($address->country) ? $address->country : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Phone')}}:</span>
                                                        <span class="strong-600 ml-2">{{ isset($address->phone) ? $address->phone : '' }}</span>
                                                    </div>
                                                    @if ($address->set_default)
                                                        <div class="position-absolute right-0 bottom-0 pr-2 pb-3">
                                                            <span class="badge badge-primary bg-base-1">{{ translate('Default')}}</span>
                                                        </div>
                                                    @endif
                                                    <div class="dropdown position-absolute right-0 top-0">
                                                        <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                                                            <i class="la la-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                            @if (!$address->set_default)
                                                                <a class="dropdown-item" href="{{ route('addresses.set_default', $address->id) }}">{{ translate('Make This Default')}}</a>
                                                            @endif
                                                            {{-- <a class="dropdown-item" href="">Edit</a> --}}
                                                            <a class="dropdown-item" href="{{ route('addresses.destroy', $address->id) }}">{{ translate('Delete')}}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-6 mx-auto" onclick="add_new_address()">
                                            <div class="border p-3 rounded mb-3 c-pointer text-center bg-light">
                                                <i class="la la-plus la-2x"></i>
                                                <div class="alpha-7">{{ translate('Add New Address')}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!--                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Payment Setting')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Cash Payment')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <label class="switch mb-3">
                                                <input value="1" name="cash_on_delivery_status" type="checkbox" @if (Auth::user()->seller->cash_on_delivery_status == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Bank Payment')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <label class="switch mb-3">
                                                <input value="1" name="bank_payment_status" type="checkbox" @if (Auth::user()->seller->bank_payment_status == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Bank Name')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Bank Name')}}" value="{{ Auth::user()->seller->bank_name }}" name="bank_name">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Bank Account Name')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Bank Account Name')}}" value="{{ Auth::user()->seller->bank_acc_name }}" name="bank_acc_name">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Bank Account Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Bank Account Number')}}" value="{{ Auth::user()->seller->bank_acc_no }}" name="bank_acc_no">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Bank Routing Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control mb-3" placeholder="{{ translate('Bank Routing Number')}}" value="{{ Auth::user()->seller->bank_routing_no }}" name="bank_routing_no">
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Update Profile')}}</button>
                            </div>
                        </form>
                        <!-- @if($country == 'india' && $kyc_status == 'pending')
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
                                            <select class="form-control" id="account_type" name="account_type">
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
                                            <input type="text" class="form-control mb-3" placeholder="GST Number" name="gst">
                                        </div>
                                    </div>                                    
                                    <div class="row" id="cin" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('CIN Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="CIN Number" name="cin">
                                        </div>
                                    </div>                                    
                                    <div class="row" id="aadhar_number" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Aadhar Number')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3" placeholder="1234-5678-9000" name="aadhar_number">
                                        </div>
                                    </div>
                                    <div class="row" id="aadhar_upload" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload Aadhar')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="aadhar" id="aadhar" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" />
                                            <label for="aadhar" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
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
                                            <input type="text" class="form-control mb-3" placeholder="CGJ6753GH65" name="pan_number">
                                        </div>
                                    </div>
                                    <div class="row" id="pan_upload" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Upload PAN')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="pan" id="pan" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*, application/pdf" />
                                            <label for="pan" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div>
                                            <img id="pan_pre">
                                        </div>
                                    </div>
                                    <div class="text-right mt-4">
                                    <button class="btn btn-styled btn-base-1" type="submit" >Verify</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @elseif($country != 'india' && $kyc_status == 'pending')
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
                                            <input type="text" class="form-control mb-3" placeholder="Company Registration Number" name="business_proof">
                                        </div>
                                    </div>  
                                    <br>
                                    <div class="row" id="tax_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Tax Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="tax" id="tax" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" />
                                            <label for="tax" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div>
                                            <img id="tax_pre">
                                        </div>
                                    </div>                                  
                                    <div class="row" id="age_proof" style="display:none;">
                                        <div class="col-md-2">
                                            <label>{{ translate('Age Proof')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="age" id="age" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf"/>
                                            <label for="age" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
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
                                            <input type="file" name="address" id="address" class="custom-input-file custom-input-file--4" accept="image/*, application/pdf" />
                                            <label for="address" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose file')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div>
                                            <img id="address_pre">
                                        </div>
                                    </div>
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
                        @endif -->
                        <form action="{{ route('user.change.email') }}" method="POST">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Change your email') }}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Email') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="input-group mb-3">
                                              <input
                                                  type="email"
                                                  class="form-control"
                                                  placeholder="{{ translate('Your Email')}}"
                                                  name="email"
                                                  value="{{ Auth::user()->email }}"
                                              />
                                              <div class="input-group-append">
                                                 <button type="button" class="btn btn-outline-secondary new-email-verification">
                                                     <span class="d-none loading">
                                                         <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                         Sending Email...
                                                     </span>
                                                     <span class="default">Verify</span>
                                                 </button>
                                              </div>
                                            </div>
                                            <button class="btn btn-styled btn-base-1" type="submit">Update Email</button>
                                        </div>
                                    </div>
                                </div>
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
                <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control textarea-autogrow mb-3" placeholder="{{ translate('Your Address')}}" rows="1" name="address" required></textarea>
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
                                                <option value="{{ $country->name }}">{{ $country->name }}</option>
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
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your State')}}" name="state" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('City')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your City')}}" name="city" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Postal code')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
        
                                <select name="country_code" id="">
                                @foreach($country_codes as $country_code)
                                    <option value="+{{ $country_code->phonecode }}">+{{ $country_code->phonecode }}</option>
                                @endforeach
                                </select>
                                </div>
                            <input type="text" name="phone" class="form-control numbers" placeholder="Mobile" autocomplete="off" minlength="4" maxlength="12" aria-label="Text input with dropdown button">
                            </div>
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
