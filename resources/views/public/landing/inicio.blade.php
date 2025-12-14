@extends('layouts.app')

@section('content')


{{-- HERO SECTION --}}
<section class="bg-cover bg-center bg-no-repeat bg-fixed relative"
         style="background-image: url('{{ asset('images/hero.webp') }}');">
    
    {{-- Overlay oscuro para mejorar la legibilidad del texto (muy recomendado en fondos claros o detallados) --}}
    <div class="absolute inset-0 bg-black/70"></div>
    
    <div class="relative max-w-7xl mx-auto px-6 py-24 text-center z-10">
        <img src="{{ asset('images/logo.webp') }}" 
             alt="Logo Iglesia" 
             class="mx-auto mb-6 w-40 drop-shadow-xl">

        <h1 class="text-4xl md:text-6xl font-bold text-[#D4AF37]">
            Centro Internacional de Adoración
        </h1>

        <p class="mt-4 text-lg text-gray-300 max-w-2xl mx-auto">
            "Un lugar donde mora la presencia de Dios."
        </p>

        <a href="#sobre"
           class="inline-block mt-10 bg-[#D4AF37] hover:bg-[#C6A433] text-black font-semibold px-8 py-3 rounded-full transition">
            Conócenos
        </a>
    </div>
</section>


{{-- SOBRE NOSOTROS --}}
<section id="sobre" class="bg-[#1C150E] text-gray-200 py-20">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12">
        
        <div>
            <h2 class="text-3xl font-bold text-[#D4AF37] mb-4">
                Sobre Nuestra Iglesia
            </h2>

            <p class="leading-relaxed">
                Somos un ministerio dedicado a la enseñanza bíblica, la adoración genuina 
                y la expansión del Reino de Dios. Nuestra misión es levantar generaciones
                que vivan para honrar al Señor con excelencia, amor y servicio.
            </p>

            <p class="mt-4 leading-relaxed text-gray-300">
                Te invitamos a ser parte de este mover y experimentar un encuentro real 
                con la presencia del Espíritu Santo.
            </p>
        </div>

        <div>
            <img src="{{ asset('images/PastorPredicando.webp') }}" 
                 class="rounded-lg shadow-lg border border-[#D4AF37]/40" 
                 alt="">
        </div>
    </div>
</section>


{{-- SERVICIOS --}}
<section class="bg-[#F5F5F5] py-20">
    <div class="max-w-7xl mx-auto px-6">
        
        <h2 class="text-3xl font-bold text-center text-[#1C150E]">
            Nuestros Servicios
        </h2>

        <div class="mt-12 grid md:grid-cols-3 gap-10">
            
            <div class="bg-white shadow-lg rounded-lg p-8 border-t-4 border-[#D4AF37]">
                <h3 class="text-xl font-bold text-[#1C150E] mb-2">Adoración & Palabra</h3>
                <p class="text-gray-700 text-sm">
                    Reuniones donde el Espíritu de Dios ministra con poder y gracia.
                </p>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-8 border-t-4 border-[#E25822]">
                <h3 class="text-xl font-bold text-[#1C150E] mb-2">Ministerio Juvenil</h3>
                <p class="text-gray-700 text-sm">
                    Jóvenes apasionados por Cristo, formados en propósito y liderazgo.
                </p>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-8 border-t-4 border-[#B22222]">
                <h3 class="text-xl font-bold text-[#1C150E] mb-2">Escuela Bíblica</h3>
                <p class="text-gray-700 text-sm">
                    Formación teológica sólida para discípulos que desean crecer.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- CTA FINAL --}}
<section class="bg-[#1C150E] text-center py-20">
    <h2 class="text-3xl font-bold text-[#D4AF37]">
        ¿Quieres conocer más o visitarnos?
    </h2>

    <p class="mt-4 text-gray-300 max-w-xl mx-auto">
        Estamos para servirte. Puedes ver nuestros recursos, prédicas, 
        horarios o enviarnos un mensaje.
    </p>

    <a href="/contacto"
       class="inline-block mt-8 bg-[#D4AF37] hover:bg-[#C6A433] text-black font-semibold px-8 py-3 rounded-full transition">
        Contáctanos
    </a>
</section>

@endsection
