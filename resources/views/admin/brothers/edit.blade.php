@extends('layouts.admin')

@section('title', 'Editar Hermano')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">
        Editar Hermano
    </h1>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('brothers.update', $brother) }}">
            @method('PUT')
            @include('admin.brothers._form', ['brother' => $brother])
        </form>
    </div>

</div>
@endsection
