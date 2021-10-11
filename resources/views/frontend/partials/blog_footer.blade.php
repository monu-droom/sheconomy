@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4">
        <div class="container text-dark">
            <div class="row">
                <div class="col">
                    <h3 style="text-align:center">SHEconomy Blog</h3>
                    <div class="p-4 bg-white">
                    @foreach($blog as $blo)
                       <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <img style="height: 400px; width: 720px;" class="img-responsive img-thumbnail" src="{{ asset('public/uploads/'.$blo->image) }}" alt="" class="src">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <div class="lead font-weight-bold">{{ $blo->name }}</div>
                            </div>
                       </div>
                       <br>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <p>{{ $blo->description }}</p>
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
