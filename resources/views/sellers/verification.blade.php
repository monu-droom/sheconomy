<!-- Verification -->
@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <div class="panel-control">
            <a href="{{ route('sellers.reject', $seller->id) }}" class="btn btn-default btn-rounded d-innline-block">{{translate('Reject')}}</a></li>
            <a href="{{ route('sellers.approve', $seller->id) }}" class="btn btn-primary btn-rounded d-innline-block">{{translate('Accept')}}</a>
        </div>
        <h3 class="panel-title">{{translate('Seller Verification')}}</h3>
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('User Info')}}</h3>
            </div>
            <div class="row">
                <label class="col-sm-3 control-label" for="name">{{translate('Name')}}</label>
                <div class="col-sm-9">
                    <p>{{ $seller->user->name }}</p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-3 control-label" for="name">{{translate('Email')}}</label>
                <div class="col-sm-9">
                    <p>{{ $seller->user->email }}</p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-3 control-label" for="name">{{translate('Address')}}</label>
                <div class="col-sm-9">
                    <p>{{ $seller->user->address }}</p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-3 control-label" for="name">{{translate('Phone')}}</label>
                <div class="col-sm-9">
                    <p>{{ $seller->user->phone }}</p>
                </div>
            </div>


            <div class="panel-heading">
                <h3 class="text-lg">{{translate('Shop Info')}}</h3>
            </div>

            <div class="row">
                <label class="col-sm-3 control-label" for="name">{{translate('Shop Name')}}</label>
                <div class="col-sm-9">
                    <p>{{ $seller->user->shop->name }}</p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-3 control-label" for="name">{{translate('Address')}}</label>
                <div class="col-sm-9">
                    <p>{{ $seller->user->shop->address }}</p>
                </div>
            </div>
        </div>  
    </div>
