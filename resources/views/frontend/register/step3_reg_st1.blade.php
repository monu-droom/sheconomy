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
                        <div id="collapseOne" class="collapse fade" role="tabpanel" aria-labelledby="headingOne1" data-parent="#accordionEx">
                            <div class="card-body">
                                <!-- Basic Info Form -->
                            </div>
                        </div>

                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card">

                        <!-- Card header -->
                        <div class="card-header btn-danger" role="tab" id="headingTwo2">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                                <h5 class="mb-0 text-center">Home Page Setup <i class="fa fa-angle-down right rotate-icon"></i></h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapseTwo" class="collapse fade" role="tabpanel" aria-labelledby="headingTwo2" data-parent="#accordionEx">
                            <div class="card-body">
                                <!-- Home Info Form -->
                            </div>
                        </div>

                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card">

                        <!-- Card header -->
                        <div class="card-header btn-danger" role="tab" id="headingThree3">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree3" aria-expanded="false" aria-controls="collapseThree3">
                                <h5 class="mb-0 text-center ">About Us<i class="fa fa-angle-down right rotate-icon"></i></h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapseThree3" class="collapse  show fade" role="tabpanel" aria-labelledby="headingThree3" data-parent="#accordionEx">
                            <div class="card-body">
                                <form class="" action="{{ route('steps.about-update', $shop->id ) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-box bg-white mt-4">
                                        <div class="row">
                                        <div class="col-md-12">
                                            <textarea name="about" rows="20" class="form-control editor" placeholder="Please Type Someting About Your Shop..."required>{{ $shop->about }}</textarea>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your about us page.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>    
                                    </div>
                                    @if($shop->about != '')                            
                                    <div class="text-right mt-4">
                                        <button type="button" onclick="location.href='{{ route('steps.policy') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
                                        <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Update')}}</button>
                                    </div>
                                    <!<!-- Update Button -->
                                    @else
                                    <div class="text-right mt-4">
                                        <button type="button" onclick="location.href='{{ route('steps.policy') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
                                        <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Save')}}</button>
                                    </div>
                                    @endif
                                </form>
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
                                <!-- Policy Info Form -->
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

@endsection