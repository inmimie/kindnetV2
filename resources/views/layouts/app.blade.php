<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KINDNET') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @auth
            @if(auth()->user()->role === 'applicant')
                <x-toast />
                
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        let lastCount = sessionStorage.getItem('unread_notifications_count');
                        
                        function checkNotifications() {
                            fetch('{{ route('applicant.notifications.unread-count') }}', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const newCount = data.unread_count;
                                
                                // Update navbar badges
                                const badgeDesktop = document.getElementById('nav-badge-desktop');
                                const badgeMobile = document.getElementById('nav-badge-mobile');
                                
                                if (badgeDesktop) {
                                    if (newCount > 0) {
                                        badgeDesktop.textContent = newCount;
                                        badgeDesktop.classList.remove('hidden');
                                    } else {
                                        badgeDesktop.classList.add('hidden');
                                    }
                                }
                                
                                if (badgeMobile) {
                                    if (newCount > 0) {
                                        badgeMobile.textContent = newCount;
                                        badgeMobile.classList.remove('hidden');
                                    } else {
                                        badgeMobile.classList.add('hidden');
                                    }
                                }

                                // Trigger Toast if unread count increased
                                if (lastCount !== null && parseInt(newCount) > parseInt(lastCount)) {
                                    if (data.latest_unread) {
                                        window.dispatchEvent(new CustomEvent('show-toast', {
                                            detail: {
                                                title: data.latest_unread.charity_type_name,
                                                message: data.latest_unread.message,
                                                url: '{{ route('applicant.notifications.index') }}'
                                            }
                                        }));
                                    }
                                }
                                
                                lastCount = newCount;
                                sessionStorage.setItem('unread_notifications_count', newCount);
                            })
                            .catch(error => console.error('Error fetching notifications:', error));
                        }

                        // Check immediately on load
                        checkNotifications();

                        // Poll every 10 seconds
                        setInterval(checkNotifications, 10000);
                    });
                </script>
            @endif
        @endauth
    </body>
</html>
