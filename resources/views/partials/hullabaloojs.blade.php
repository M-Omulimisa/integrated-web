<script src="{{ asset('assets/libs/toast/js/hullabaloo.js') }}"></script>

<script>

     var hulla = new hullabaloo();

    @if(Session::has('success') || isset($success))
        hulla.send("{{ session('success') }}", "success");
    @elseif(Session::has('status'))
        hulla.send("{{ session('status') }}", "success");
    @elseif(Session::has('info'))
        hulla.send("{{ Session::get('info') }}", "info");
    @endif

    @if($errors->count() > 0)

    @php
        $error_list = '';
        foreach ($errors->all() as $error) {
            $error_list .= $error.'\r\n';
        }
    @endphp
        @php
            $message = $error_list;
            $type = 'danger';
        @endphp
        hulla.send("{{ $message }}", "{{ $type }}");
    @endif 
</script>