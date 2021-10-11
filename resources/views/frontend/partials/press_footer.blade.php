@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4">
        <div class="container text-dark">
            <div class="row">
                <div class="col">
                    <h3 style="text-align:center">SHEconomy Press</h3>
                    <div class="p-4 bg-white">
                    @foreach($press as $pre)
                       <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <img style="height: 400px; width: 720px;" class="img-responsive img-thumbnail" src="{{ asset('public/uploads/'.$pre->image) }}" alt="" class="src">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <div class="lead font-weight-bold">{{ $pre->name }}</div>
                            </div>
                       </div>
                       <br>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <p>{{ $pre->description }}</p>
                            </div>
                       </div>
                       <br>
                    @endforeach
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
