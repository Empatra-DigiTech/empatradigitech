<!DOCTYPE html>
<html lang="en">
    @include('kir.layouts.head')
    @trixassets
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('sweetalert::alert')
        @include('kir.layouts.topbar')
        @include('kir.layouts.sidebar')
        <div class="content-wrapper">
            @include('kir.components.breadcumb')
            <section class="content">
                <div class="container-fluid">
                    @yield("content")
                </div>
            </section>
        </div>
    </div>
    @include('kir.layouts.script')
</body>
</html>