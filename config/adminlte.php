<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'CALASANZ SCHOOL',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>CALASANZ </b>SCHOOL',
    'logo_img' => 'imagenes/logo.webp',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'imagenes/logo.webp',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'imagenes/logo.webp',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-maroon',
    'usermenu_image' => false,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-maroon',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-dark navbar-dark bg-indigo',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'indigo',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-dark',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'admin',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => false,
    'password_reset_url' => false, //'password/reset'
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false, //estaba en false
    // 'laravel_css_path' => 'css/app.css',
    // 'laravel_js_path' => 'js/app.js',
    'laravel_css_path' => 'resources/css/app.css',
    'laravel_js_path' => 'resources/js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        [
            'type'         => 'darkmode-widget',
            'topnav_right' => true, // Or "topnav => true" to place on the left.
        ],

        [
            'route'  => 'movimientos.crear',
            'title' => 'asdasd',
            'icon'    => 'fas fa-plus-circle',
            'shift'   => 'ml-4',
            'text' => 'Cobranza',
            'topnav' => true,
        ],
        [
            'text' => 'Pagos',
            'route'  => 'movimientos.proveedor',
            'icon'    => 'fas fa-minus-circle',
            'shift'   => 'ml-4',
            'topnav' => true,
        ],

        // Sidebar items:
        // [
        //     'type' => 'sidebar-menu-search',
        //     'text' => 'buscar',
        // ],
        [
            'text' => 'INICIO',
            'route' => 'home',
            'icon' => 'fa fa-home'
        ],
        [
            'text' => 'SALDOS',
            'route'  => 'montos.index',
            'icon' => 'fas fa-piggy-bank',
        ],

        [
            'text'    => 'Movimientos',
            'icon'    => 'fas fa-dollar-sign',
            'label'       => 'Nuevo',
            'active' => ['admin/movimientos', 'admin/movimientos/crear_gasto_proveedor', 'admin/movimientos/crear', 'admin/movimientos/crear_por_cliente'],
            'label_color' => 'info',
            'submenu' => [
                [
                    'text' => 'Registros',
                    'route'  => 'movimientos.listado',
                    'icon'    => 'fas fa-dollar-sign',
                    'shift'   => 'ml-3',
                ],

                [
                    'text' => 'Nuevo Ingreso',
                    'url'  => '#',
                    'label'       => '+',
                    'shift'   => 'ml-3',
                    'label_color' => 'success',
                    'icon'    => 'fas fa-dollar-sign',
                    'submenu'    => [
                        [
                            'text' => 'Registro x Prov',
                            'route'  => 'movimientos.crear',
                            'icon'    => 'fas fa-plus-circle',
                            'shift'   => 'ml-4',
                        ],
                        [
                            'text' => 'Registro x Client',
                            'route'  => 'movimientos.cliente',
                            'icon'    => 'fas fa-plus-circle',
                            'shift'   => 'ml-4',
                        ],
                    ],
                ],
                [
                    'text' => 'Registro de Gastos',
                    'label'       => '-',
                    'label_color' => 'danger',
                    // 'active' => ['admin/movimientos/provision_fija'],
                    'icon'    => 'fas fa-minus-circle',
                    'shift'   => 'ml-3',
                    'route'  => 'movimientos.proveedor',
                ],
            ],
        ],
        [
            'text' => 'PROVISIONES',
            'url'  => '#',
            'label'       => 'new',
            'label_color' => 'secondary',
            'icon'    => 'fas fa-dollar-sign',
            'submenu' => [
                [
                    'text' => 'PROV. FIJA',
                    'route'  => 'provision.fija',
                    'icon'    => 'fas fa-plus-circle',
                    'shift'   => 'ml-3',
                ],
                [
                    'text' => 'PROV. VARIABLE',
                    'route'  => 'provision.variable',
                    'icon'    => 'fas fa-plus-circle',
                    'shift'   => 'ml-3',
                ],
                // [
                //     'text' => 'Provision x Socio',
                //     'route'  => 'provision.socio',
                //     'icon'    => 'fas fa-plus-circle',
                //     'shift'   => 'ml-3',
                // ],
            ],
        ],
        // [
        //     'text'    => 'Balance',
        //     'icon'    => 'fas fa-calculator',
        //     'submenu' => [
        //         [
        //             'text' => 'Balance Total',
        //             'route'  => 'balance.index',
        //             'shift'   => 'ml-3',
        //             'icon'    => 'fas fa-calculator',
        //         ],
        //         [
        //             'text' => 'Balance de Ingresos',
        //             'route'  => 'balance.ingresos',
        //             'shift'   => 'ml-3',
        //             'icon'    => 'fas fa-calculator',
        //         ],
        //         [
        //             'text' => 'Balance de Gastos',
        //             'route'  => 'balance.gastos',
        //             'shift'   => 'ml-3',
        //             'icon'    => 'fas fa-calculator',
        //         ],
        //     ],
        // ],

        ['header' => 'CONGIGURACIÓN DE ENTORNO'],
        [
            'text'    => ' CATEGORÍAS',
            'icon'    => 'fas fa-paperclip',
            'submenu' => [
                [
                    'text' => 'LISTADO',
                    'url'  => 'admin/categories',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-cog',
                ],
                [
                    'text' => 'NUEVA CAT.',
                    'url'  => 'admin/categories/create',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-cog',

                ],
            ],
        ],
        [
            'text'    => ' CUENTAS',
            'icon'    => 'fas fa-wallet',
            'submenu' => [
                [
                    'text' => 'LISTADO',
                    'url'  => 'admin/cuentas',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-check',
                ],
                [
                    'text' => 'NUEVA CUENTA',
                    'url'  => 'admin/account/create',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-plus',
                ],
            ],
        ],

        ['header' => 'DATOS GENERALES'],
        [
            'text'        => 'ESTUDIANTES',
            'route'         => 'students.index',
            'icon'        => 'fa fa-handshake',
            'active' => ['admin/students', 'admin/students*'],
            'label'       => 'Nuevo',
            'label_color' => 'info',
        ],
        [
            'text'        => 'Reportes',
            'url'         => '#',
            'icon'        => 'fa fa-file',
            // 'active' => ['admin/socios', 'admin/socios*'],
            'label'       => 'pdf, excel',
            'label_color' => 'primary',
            'submenu' => [
                [
                    'text' => 'Reporte Detallados',
                    // 'route'  => 'reportes.detalles',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
                    // [
                    //     'text' => 'Reporte General',
                    //     'route'  => 'reportes.general',
                    //     'shift'   => 'ml-3',
                    //     'icon'    => 'fas fa-file',
                    // ],
                [
                    'text' => 'Reporte Multiples',
                    // 'route'  => 'reportes.multiples',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
                [
                    'text' => 'Rep. Conceptos',
                    // 'route'  => 'reportes.conceptos.view',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
                [
                    'text' => 'Rep. Concepto Deuda',
                    // 'route'  => 'reportes.conceptos_deuda.view',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-download',
                ],
                [
                    'text' => 'Reporte Ingreso x Día',
                    // 'route'  => 'reportes.detalles',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
                [
                    'text' => 'Reporte Gasto x Día',
                    // 'route'  => 'reportes.detalles',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
            ],
        ],

        [
            'text'        => 'RECIBOS',
            'url'         => '#',
            'icon'        => 'fa fa-file',
            'active' => ['reporte', 'reporte/cc5/*'],
            'label'       => 'nuevo',
            'label_color' => 'primary',
            'submenu' => [
                [
                    'text' => 'Ingresos PDF',
                    // 'route'  => 'reporte.ingreso.gasto',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
                [
                    'text' => 'Reporte PDF detalle',
                    // 'route'  => 'reporte.ingreso.pdf-detallado',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
                [
                    'text' => 'Buscar Recibo',
                    'route'  => 'reporte.ingreso.buscar-recibo',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-file',
                ],
                // [
                //     'text' => 'Recibos Masivos',
                //     'route'  => 'reporte.ingreso.recibos-masivos',
                //     'shift'   => 'ml-3',
                //     'icon'    => 'fas fa-book',
                // ],
            ],
        ],
        // [
        //     'text'        => 'Transferencia',
        //     'route'         => 'transferencias.create',
        //     'icon'        => 'fas fa-exchange-alt',
        //     'label_color' => 'success',
        // ],
        // [
        //     'text'        => 'Movimientos futuros',
        //     'route'         => 'movimientosfuturos.index',
        //     'icon'        => 'fas fa-fast-forward',
        //     'label_color' => 'success',
        // ],
        // [
        //     'text'        => 'Bitacora',
        //     'route'         => 'bitacora.index',
        //     'icon'        => 'fas fa-history',
        //     'label_color' => 'success',
        // ],
        // [
        //     'text'        => 'Cierre',
        //     'route'         => 'closes.index',
        //     'icon'        => 'fas fa-caret-down',
        //     'label_color' => 'success',
        // ],
        ['header' => 'CONFIGURACIÓN DEL SISTEMA'],
        [
            'text'    => 'SISTEMA',
            'icon'    => 'fas fa-cogs',
            'submenu' => [
                [
                    'text' => 'Clientes - proveedores',
                    'url'  => 'admin/cliente-proveedor',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-users-cog',
                ],
                [
                    'text' => 'Import - Socios',
                    // 'route'  => 'import.socios',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-users-cog',
                ],
                [
                    'text' => 'Import - Stands',
                    // 'route'  => 'import.stands',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-users-cog',
                ],
                [
                    'text' => 'Import - Provisions',
                    // 'route'  => 'import.details',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-users-cog',
                ],
                [
                    'text' => 'Configuracion Cliente',
                    'route'  => 'company.settings',
                    'shift'   => 'ml-3',
                    'icon'    => 'fas fa-users-cog',
                ],
            ],
        ],
        [
            'text'    => 'Usuarios',
            'icon'    => 'fas fa-users',
            'submenu' => [
                [
                    'text' => 'Listado de usuarios',
                    'route'  => 'usuarios',
                    'icon'    => 'fas fa-users-cog',
                    'shift'   => 'ml-3',
                ],
                [
                    'text' => 'Permiso/Rol',
                    'route'  => 'asignar.permiso',
                    'icon'    => 'fas fa-key',
                    'label'       => 'Config...',
                    'shift'   => 'ml-3',
                    'label_color' => 'info',
                    'can' => ['usuarios.delete'],
                ],
            ],
        ],
        // [
        //     'text'    => 'Stands',
        //     'icon'    => 'fas fa-users',
        //     'route'  => 'stands.index',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/select2/js/select2.full.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2/css/select2.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];
