<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @if(Auth::user()->admin == 1)
                <li class="menu-title" key="t-menu">Informacion</li>

                <li>
                    <a href="/admin" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Inicio</span>
                    </a>
                </li>

                <li>
                    <a href="https://so-you-start.ru/update/" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Actualizaciones</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/users" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Usuarios</span>
                    </a>
                </li>

                <li class="menu-title" key="t-menu">Billetera</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-store"></i>
                        <span key="t-ecommerce">Depositos</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/admin/deps/1" key="t-product-detail">Exitosos <span class="badge rounded-pill bg-success float-end">{{\App\Payment::where('status', 1)->count()}}</span></a></li>
                        <li><a href="/admin/deps/0" key="t-products">Pendientes <span class="badge rounded-pill bg-warning float-end">{{\App\Payment::where('status', 0)->count()}}</span></a></li>
                    </ul>
                </li>

                 <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-store"></i>
                        <span key="t-ecommerce">Retiros</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/admin/withdraws/0" key="t-products">Pendientes <span class="badge rounded-pill bg-warning float-end">{{\App\Withdraw::where('status', 0)->count()}}</span></a></li>
                        <li><a href="/admin/withdraws/1" key="t-product-detail">Exitosos <span class="badge rounded-pill bg-success float-end">{{\App\Withdraw::where('status', 1)->count()}}</span></a></li>
                        <li><a href="/admin/withdraws/2" key="t-orders">Rechazados <span class="badge rounded-pill bg-danger float-end">{{\App\Withdraw::where('status', 2)->count()}}</span></a></li>
                        <!-- <li><a href="/admin/withdraws/3" key="t-customers">En procesamiento en la pasarela <span class="badge rounded-pill bg-secondary float-end">{{\App\Withdraw::where('status', 3)->count()}}</span></a></li> -->
                    </ul>
                </li>
                @endif
                <li class="menu-title" key="t-menu">Promocodigos</li>

                
                <li>
                    <a href="/admin/promo" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Monetarios</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/dep_promo" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">De deposito</span>
                    </a>
                </li>

                
                @if(Auth::user()->admin == 1)
                <li class="menu-title" key="t-menu">Configuracion</li>

                <li>
                    <a href="/admin/settings" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Configuracion del sitio</span>
                    </a>
                </li>



                <li>
                    <a href="/admin/systems_deposit" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Sistemas de deposito</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/systems_withdraw" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Sistemas de retiro</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/anti" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Anti-perdidas</span>
                    </a>
                </li>


                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
