@extends('layouts.app')

@section('content')

<section class="bg-[#f4f6fb] py-20 text-center">

    <h1 class="text-4xl font-extrabold text-[#2128A6] mb-6">
        Categoría: {{ ucfirst($slug) }}
    </h1>

    <p class="text-gray-700 text-lg">
        Aquí irán los productos de la categoría <b>{{ ucfirst($slug) }}</b>.
    </p>

</section>

@endsection
