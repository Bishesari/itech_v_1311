@php
    $roleCode = session('selected_role_code', 'newcomer');
@endphp

@includeIf('partials.sidebar.' . str_replace('_', '-', $roleCode))
