<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Accepted Terms & Conditions</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/images/favicon.png') }}"
    >

</head>

<body>

    {{-- MAIN WRAPPER --}}

    <div id="main-wrapper">

        {{-- TENANT NAVIGATION --}}

        @include('tenant.nav')

        {{-- PAGE WRAPPER --}}

        <div class="page-wrapper">

            <div class="container-fluid">

                {{-- PAGE TITLE --}}

                <div class="row page-titles">

                    <div class="col-md-6 align-self-center">

                        <h3 class="text-themecolor">
                            Accepted Terms & Conditions
                        </h3>

                    </div>

                    <div class="col-md-6 align-self-center text-end">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('tenant.dashboard') }}">
                                    Dashboard
                                </a>

                            </li>

                            <li class="breadcrumb-item active">
                                Accepted Terms & Conditions
                            </li>

                        </ol>

                    </div>

                </div>

                {{-- SUCCESS MESSAGE --}}

                @if(session('success'))

                    <div class="alert alert-success">

                        <i class="fa fa-check-circle"></i>

                        {{ session('success') }}

                    </div>

                @endif

                {{-- ERROR MESSAGE --}}

                @if(session('error'))

                    <div class="alert alert-danger">

                        <i class="fa fa-exclamation-circle"></i>

                        {{ session('error') }}

                    </div>

                @endif

                {{-- ====================================================== --}}
                {{-- ACCEPTANCE STATUS --}}
                {{-- ====================================================== --}}

                @if($acceptedAt)

                    <div class="alert alert-success">

                        <i class="fa fa-check-circle"></i>

                        <strong>Terms & Conditions Accepted</strong>

                        <br>

                        <span>
                            Accepted At:
                            {{ \Carbon\Carbon::parse($acceptedAt)->format('d M Y, h:i A') }}
                        </span>

                    </div>

                @else

                    <div class="alert alert-warning">

                        <i class="fa fa-exclamation-triangle"></i>

                        You have not accepted any Terms & Conditions yet.

                    </div>

                @endif

                {{-- ====================================================== --}}
                {{-- TERMS & CONDITIONS ACCORDION --}}
                {{-- ====================================================== --}}

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <h4 class="card-title">
                                    Accepted Terms & Conditions
                                </h4>

                                <h6 class="card-subtitle">
                                    Terms & Conditions provided by your
                                    Tenant Admin
                                </h6>

                                <div
                                    id="accordion1"
                                    role="tablist"
                                    aria-multiselectable="true"
                                >

                                    @forelse($termsConditions as $index => $term)

                                        <div class="card m-b-0">

                                            {{-- TERM HEADING --}}

                                            <div
                                                class="card-header"
                                                role="tab"
                                                id="heading{{ $term->id }}"
                                            >

                                                <h5 class="mb-0">

                                                    <a
                                                        class="{{ $index !== 0 ? 'collapsed' : '' }} link"
                                                        data-bs-toggle="collapse"
                                                        data-bs-parent="#accordion1"
                                                        href="#collapse{{ $term->id }}"
                                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $term->id }}"
                                                    >

                                                        Q{{ $index + 1 }}.

                                                        {{ $term->heading }}

                                                    </a>

                                                </h5>

                                            </div>

                                            {{-- TERM DESCRIPTION --}}

                                            <div
                                                id="collapse{{ $term->id }}"
                                                class="collapse {{ $index === 0 ? 'show' : '' }}"
                                                role="tabpanel"
                                                aria-labelledby="heading{{ $term->id }}"
                                            >

                                                <div class="card-body">

                                                    {!! nl2br(e($term->description)) !!}

                                                </div>

                                            </div>

                                        </div>

                                    @empty

                                        <div class="alert alert-info">

                                            <i class="fa fa-info-circle"></i>

                                            No accepted Terms & Conditions
                                            are available.

                                        </div>

                                    @endforelse

                                </div>

                                {{-- NO ACCEPT BUTTON ON THIS PAGE --}}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- TENANT FOOTER --}}

    @include('tenant.footer')

</body>

</html>