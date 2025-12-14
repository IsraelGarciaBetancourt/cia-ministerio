@extends('layouts.admin')

@section('title', 'Nuevo Hermano')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Registrar Hermano</h1>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('brothers.store') }}">
            @include('admin.brothers._form')
        </form>
    </div>

</div>
@endsection
