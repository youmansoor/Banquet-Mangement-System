@php
    $footer = \App\Models\AdminFooterSetting::first();
@endphp

@if($footer && $footer->enabled)

<footer class="footer">

    ©{{ $footer->footer_text }}

    @if($footer->brand_name)
        {{ $footer->brand_name }}
    @endif

    @if($footer->link_text)
        <a
            href="{{ $footer->link_url ?: '#' }}"
            target="_blank"
            rel="noopener noreferrer"
        >
            {{ $footer->link_text }}
        </a>
    @endif

</footer>

@endif


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script src="{{ asset('assets/node_modules/jquery/dist/jquery.min.js') }}"></script>

<script src="{{ asset('assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('assets/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>

<script src="{{ asset('assets/dist/js/waves.js') }}"></script>

<script src="{{ asset('assets/dist/js/sidebarmenu.js') }}"></script>

<script src="{{ asset('assets/dist/js/custom.min.js') }}"></script>

{{-- Morris --}}

<script src="{{ asset('assets/node_modules/raphael/raphael-min.js') }}"></script>

<script src="{{ asset('assets/node_modules/morrisjs/morris.min.js') }}"></script>

{{-- Sparkline --}}

<script src="{{ asset('assets/node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

{{-- Toast --}}
<!--
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
-->

{{-- Dashboard --}}
<!--
<script src="{{ asset('assets/dist/js/dashboard1.js') }}"></script>
-->


<script>

    $(function () {

        if ($('#chat').length) {
            $('#chat').perfectScrollbar();
        }

        if ($('#msg').length) {
            $('#msg').perfectScrollbar();
        }

        if ($('#comment').length) {
            $('#comment').perfectScrollbar();
        }

        if ($('#todo').length) {
            $('#todo').perfectScrollbar();
        }

    });

</script>