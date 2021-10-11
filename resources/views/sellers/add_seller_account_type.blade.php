@extends('layouts.app')

@section('content')


<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title text-center" style="font-weight: bold;">{{ translate('Add Seller Account Type') }}</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="{{ route('post.seller.account.type') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="col-lg-3">
                        <label class="control-label">{{ translate('Account Type') }}</label>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="seller_account_type" placeholder="{{ translate('Individual or Registered Business/Company') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-12 text-right">
                        <button class="btn btn-purple" type="submit">{{ translate('Add') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
