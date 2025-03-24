<?php declare(strict_types=1); ?>
<div class="w-64 bg-white shadow-md h-screen p-4">
    @if(Auth::check())
        @php
            $role = Auth::user()->getRoleNames()->first();
        @endphp

        @if($role === 'admin')
            @include('layouts.sidebars.admin_sidebar')
        @elseif($role === 'superadmin')
            @include('layouts.sidebars.superadmin_sidebar')
        @elseif($role === 'client')
            @include('layouts.sidebars.client_sidebar')
        @elseif($role === 'chauffeur')
            @include('layouts.sidebars.chauffeur_sidebar')
        @elseif($role === 'agent')
            @include('layouts.sidebars.agent_sidebar')
            @elseif($role === 'super-admin')
            @include('layouts.sidebars.superadmin_sidebar')
        @elseif($role === 'entreprise')
            @include('layouts.sidebars.entreprise_sidebar')
        @endif
    @endif
</div>
