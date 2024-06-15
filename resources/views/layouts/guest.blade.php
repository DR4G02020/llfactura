<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

        <!-- Scripts -->
        <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.green.min.css"
/>
    </head>
    <body class="">
        <div class="" style="text-align: center; margin: 50px; margin-bottom: 50px;">
            <div>
                <a href="/" wire:navigate>
                    {{-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
                {{-- <svg fill="#24a845" height="256px" width="256px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 493.423 493.423" xml:space="preserve" stroke="#24a845"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="5.921076"></g><g id="SVGRepo_iconCarrier"> <g id="ubuntu"> <g> <g> <path d="M168.839,241.198c0-38.117,17.894-72.05,45.685-93.896L171.988,79.22c-35.648,25.603-62.472,62.66-75.127,105.796 c19.811,12.751,32.949,35.031,32.949,60.353c0,24.424-12.143,45.957-30.783,58.918c13.606,40.86,40.12,75.838,74.706,100.113 l39.559-70.358C186.187,312.204,168.839,278.724,168.839,241.198z"></path> </g> </g> <g> <path d="M109.704,245.368c0,28.484-23.132,51.592-51.609,51.592c-28.491,0-51.606-23.107-51.606-51.592 c0-28.47,23.115-51.577,51.606-51.577C86.572,193.791,109.704,216.898,109.704,245.368z"></path> </g> <g> <g> <path d="M399.494,370.126c12.002,0,23.301,2.936,33.23,8.149c30.924-32.591,50.906-75.595,54.211-123.228l-80.148-1.551 c-6.171,60.111-56.954,106.941-118.677,106.941c-17.084,0-33.388-3.594-48.101-10.093l-39.841,69.704 c26.56,13.069,56.376,20.411,87.941,20.411c13.622,0,26.981-1.379,39.854-4.006C330.709,399.381,361.68,370.126,399.494,370.126z "></path> </g> </g> <g> <path d="M451.071,441.847c0,28.478-23.084,51.576-51.577,51.576c-28.493,0-51.594-23.098-51.594-51.576 c0-28.5,23.101-51.592,51.594-51.592C427.987,390.255,451.071,413.347,451.071,441.847z"></path> </g> <g> <g> <path d="M438.211,110.152c-11.677,8.269-25.968,13.163-41.399,13.163c-39.637,0-71.73-32.102-71.73-71.715 c0-2.104,0.094-4.139,0.25-6.181c-12.05-2.307-24.503-3.491-37.222-3.491c-31.859,0-61.988,7.498-88.689,20.777l39.607,69.75 c14.979-6.748,31.593-10.544,49.082-10.544c61.177,0,111.601,46.074,118.491,105.414l80.147-2.447 C483.209,181.12,465.487,141.372,438.211,110.152z"></path> </g> </g> <g> <path d="M448.374,51.601c0,28.492-23.038,51.592-51.561,51.592c-28.491,0-51.592-23.1-51.592-51.592 C345.22,23.107,368.321,0,396.812,0C425.335,0,448.374,23.107,448.374,51.601z"></path> </g> </g> </g></svg>     --}}
                    <img src="{{asset('logosLLFactura/LLFACTURA.png')}}" alt="">
                </a>
            </div>

            <div class="">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
