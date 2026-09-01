<!DOCTYPE html>
<html lang="en">
    @include('home.layouts.head')
    @vite(['resources/css/home.css', 'resources/js/home.js'])
<body>
    <div class="wrapper">
        @include('home.layouts.navbar')
        
        <div class="content-wrapper">
            <section class="content">
                <div class="">
                    <main class="main"> 
                        @yield("content")
                    </main>
                </div>
            </section>

            @include('home.components.floating_wa')
        
            @include('home.layouts.footer')
        </div>
    </div>
    
    @include('home.layouts.script')
</body>
</html>