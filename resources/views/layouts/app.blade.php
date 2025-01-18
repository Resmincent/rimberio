<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->


<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8" />
    <title>Rimberio</title>
    <meta name="description" content="Login page example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    {{-- <link href="{{asset('metch')}}/css/pages/login/classic/login-3f552.css?v=7.1.8" rel="stylesheet" type="text/css" /> --}}
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{asset('metch')}}/plugins/global/plugins.bundlef552.css?v=7.1.8" rel="stylesheet" type="text/css" />
    <link href="{{asset('metch')}}/plugins/custom/prismjs/prismjs.bundlef552.css?v=7.1.8" rel="stylesheet" type="text/css" />
    <link href="{{asset('metch')}}/css/style.bundlef552.css?v=7.1.8" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{asset('metch')}}/css/themes/layout/header/base/lightf552.css?v=7.1.8" rel="stylesheet" type="text/css" />
    <link href="{{asset('metch')}}/css/themes/layout/header/menu/lightf552.css?v=7.1.8" rel="stylesheet" type="text/css" />
    <link href="{{asset('metch')}}/css/themes/layout/brand/darkf552.css?v=7.1.8" rel="stylesheet" type="text/css" />
    <link href="{{asset('metch')}}/css/themes/layout/aside/darkf552.css?v=7.1.8" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('metch/media/logos/rimberio.png') }}" />

    <!--end::Layout Themes-->
    <!-- Hotjar Tracking Code for keenthemes.com -->
    <style>
        .login.login-3 .login-forgot,
        .login.login-3 .login-signin,
        .login.login-3 .login-signup {
            display: none
        }

        .login.login-3.login-signin-on .login-signup {
            display: none
        }

        .login.login-3.login-signin-on .login-signin {
            display: block
        }

        .login.login-3.login-signin-on .login-forgot {
            display: none
        }

        .login.login-3.login-signup-on .login-signup {
            display: block
        }

        .login.login-3.login-signup-on .login-signin {
            display: none
        }

        .login.login-3.login-signup-on .login-forgot {
            display: none
        }

        .login.login-3.login-forgot-on .login-signup {
            display: none
        }

        .login.login-3.login-forgot-on .login-signin {
            display: none
        }

        .login.login-3.login-forgot-on .login-forgot {
            display: block
        }

        .login.login-3 .login-form {
            width: 100%;
            max-width: 450px
        }

        @media (max-width:575.98px) {
            .login.login-3 .login-form {
                width: 100%;
                max-width: 100%
            }
        }

    </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!-- Google Tag Manager (noscript) -->
    <!-- End Google Tag Manager (noscript) -->
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-3 login-signin-on d-flex flex-row-fluid" id="kt_login">
            <div class="d-flex flex-center bgi-size-cover flex-row-fluid" style="background-image: url({{asset('metch')}}/media/bg/bg-1.jpg);">
                <div class="login-form text-center text-white position-relative overflow-hidden">
                    <!--begin::Login Header-->
                    <!--end::Login Header-->
                    @yield('auth')

                </div>
            </div>
        </div>
        <!--end::Login-->
    </div>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576
                , "md": 768
                , "lg": 992
                , "xl": 1200
                , "xxl": 1400
            }
            , "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff"
                        , "primary": "#3699FF"
                        , "secondary": "#E5EAEE"
                        , "success": "#1BC5BD"
                        , "info": "#8950FC"
                        , "warning": "#FFA800"
                        , "danger": "#F64E60"
                        , "light": "#E4E6EF"
                        , "dark": "#181C32"
                    }
                    , "light": {
                        "white": "#ffffff"
                        , "primary": "#E1F0FF"
                        , "secondary": "#EBEDF3"
                        , "success": "#C9F7F5"
                        , "info": "#EEE5FF"
                        , "warning": "#FFF4DE"
                        , "danger": "#FFE2E5"
                        , "light": "#F3F6F9"
                        , "dark": "#D6D6E0"
                    }
                    , "inverse": {
                        "white": "#ffffff"
                        , "primary": "#ffffff"
                        , "secondary": "#3F4254"
                        , "success": "#ffffff"
                        , "info": "#ffffff"
                        , "warning": "#ffffff"
                        , "danger": "#ffffff"
                        , "light": "#464E5F"
                        , "dark": "#ffffff"
                    }
                }
                , "gray": {
                    "gray-100": "#F3F6F9"
                    , "gray-200": "#EBEDF3"
                    , "gray-300": "#E4E6EF"
                    , "gray-400": "#D1D3E0"
                    , "gray-500": "#B5B5C3"
                    , "gray-600": "#7E8299"
                    , "gray-700": "#5E6278"
                    , "gray-800": "#3F4254"
                    , "gray-900": "#181C32"
                }
            }
            , "font-family": "Poppins"
        };

    </script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{asset('metch')}}/plugins/global/plugins.bundlef552.js?v=7.1.8"></script>
    <script src="{{asset('metch')}}/plugins/custom/prismjs/prismjs.bundlef552.js?v=7.1.8"></script>
    <script src="{{asset('metch')}}/js/scripts.bundlef552.js?v=7.1.8"></script>
    <!--end::Global Theme Bundle-->
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{asset('metch')}}/js/pages/custom/login/login-generalf552.js?v=7.1.8"></script>
    <!--end::Page Scripts-->
</body>
</html>
