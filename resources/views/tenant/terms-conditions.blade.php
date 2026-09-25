<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Terms & Conditions
    </title>


    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <style>

        body {
            background: #f5f6fa;
        }

        .terms-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .terms-card {
            width: 100%;
            max-width: 1000px;
            border: 0;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .terms-header {
            padding: 25px 30px;
            border-bottom: 1px solid #eee;
        }

        .terms-header h3 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .terms-content {
            padding: 30px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .term-card {
            border: 1px solid #e9ecef;
            margin-bottom: 8px;
            border-radius: 6px;
        }

        .term-card .card-header {
            background: #fff;
            border: 0;
            padding: 15px 20px;
        }

        .term-card .card-body {
            border-top: 1px solid #eee;
            padding: 20px;
            color: #666;
            line-height: 1.7;
        }

        .accept-area {
            padding: 20px 30px;
            border-top: 1px solid #eee;
            background: #fff;
        }

        .accept-checkbox {
            cursor: pointer;
        }

        .accept-btn {
            min-width: 180px;
        }

    </style>

</head>


<body>


<div class="terms-wrapper">

    <div class="card terms-card">


        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="terms-header">

            <div class="d-flex align-items-center">

                <div class="me-3">

                    <div
                        class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;"
                    >

                        <i class="fa fa-file-contract"></i>

                    </div>

                </div>


                <div>

                    <h3>
                        Terms & Conditions
                    </h3>

                    <p class="text-muted mb-0">

                        Please review and accept the
                        Terms & Conditions to continue.

                    </p>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- TERMS --}}
        {{-- ================================================= --}}

        <div class="terms-content">


            @forelse(
                $termsConditions
                as $index => $term
            )

                <div class="card term-card">

                    <div
                        class="card-header"
                        role="tab"
                        id="heading{{ $index }}"
                    >

                        <h5 class="mb-0">

                            <a
                                class="{{ $index === 0 ? '' : 'collapsed' }} link text-decoration-none"
                                data-bs-toggle="collapse"
                                href="#collapse{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $index }}"
                            >

                                <i class="fa fa-circle-check text-primary me-2"></i>

                                {{ $term->heading }}

                            </a>

                        </h5>

                    </div>


                    <div
                        id="collapse{{ $index }}"
                        class="collapse {{ $index === 0 ? 'show' : '' }}"
                        role="tabpanel"
                        aria-labelledby="heading{{ $index }}"
                    >

                        <div class="card-body">

                            {!! nl2br(e($term->description)) !!}

                        </div>

                    </div>

                </div>

            @empty

                <div class="alert alert-info">

                    <i class="fa fa-info-circle me-2"></i>

                    No Terms & Conditions are currently available.

                </div>

            @endforelse


        </div>


        {{-- ================================================= --}}
        {{-- ACCEPT AREA --}}
        {{-- ================================================= --}}

        <div class="accept-area">


            <form
                action="{{ route('tenant.terms.accept') }}"
                method="POST"
                id="termsAcceptForm"
            >

                @csrf


                <div class="form-check mb-3">

                    <input
                        class="form-check-input accept-checkbox"
                        type="checkbox"
                        id="acceptTerms"
                        required
                    >


                    <label
                        class="form-check-label"
                        for="acceptTerms"
                    >

                        I have read and agree to the
                        <strong>
                            Terms & Conditions
                        </strong>.

                    </label>

                </div>


                <div class="d-flex justify-content-end">

                    <button
                        type="submit"
                        class="btn btn-primary accept-btn"
                        id="acceptBtn"
                        disabled
                    >

                        <i class="fa fa-check me-2"></i>

                        I Accept & Continue

                    </button>

                </div>


            </form>


        </div>


    </div>

</div>


{{-- Bootstrap JS --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

    const checkbox =
        document.getElementById('acceptTerms');

    const button =
        document.getElementById('acceptBtn');


    checkbox.addEventListener(
        'change',
        function () {

            button.disabled =
                !this.checked;

        }
    );

</script>


</body>

</html>