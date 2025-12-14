@extends('layouts.app')

@section('title', 'Sobre Nosotros | CIA Ministerio')

@section('content')

{{-- ================= HEADER SIMPLE ================= --}}
<section class="bg-[#0D0703] py-28">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-[#D4AF37]">
            Nuestra Casa Espiritual
        </h1>

        <p class="mt-6 text-gray-400 text-lg max-w-3xl mx-auto">
            Más que un lugar, somos una familia formada por Dios
            para cumplir Su propósito en esta generación.
        </p>
    </div>
</section>

{{-- ================= BLOQUE NARRATIVO ================= --}}
<section class="bg-[#1C150E] py-20 text-gray-200">
    <div class="max-w-4xl mx-auto px-6 space-y-8 text-lg leading-relaxed">

        <p>
            Centro Internacional de Adoración nace con el llamado de
            establecer una iglesia fundamentada en la Palabra de Dios,
            sensible a la voz del Espíritu Santo y comprometida con
            una adoración que transforma vidas.
        </p>

        <p class="text-gray-300">
            Creemos que la iglesia no es un edificio, sino personas
            que han sido redimidas por Cristo y enviadas a manifestar
            Su Reino con amor, verdad y poder. Nuestro anhelo es ver
            generaciones caminando en identidad, santidad y propósito.
        </p>

        <p>
            Cada reunión, cada enseñanza y cada servicio está orientado
            a glorificar a Jesús y edificar Su cuerpo con excelencia
            y orden espiritual.
        </p>
    </div>
</section>

{{-- ================= SECCIÓN DESTACADA ================= --}}
<section class="bg-[#0D0703] py-24">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-12 text-center">

        <div>
            <h3 class="text-2xl font-bold text-[#D4AF37] mb-4">Nuestra Visión</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Ser una iglesia que manifieste la presencia de Dios,
                forme generaciones con carácter y transforme
                su entorno para la gloria de Cristo.
            </p>
        </div>

        <div>
            <h3 class="text-2xl font-bold text-[#D4AF37] mb-4">Nuestra Misión</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Predicar el evangelio, formar discípulos,
                levantar líderes y extender el Reino de Dios
                con integridad y excelencia.
            </p>
        </div>

        <div>
            <h3 class="text-2xl font-bold text-[#D4AF37] mb-4">Nuestros Valores</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Fidelidad a la Palabra, adoración genuina,
                servicio, unidad, integridad y amor por las personas.
            </p>
        </div>

    </div>
</section>

{{-- ================= CIERRE ================= --}}
<section class="bg-[#1C150E] py-24 text-center">
    <h2 class="text-3xl font-bold text-[#D4AF37]">
        Un lugar para crecer en Dios
    </h2>

    <p class="mt-6 text-gray-300 max-w-xl mx-auto">
        Te invitamos a conocer nuestra comunidad y ser parte
        de lo que Dios está haciendo en este tiempo.
    </p>
</section>

@endsection
