<script src="{{ asset('public/dist/js/popper.min.js') }}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{ asset('public/dist/libraries/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- Select2 -->
<script src="{{ asset('public/dist/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<!-- moment -->
<script src="{{ asset('public/dist/js/moment.min.js') }}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/admin/templates/js/app.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    "use strict";
    var url = "{{ url('change-lang') }}";
    var token = "{{ csrf_token() }}";
 </script>
<script src="{{ asset('public/admin/customs/js/body_script.min.js') }}"></script>
@yield('body_script')