</div>
<!-- Kyc Verification for Non-Indian -->
@if(strtolower($shop->country) != 'india')
<div class="panel">
    <div class="panel-heading">
        <div class="panel-control">
        </div>
        <h3 class="panel-title" style="font-weight: bold">{{translate('Seller KYC Verification')}}</h3>        
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('Age Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <?php
                    $src_file_name = isset($seller->age_proof_img) ? $seller->age_proof_img : '';
                    $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                ?>
                @if($ext != 'pdf')
                <div class="row">
                    <img src="{{ asset("public/uploads/$seller->age_proof_img") }}"  width="300px" height="200px">
                </div><br>
                @else
                    <iframe src="{{ asset("public/uploads/$seller->age_proof_img") }}" style="width:600px; height:500px;" frameborder="0"></iframe>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->age_proof_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="age_proof_accpeted">Accepted</option>
                          <option value="age_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->age_proof_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="age_proof_rejected">Rejected</option>
                          <option value="age_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->age_proof_verified == 1)
                          <option selected="selected" value="age_proof_accpeted">Accepted</option>
                          <option value="age_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('Address Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <?php
                    $src_file_name = isset($seller->address_proof_img) ? $seller->address_proof_img : '';
                    $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                ?>
                @if(strtolower($ext) != 'pdf')
                <div class="row">
                    <img src="{{ asset("public/uploads/$seller->address_proof_img") }}"  width="400px" height="300px">
                </div><br>
                @else
                    <iframe src="{{ asset("public/uploads/$seller->address_proof_img") }}" style="width:500px; height:400px;" frameborder="0"></iframe>
                    <br></br>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->address_proof_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="address_proof_accpeted">Accepted</option>
                          <option value="address_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->address_proof_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="address_proof_rejected">Rejected</option>
                          <option value="address_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->address_proof_verified == 1)
                          <option selected="selected" value="address_proof_accpeted">Accepted</option>
                          <option value="address_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    @if($seller_type == 'pro')
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('Tax Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <?php
                    $src_file_name = isset($seller->tax_proof_img) ? $seller->tax_proof_img : '';
                    $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                ?>
                @if(strtolower($ext) != 'pdf')
                <div class="row">
                    <img src="{{ asset("public/uploads/$seller->tax_proof_img") }}"  width="400px" height="300px">
                </div><br>
                @else
                    <iframe src="{{ asset("public/uploads/$seller->tax_proof_img") }}" style="width:500px; height:400px;" frameborder="0"></iframe>
                    <br></br>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->tax_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="tax_proof_accpeted">Accepted</option>
                          <option value="tax_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->tax_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="tax_proof_rejected">Rejected</option>
                          <option value="tax_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->tax_verified == 1)
                          <option selected="selected" value="tax_proof_accpeted">Accepted</option>
                          <option value="tax_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('Business Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <label class="col-md-4 control-label" for="name">{{translate('Business Proof')}}</label>
                    <div class="col-md-8">
                        <p>{{ $seller->business_proof }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->business_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="business_proof_accpeted">Accepted</option>
                          <option value="business_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->business_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="business_proof_rejected">Rejected</option>
                          <option value="business_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->business_verified == 1)
                          <option selected="selected" value="business_proof_accpeted">Accepted</option>
                          <option value="business_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg"><strong>{{translate('Final Kyc Status')}}</strong></h3>
            </div>
            <form class="" action="{{ route('final.kyc.status', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="row">
                    <label class="col-md-4 control-label" for="name" style="font-weight: bold;">{{translate('Final Status')}}</label>
                    <div class="col-md-8" style="font-weight: bold;">
                        <p>{{ \Str::ucfirst($seller->kyc_status) }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Final Kyc Status')}}</strong></label>
                    </div>
                <div class="col-md-8">
                    <select class="form-control" id="kyc_status" name="kyc_status">
                      <option value="">--Select Kyc Status--</option>
                          @if($seller->kyc_status == 'rejected')
                          <option selected="selected" value="rejected">Rejected</option>
                          <option value="verified">Verified</option>
                          @elseif($seller->kyc_status == 'verified')
                          <option selected="selected" value="verified">Verified</option>
                          <option value="rejected">Rejected</option>
                          @else
                          <option value="verified">Verified</option>
                          <option value="rejected">Rejected</option>
                          @endif
                    </select>
                </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div> 
</div>
<!<!-- KYC Verification For Indian -->
@else
<div class="panel">
    <div class="panel-heading">
        <div class="panel-control">
        </div>
        <h3 class="panel-title" style="font-weight: bold">{{translate('Seller KYC Verification')}}</h3>        
    </div>
    <div style="margin-left: 10px; font-weight: bold;">
    <span >Account Type: </span><span class="panel-title" style="font-weight: 500;">{{ ucfirst($account_type->account_type) }}</span>
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('Aadhar Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('indian.kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <label class="col-md-4 control-label" for="name"><strong>{{translate('Aadhar Proof')}}</strong></label>
                    <div class="col-md-8">
                        <p>{{ $seller->aadhar_number }}</p>
                    </div>
                </div>
                <?php
                    $src_file_name = isset($seller->aadhar_img) ? $seller->aadhar_img : '';
                    $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                ?>
                @if(strtolower($ext) != 'pdf')
                <div class="row">
                    <img src="{{ asset("public/uploads/$seller->aadhar_img") }}"  width="400px" height="300px">
                </div><br>
                @else
                    <iframe src="{{ asset("public/uploads/$seller->aadhar_img") }}" style="width:500px; height:400px;" frameborder="0"></iframe>
                    <br></br>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->aadhar_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="aadhar_proof_accpeted">Accepted</option>
                          <option value="aadhar_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->aadhar_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="aadhar_proof_rejected">Rejected</option>
                          <option value="aadhar_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->aadhar_verified == 1)
                          <option selected="selected" value="aadhar_proof_accpeted">Accepted</option>
                          <option value="aadhar_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('PAN Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('indian.kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <label class="col-md-4 control-label" for="name"><strong>{{translate('PAN Proof')}}</strong></label>
                    <div class="col-md-8">
                        <p>{{ $seller->pan_number }}</p>
                    </div>
                </div>
                <?php
                    $src_file_name = isset($seller->pan_img) ? $seller->pan_img : '';
                    $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                ?>
                @if(strtolower($ext) != 'pdf')
                <div class="row">
                    <img src="{{ asset("public/uploads/$seller->pan_img") }}"  width="300px" height="200px">
                </div><br>
                @else
                    <iframe src="{{ asset("public/uploads/$seller->pan_img") }}" style="width:500px; height:400px;" frameborder="0"></iframe>
                    <br></br>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->pan_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="pan_proof_accpeted">Accepted</option>
                          <option value="pan_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->pan_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="pan_proof_rejected">Rejected</option>
                          <option value="pan_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->pan_verified == 1)
                          <option selected="selected" value="pan_proof_accpeted">Accepted</option>
                          <option value="pan_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    @if($seller_type == 'pro')
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('GST Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('indian.kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <label class="col-md-4 control-label" for="name">{{translate('GST Proof')}}</label>
                    <div class="col-md-8">
                        <p>{{ $seller->gst_number }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->gst_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="gst_proof_accpeted">Accepted</option>
                          <option value="gst_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->gst_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="gst_proof_rejected">Rejected</option>
                          <option value="gst_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->gst_verified == 1)
                          <option selected="selected" value="gst_proof_accpeted">Accepted</option>
                          <option value="gst_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg">{{translate('CIN Proof Info')}}</h3>
            </div>
            <form class="" action="{{ route('indian.kyc.byadmin', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <label class="col-md-4 control-label" for="name">{{translate('CIN Proof')}}</label>
                    <div class="col-md-8">
                        <p>{{ $seller->cin_number }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Kyc Status')}}</strong></label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="kyc_status" name="kyc_status">
                          @if($kyc_status != '' && $kyc_status->cin_verified == 0 && strtolower($seller->kyc_status) == 'submitted')
                          <option value="">--Select Kyc Status--</option>
                          <option value="cin_proof_accpeted">Accepted</option>
                          <option value="cin_proof_rejected">Rejected</option>
                          @elseif($kyc_status != '' && $kyc_status->cin_verified == 0 && strtolower($seller->kyc_status) == 'rejected')
                          <option selected="selected" value="cin_proof_rejected">Rejected</option>
                          <option value="cin_proof_accpeted">Accepted</option>
                          @elseif($kyc_status != '' && $kyc_status->cin_verified == 1)
                          <option selected="selected" value="cin_proof_accpeted">Accepted</option>
                          <option value="cin_proof_rejected">Rejected</option>
                          @endif
                        </select>
                    </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    <div class="panel-body">
        <div class="col-md-4">
            <div class="panel-heading">
                <h3 class="text-lg"><strong>{{translate('Final Kyc Status')}}</strong></h3>
            </div>
            <form class="" action="{{ route('final.kyc.status', $seller->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="row">
                    <label class="col-md-4 control-label" for="name" style="font-weight: bold;">{{translate('Final Status')}}</label>
                    <div class="col-md-8" style="font-weight: bold;">
                        <p>{{ \Str::ucfirst($seller->kyc_status) }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>{{ translate('Final Kyc Status')}}</strong></label>
                    </div>
                <div class="col-md-8">
                    <select class="form-control" id="kyc_status" name="kyc_status">
                      <option value="">--Select Kyc Status--</option>
                          @if($seller->kyc_status == 'rejected')
                          <option selected="selected" value="rejected">Rejected</option>
                          <option value="verified">Verified</option>
                          @elseif($seller->kyc_status == 'verified')
                          <option selected="selected" value="verified">Verified</option>
                          <option value="rejected">Rejected</option>
                          @else
                          <option value="verified">Verified</option>
                          <option value="rejected">Rejected</option>
                          @endif
                    </select>
                </div>
                </div><br>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-base-1">{{ translate('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>    
</div>
@endif
@endsection
