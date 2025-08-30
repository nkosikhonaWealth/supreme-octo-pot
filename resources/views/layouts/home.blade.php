<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<!-- Head -->
<head>
	@include('layouts.partials.head')
	@livewireStyles
</head>

<body class="stretched sticky-footer">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Header -->
		@include('layouts.partials.header')

		{{$slot}}

		<!-- Footer -->
		@include('layouts.partials.footer')

		<!-- Floating Contact / WhatsApp -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="uil uil-angle-up"></div>

	<!-- Foot -->
    @include('layouts.partials.foot')

    @stack('scripts')
    @livewireScripts
</body>
</html>
