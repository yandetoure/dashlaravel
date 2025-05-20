@extends('layouts.app') {{-- ou votre layout principal --}}

@section('content')
<div class="max-w-xl mx-auto p-4">
<div class="max-w-2xl mx-auto p-4 bg-white rounded shadow">
    <h2 class="text-3xl font-semibold mb-4">{{ $actu->title }}</h2>
    
    @if ($actu->image)
        <img src="{{ asset('storage/' . $actu->image) }}" alt="{{ $actu->title }}" class="w-full h-auto mb-4 rounded">
    @endif

    <div class="prose">
        {!! nl2br(e($actu->content)) !!}
    </div>

    <div class="mt-4">
        <a href="{{ route('actus.index') }}" class="text-blue-600 hover:underline">Retour à la liste</a>
        {{-- Si vous avez la gestion d'édition/suppression, ajoutez-les ici --}}
    </div>
</div>
@endsection
