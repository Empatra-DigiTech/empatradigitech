<!DOCTYPE html>
<html lang="en">
    @include('patra.layouts.head')
    @trixassets
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('sweetalert::alert')
        @include('patra.layouts.topbar')
        @include('patra.layouts.sidebar')
        <div class="content-wrapper">
            @include('patra.components.breadcumb')
            <section class="content">
                <div class="container-fluid">
                    @yield("content")
                </div>
            </section>
        </div>
    </div>
    @include('patra.layouts.script')
</body>
</html>
