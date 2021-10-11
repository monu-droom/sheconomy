@extends('frontend.layouts.app')

@section('content')

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
                                            <li class="active"><a href="{{ route('shops.index') }}">{{ translate('Shop Settings')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="" action="{{ route('shop.about_us') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('About Us')}}
                                </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <textarea name="about" rows="10" class="form-control editor" placeholder="Please Type Someting About You..."required>{{ $shop->about }}</textarea>
                                    <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your about us page.</p>"><i class="fa fa-question-circle"></i></a>
                                </div>
                            </div>    
                            </div>
                            @if($shop->about != '')                            
                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Update')}}</button>
                            </div>
                            <!<!-- Update Button -->
                            @else
                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Save')}}</button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
