<!DOCTYPE html>
<html lang="en">
    @include('home.home_temp.layouts.head')
    @vite(['resources/css/home.css', 'resources/js/home.js'])
<body>
    <div class="wrapper">
        @include('home.home_temp.layouts.navbar')
        
        <div class="content-wrapper">
            <section class="content">
                <div class="">
                    <main class="main"> 
                        @yield("content")
                    </main>
                </div>
            </section>

            @include('home.home_temp.components.floating_wa')
        
            @include('home.home_temp.layouts.footer')
        </div>
    </div>
    
    @include('home.home_temp.layouts.script')
</body>
</html>