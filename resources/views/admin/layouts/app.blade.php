<!DOCTYPE html>
<html lang="ja">

{{--head start--}}
@include('admin.layouts.partials._head')
{{-- head end--}}

<body>

{{--header start--}}
@include('admin.layouts.partials.header')
{{--header end--}}

@yield('content')

{{--  footer start--}}
@include('admin.layouts.partials.footer')
{{--  footer end--}}

</body>

</html>
