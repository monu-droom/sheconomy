@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="p-4 bg-white">
                       <h1>Refund Policy</h1>
                       <p><?php echo $seller->refund_policy; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
