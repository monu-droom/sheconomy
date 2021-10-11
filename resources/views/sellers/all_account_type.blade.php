@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('add.seller_account.type')}}" class="btn btn-rounded btn-info pull-right">{{translate('Add New Seller Account Type')}}</a>
        </div>
    </div>

    <br>

    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="panel">
        <div class="panel-body">
            <table class="table table-striped res-table mar-no" cellspacing="0" width="50%">
                <thead>
                <tr>
                    <th>S.No.</th>
                    <th>{{translate('Seller Account Type')}}</th>
                </tr>
                </thead>
                <tbody>
                    @if(!empty($account_type))
                    @foreach($account_type as $index => $account)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$account->account_type}}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
