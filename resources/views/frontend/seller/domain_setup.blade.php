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

    <section class="gry-bg py-4 domain_setup">
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
                                        {{ translate('Domain Setup')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('get.domain.setup') }}">{{ translate('Domain Setup')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <form class="form-default" action="{{ route('domain.setup') }}" method="POST" enctype="multipart/form-data">
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
                                        <div class=" input-group col-md-7 mb-3">
                                                <input type="text" class="form-control" placeholder="xyz..." name="domain" id ="domain" value="{{translate($domain->domain)}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.sheconomy.in</span>
                                                </div>
                                            </div>
                                        <div class="col-md-4" id="status"></div>
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