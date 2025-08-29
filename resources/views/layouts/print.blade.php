<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Gudang XYZ</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Print Styles -->
    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }
            body {
                font-family: Arial, sans-serif;
                line-height: 1.5;
                font-size: 12pt;
            }
            .print-container {
                padding: 0;
                margin: 0;
            }
            /* Hide any non-printable elements */
            .no-print {
                display: none !important;
            }
        }
        /* Common styles for both screen and print */
        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm;
        }
    </style>
</head>
<body class="bg-gray-100 print:bg-white">
    <!-- Print action buttons (only visible on screen) -->
    <div class="no-print fixed top-4 right-4 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak
        </button>
        <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tutup
        </button>
    </div>

    <!-- Print container -->
    <div class="print-container bg-white shadow-lg print:shadow-none">
        @yield('content')
    </div>
</body>
</html>
