<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&family=Merriweather:wght@400;700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-serif, .titulo-mw { font-family: 'Merriweather', serif !important; }
    </style>
</head>

<body class="text-gray-200 antialiased bg-gray-900 selection:bg-[#F59E0B] selection:text-white transition-colors duration-300">
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="w-full max-w-6xl p-8 bg-gray-800 shadow-2xl shadow-black/50 sm:rounded-md border border-gray-700 animate-fade-in">
            {{ $slot }}
        </div>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swal === 'undefined') return;

            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                background: '#0d1420',
                color: '#fff'
            });

            const applyFieldError = (fieldName) => {
                const safeName = String(fieldName).replace(/"/g, '\\"');
                const selector = `[name="${safeName}"], [name="${safeName}[]"]`;
                const field = document.querySelector(selector);
                if (!field) return;

                field.setAttribute('aria-invalid', 'true');
                field.style.borderColor = '#ef4444';
                field.style.boxShadow = '0 0 0 1px #ef4444';
            };

            const errors = {!! json_encode($errors->getMessages()) !!};
            Object.entries(errors).forEach(([field, messages]) => {
                applyFieldError(field);
                (messages || []).forEach((message) => toast.fire({ icon: 'error', title: message }));
            });

            @if(session('sucesso'))
                toast.fire({ icon: 'success', title: {!! json_encode(session('sucesso')) !!} });
            @endif

            @if(session('error'))
                toast.fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
            @endif

            @if(session('status'))
                toast.fire({ icon: 'info', title: {!! json_encode(session('status')) !!} });
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    toast.fire({ icon: 'error', title: {!! json_encode($error) !!} });
                @endforeach
            @endif
        });
    </script>
</body>

</html>