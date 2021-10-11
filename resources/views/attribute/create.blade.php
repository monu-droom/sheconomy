@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{ translate('Attribute Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('attributes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="panel-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name">{{ translate('Name')}}</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="{{ translate('Name')}}" id="name" name="name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="sub_attribute">{{ translate('Sub Attributes')}}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control mb-3 tagsInput" name="sub_attribute[]" id="sub_attribute" placeholder="{{ translate('Sub Attribute') }}" data-role="tagsinput" required>
                                <span style="color:green">**Sub-Attributes can be More Than One**</span> 
                            </div>
                        </div>           
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center ">
                <button class="btn btn-purple" type="submit">{{ translate('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
