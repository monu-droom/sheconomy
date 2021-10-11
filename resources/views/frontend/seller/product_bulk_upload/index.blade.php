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
                                        {{ translate('Bulk Products upload')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li><a href="#">{{ translate('Bulk Products upload')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-content p-3">
                                    <table class="table mb-0 table-bordered" style="font-size:14px;background-color: #cce5ff;border-color: #b8daff">
                                        <tr>
                                            <td>{{ translate('1. Download the skeleton file and fill it with data.')}}:</td>
                                        </tr>
                                        <tr >
                                            <td>{{ translate('2. You can download the example file to understand how the data must be filled.')}}:</td>
                                        </tr>
                                        <tr>
                                            <td>{{ translate('3. Once you have downloaded and filled the skeleton file, upload it in the form below and submit.')}}:</td>
                                        </tr>
                                        <tr>
                                            <td>{{ translate('4. After uploading products you need to edit them and set products images and choices.')}}</td>
                                        </tr>
                                    </table>
                                    <a href="{{ my_asset('download/bulk_upload_product_sample.xlsx') }}" download><button class="btn btn-styled btn-base-1 mt-2">{{ translate('Download product sample') }}</button></a>
                                    <a href="{{ my_asset('download/bulk_upload_product_variant_sample.xlsx') }}" download><button class="btn btn-styled btn-base-1 mt-2">{{ translate('Download config product sample') }}</button></a>
                                    <a href="{{ my_asset('download/bulk_upload_service_sample.xlsx') }}" download><button class="btn btn-styled btn-base-1 mt-2">{{ translate('Download service sample') }}</button></a>
                                </div>
                            </div>

                            <div class="form-box bg-white mt-4">
                                <div class="form-box-content p-3">
                                    <table class="table mb-0 table-bordered" style="font-size:14px;background-color: #cce5ff;border-color: #b8daff">
                                        <tr>
                                            <td>{{ translate('1. Want to know which category your product lies in? Click on instruction for categories button given below.')}}</td>
                                        </tr>
                                        <tr >
                                            <td>{{ translate('2. Want to know which brand your product lies in? Click on instruction for brands button given below.')}}</td>
                                        </tr>
                                    </table>
                                    <?php $categories = \App\Category::all(); ?>
                                    <button id='cat' class='btn btn-styled btn-base-1 mt-2'>Instruction for Categories</button>
                                    <!--Category Modal -->
                                    <div class="modal fade" id="categorySelectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('Select Category') }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="target-category heading-6">
                                                        <span class="mr-3">{{ translate('Target Category')}}:</span>
                                                        <span>{{ translate('category')}} > {{ translate('subcategory')}} > {{ translate('subsubcategory')}}</span>
                                                    </div>
                                                    <div class="row no-gutters modal-categories mt-4 mb-2">
                                                        <div class="col-4">
                                                            <div class="modal-category-box c-scrollbar">
                                                                <div class="sort-by-box">
                                                                    <form role="form" class="search-widget">
                                                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search Category') }}" onkeyup="filterListItems(this, 'categories')">
                                                                        <button type="button" class="btn-inner">
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-category-list has-right-arrow">
                                                                    <ul id="categories">
                                                                        @foreach ($categories as $key => $category)
                                                                            <li onclick="get_subcategories_by_category(this, {{ $category->id }})">{{  __($category->name) }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="modal-category-box c-scrollbar" id="subcategory_list">
                                                                <div class="sort-by-box">
                                                                    <form role="form" class="search-widget">
                                                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search SubCategory') }}" onkeyup="filterListItems(this, 'subcategories')">
                                                                        <button type="button" class="btn-inner">
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-category-list has-right-arrow">
                                                                    <ul id="subcategories">

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="modal-category-box c-scrollbar" id="subsubcategory_list">
                                                                <div class="sort-by-box">
                                                                    <form role="form" class="search-widget">
                                                                        <input class="form-control input-lg" type="text" placeholder="{{ translate('Search SubSubCategory') }}" onkeyup="filterListItems(this, 'subsubcategories')">
                                                                        <button type="button" class="btn-inner">
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-category-list">
                                                                    <ul id="subsubcategories">

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button data-toggle="modal" data-target="#brand-btn" class="btn btn-styled btn-base-1 mt-2">{{ translate('Instruction for Brands')}}</button>

                                    <!-- Brand Pop-up -->

                                    <!-- The Modal -->
                                    <div class="modal fade" id="brand-btn">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                
                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <select class="form-control mb-3 selectpicker" data-placeholder="{{ translate('Check All Our Brands') }}" id="brands" name="brand_id">
                                                                <option value="">{{ ('See All Our Brands Here') }}</option>
                                                                @foreach (\App\Brand::all() as $brand)
                                                                    <option  value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br><br><br><br><br><br><br><br><br><br><br><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form class="form-horizontal" action="{{ route('bulk_product_upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-box bg-white mt-4">
                                    <div class="form-box-title px-3 py-2">
                                        {{ translate('Upload CSV File')}}
                                    </div>
                                    <div class="form-box-content p-3">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>{{ translate('CSV')}}</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="file" name="bulk_file" id="file-6" class="custom-input-file custom-input-file--4"/>
                                                <label for="file-6" class="mw-100 mb-3">
                                                    <span></span>
                                                    <strong>
                                                        <i class="fa fa-upload"></i>
                                                        {{ translate('Choose CSV File')}}
                                                    </strong>
                                                </label>
                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>As an option you can upload an existing brochure of the service in this listing. Your brochure will appear next to description in your listing.</p>"><i class="fa fa-question-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-box mt-4 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Upload') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        $('#cat').click(function(){
            $('#categorySelectModal').modal('show');
        });
        var category_name = "";
        var subcategory_name = "";
        var subsubcategory_name = "";

        var category_id = null;
        var subcategory_id = null;
        var subsubcategory_id = null;

        $(document).ready(function(){
            $('#subcategory_list').hide();
            $('#subsubcategory_list').hide();
        });

        function list_item_highlight(el){
            $(el).parent().children().each(function(){
                $(this).removeClass('selected');
            });
            $(el).addClass('selected');
        }

        function get_subcategories_by_category(el, cat_id){
            list_item_highlight(el);
            category_id = cat_id;
            subcategory_id = null;
            subsubcategory_id = null;
            category_name = $(el).html();
            $('#subcategories').html(null);
            $('#subsubcategory_list').hide();
            $.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
                for (var i = 0; i < data.length; i++) {
                    $('#subcategories').append('<li onclick="get_subsubcategories_by_subcategory(this, '+data[i].id+')">'+data[i].name+'</li>');
                }
                $('#subcategory_list').show();
            });
        }

        function get_subsubcategories_by_subcategory(el, subcat_id){
            list_item_highlight(el);
            subcategory_id = subcat_id;
            subsubcategory_id = null;
            subcategory_name = $(el).html();
            $('#subsubcategories').html(null);
            $.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
                for (var i = 0; i < data.length; i++) {
                    $('#subsubcategories').append('<li onclick="confirm_subsubcategory(this, '+data[i].id+')">'+data[i].name+'</li>');
                }
                $('#subsubcategory_list').show();
            });
        }

        function confirm_subsubcategory(el, subsubcat_id){
            list_item_highlight(el);
            subsubcategory_id = subsubcat_id;
            subsubcategory_name = $(el).html();
    	}

        function get_attributes_by_subsubcategory(subsubcategory_id){
    		$.post('{{ route('subsubcategories.get_attributes_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
    		    $('#choice_attributes').html(null);
    		    for (var i = 0; i < data.length; i++) {
    		        $('#choice_attributes').append($('<option>', {
    		            value: data[i].id,
    		            text: data[i].name
    		        }));
    		    }
    		});
    	}

        function filterListItems(el, list){
            filter = el.value.toUpperCase();
            li = $('#'+list).children();
            for (i = 0; i < li.length; i++) {
                if ($(li[i]).html().toUpperCase().indexOf(filter) > -1) {
                    $(li[i]).show();
                } else {
                    $(li[i]).hide();
                }
            }
        }

        function closeModal(){
            if(category_id > 0 && subcategory_id > 0){
                $('#category_id').val(category_id);
                $('#subcategory_id').val(subcategory_id);
                $('#subsubcategory_id').val(subsubcategory_id);
                $('#product_category').html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
                $('#categorySelectModal').modal('hide');
            }
            else{
                alert('Please choose categories...');
                // console.log(category_id);
                // console.log(subcategory_id);
                // console.log(subsubcategory_id);
            }
        }
    </script>     
@endsection