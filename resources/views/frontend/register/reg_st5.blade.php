@extends('frontend.layouts.app')

@section('content')
<style>
    .input-group-text{
        padding: 1px;
        font-size: 16px;
        font-family: "Times New Roman", Times, serif;
        height: 35px;
    }

</style>

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
                                            <li><a href="">...</a></li>
                                            <li class="active"><a href="">{{ translate('Step 5')}}</a></li>
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
                                <div class="step current"> <span>Step 5</span> </div>
                                <div class="step"> <span>Step 6</span> </div>
                            </div>
                          </div>
                            <form class="form-default" action="{{ route('steps.domain-setup') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Create Your Domain')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Domain Name')}}</label>
                                            <span style="font-size: 10px">{{translate('(Do not use space.)')}}</span>
                                        </div>
                                        <?php $shop = \App\Shop::where('user_id', Auth::user()->id)->first(); ?>
                                            <div class=" input-group col-md-7 mb-3">
                                                <input type="text" class="form-control" placeholder="xyz..." name="domain" id ="domain" value="{{ $shop->domain }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.sheconomy.in</span>
                                                </div>
                                            </div>
                                        <div class="mt-2 ml-2" id="status"><span></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                            </div>
                        </form>                                             
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script type="text/javascript">
    $(document).ready(function(){
        //forcing stop space
        $('input').keypress(function( e ) {
            if(e.which === 32) 
                return false;
        });
	// check change event of the text field 
        $("#domain").keyup(function(){
                // get text username text field value 
                var domain = $("#domain").val();
                // check username name only if length is greater than or equal to 3
                if(domain.length >= 3)
                {
                        $("#status").html('Checking availability...');
                        // check username 
                        
                $.post('{{ route('domain.verify') }}', {_token:'{{ csrf_token() }}',
                    domain:domain,
                    },
                    function(data, status){
                        $("#status").html(data);
                });
                }

        });
    });
    </script>
@endsection