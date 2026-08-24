<!DOCTYPE html>
<html lang="en">
    @include('home_temp.layouts.head')
<body>
    <div class="wrapper">
        @include('home_temp.layouts.navbar')
        
        <div class="content-wrapper">
            <section class="content">
                <div class="">
                    <main class="main">
                        @yield("content")
                    </main>
                </div>
            </section>

            @include('home_temp.components.floating_wa')
        
            @include('home_temp.layouts.footer')
        </div>
    </div>
    
    @include('home_temp.layouts.script')
</body>
</html>