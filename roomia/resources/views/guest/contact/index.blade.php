@extends('layouts.guest')

@section('title', 'Liên hệ')

@section('content')
    <div class="container py-4">
        <div class="rm-about-bg p-3 p-md-4">

            @include('partials.guest.contact.hero')

            @includeIf('partials.general.alerts')

            <div class="row g-3">
                <div class="col-12 col-lg-7">
                    @include('partials.guest.contact.form', ['prefill' => $prefill])
                </div>
                <div class="col-12 col-lg-5">
                    @include('partials.guest.contact.sidebar')
                </div>
            </div>

        </div>
    </div>
@endsection