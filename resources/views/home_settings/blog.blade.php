@extends('layouts.app')

@section('content')


    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="panel">
        <div class="panel-body">
            <h3>Blog</h3>
            <br>
            <form method="POST" action="{{ route('post_home.blog') }}" enctype="multipart/form-data">
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
                        <textarea class="form-control editor" name="description" id="description"
                        data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'>
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
            @if($blog != null)
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
                    @foreach($blog as $blo)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $blo->name }}</td>
                        <td><img style="height: 80px; width: 120px;" class="img-responsive img-thumbnail" src="{{ asset('public/uploads/'.$blo->image) }}" alt="" class="src"></td>
                        <td>{{ $blo->description }}</td>
                    </tr>
                    <?php $i = $i + 1; ?>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
@endsection
