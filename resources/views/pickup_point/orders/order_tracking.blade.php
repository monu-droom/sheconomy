@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title" style="font-weight: bold;">{{translate('Order Tracking')}}</h3>               
    </div>
    <div class="panel-body">
        <div class="container">
            <form action="{{ route('post.order.tracking') }}" method = 'POST'>
            @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service" style="font-weight: bold;">Service Provider</label>
                            <input type="text" name='service' class="form-control" placeholder="FedEx" id="service" style="border: solid;">
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="url" style="font-weight: bold;">URL</label>
                            <input type="text" name='url' class="form-control" placeholder="https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=123456&cntry_code=us&locale=en_US" id="url" style="border: solid;">
                        </div>
                    </div>
                </div>
                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <br>
        <style>

        </style>
        <div id='msg'></div>
        <div class="container">
            <div class="col-md-10">
            <table class="table table-responsive table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>SKU</th>
                        <th>Service Provider</th>
                        <th>URL</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($all_order_trackings as $order_tracking)
                    <tr id="{{$index}}}">
                        <td>{{ $index }}</td>
                        <?php $index += 1; ?>
                        <td>
                            <input type="text" class="row-data" name="sku" value="{{$order_tracking->sku}}" disabled>
                        </td>
                        <td>
                            <input type="text" style="width: 50%" class="row-data" name="service" value="{{$order_tracking->service_provider}}">
                        </td>
                        <td>
                            <input type="text" style="width: 100%" class="row-data" name="url" value="{{$order_tracking->url}}">
                        </td>
                        <td>
                            <button type="submit" onclick="update()" class="btn btn-styled btn-base-1 btn-primary">{{ translate('Update')}}</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>    
            </div>
        </div> 
    </div>    
</div>

@endsection


@section('script')
    <script type="text/javascript">
        function update() {          
                var rowId = event.target.parentNode.parentNode.id;
                var data = document.getElementById(rowId).querySelectorAll(".row-data");
                var sku = data[0].value;
                var service = data[1].value;
                var url = data[2].value;
                $.post('{{ route('post.order.tracking') }}', {_token:'{{ csrf_token() }}',
                    service : service,
                    sku : sku,
                    url : url,
                    },
                    function(data){
                    data = JSON.parse(data);
                    if(data.status == 1){
                        $('#msg').html(data.message).fadeIn('slow');
                    }else{
                        $('#msg').html(data.message).fadeIn('slow');
                    }    
                    location.reload();
                });
            }
    </script>
@endsection
