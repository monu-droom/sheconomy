@extends('layouts.app')

@section('content')


    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="panel">
        <div class="panel-body">
            <h3>Press</h3>
            <br>
            <form action="{{ route('post_home.press') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="form-group col-md-5">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-5">
                        <label for="description">Description</label>                        
                        <textarea class="form-control editor" name="description" id="description">
                        </textarea> 
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-5">
                        <label class="form-label" for="image">Image</label>
                        <input type="file" name="image" class="form-control" id="image" />
                    </div> 
                </div> 
                <br>           
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>            
            <br>
            @if($press != null)
            <table class="table table-striped res-table mar-no" cellspacing="0" width="70%">
                <thead>
                <tr>
                    <th>S.No.</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Image')}}</th>
                    <th>{{translate('Description')}}</th>
                </tr>
                </thead>
                <?php $i = 1;  ?>
                <tbody>
                    @foreach($press as $pre)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $pre->name }}</td>
                        <td><img style="height: 80px; width: 120px;" class="img-responsive img-thumbnail" src="{{ asset('public/uploads/'.$pre->image) }}" alt="" class="src"></td>
                        <td>{{ $pre->description }}</td>
                    </tr>
                    <?php $i = $i + 1; ?>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
@endsection
