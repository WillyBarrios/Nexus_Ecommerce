@extends('layouts.app')

@section('content')

{{-- SECCIÓN PRINCIPAL --}}
<section class="bg-[#f4f6fb] py-16">

    <div class="max-w-7xl mx-auto px-6">

        {{-- TÍTULO --}}
        <h1 class="text-4xl md:text-5xl font-extrabold text-center text-[#2128A6] mb-12">
            Categorías
        </h1>

        @php
            $categories = [
               
                ['name' => 'Belleza',    'emoji' => '💄'],
               
                ['name' => 'Oficina',    'emoji' => '🏢'],

                ['name' => 'Hogar',      'emoji' => '🏠'],
                ['name' => 'Niños',      'emoji' => '🧸'],
                ['name' => 'Deportes',   'emoji' => '🏀'],
                

                ['name' => 'Juguetes',   'emoji' => '🎮'],
                ['name' => 'Tecnología', 'emoji' => '💻'],
                ['name' => 'Ropa',      'emoji' => '👗'],
            ];
        @endphp

        {{-- GRID DE CATEGORÍAS --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-10">

            @foreach($categories as $category)
                <div class="flex flex-col items-center gap-3 cursor-pointer group">

                    {{-- CUADRO CON EMOJI --}}
                    <div class="w-24 h-24 bg-white rounded-2xl shadow-md flex items-center justify-center
                                text-4xl group-hover:scale-[1.05] group-hover:shadow-lg transition">
                        <span>{{ $category['emoji'] }}</span>
                    </div>

                    {{-- NOMBRE --}}
                    <p class="text-center text-lg font-semibold text-gray-700">
                        {{ $category['name'] }}
                    </p>
                </div>
            @endforeach

        </div>

    </div>

</section>

@endsection
