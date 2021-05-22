<style>
    .gol_menu_area {
        box-sizing: border-box;
        padding: 0px 21px;
    }
</style>
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand">
        <!--begin::Logo-->
        <a href="index.html" class="brand-logo">
            <img width="150" alt="Logo" src="{{ asset('public') }}/assets/media/logos/pospro.png"/>
        </a>
        <!--end::Logo-->
        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Navigation/Angle-double-left.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                        <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
        </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <!--begin::Menu Container-->
        <div class="gol_menu_area">
            <div class="row">
                <div class="col-md-6">
                    <a href="#" id="show_sub_menu" data-menu_name="products">
                        <div class="text-center main_nemu_icon">
                            <img src="{{ asset('public/assets/media/menu_icon/delivery-box.png') }}" alt=""> 
                        </div>
                        
                        <div class="text-center main_menu_text text-white">
                            <span>Products</span> 
                        </div>
                    </a> 
                </div>

                <div class="col-md-6">
                    <a href="#" id="show_sub_menu" data-menu_name="purchases">
                        <div class="text-center main_nemu_icon">
                            <img class="mt-1" src="{{ asset('public/assets/media/menu_icon/shipping.png') }}" alt=""> 
                        </div>
                        
                        <div class="text-center text-white main_menu_text">
                            <span>Purchases</span>  
                        </div>
                    </a> 
                </div>
            </div>
        </div>

        <div class="all_sub_menu d-none">
            <div id="products" class="menu_list">
                <div class="row">
                    <ul class="list-unstyled sup_menu_area">
                        <li>
                            <a href="{{ route('product.categories.index') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-boxes"></i></p>
                                <p class="text-center menu-text p-0 m-0">Categories</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('product.brands.index') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-box"></i></p>
                                <p class="text-center menu-text p-0 m-0">Brand</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.all.product') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-list"></i></p>
                                <p class="text-center menu-text p-0 m-0">Product List</p>
                            </a>
                        </li>
    
                        <li>
                            <a href="{{ route('products.add.view') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-plus-square"></i></p>
                                <p class="text-center menu-text p-0 m-0">Add Product</p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('products.add.view') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-plus-square"></i></p>
                                <p class="text-center menu-text p-0 m-0">Variants</p>
                            </a>
                        </li>
                        
                        <li>
                            <a href="{{ route('barcode.index') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-barcode"></i></p>
                                <p class="text-center menu-text p-0 m-0">Generate Barcode</p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('product.warranties.index') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-dumbbell"></i></p>
                                <p class="text-center menu-text p-0 m-0">Warranties/Guaranties</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="purchases" class="menu_list">
                <div class="row"> 
                    <ul class="list-unstyled sup_menu_area">
                        <li>
                            <a href="{{ route('purchases.create') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-shopping-basket"></i></p>
                                <p class="text-center menu-text p-0 m-0">Add Purchase</p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('purchases.returns.index') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-clipboard-list"></i></p>
                                <p class="text-center menu-text p-0 m-0">Purchase List</p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('purchases.returns.index') }}">
                                <p class="text-center menu_icon p-0 m-0"><i class="fas fa-exchange-alt"></i></p>
                                <p class="text-center menu-text p-0 m-0">Purchase Return List</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav">
                <li class="menu-item menu-item-active" aria-haspopup="true">
                    <a href="{{ route('dashboard.dashboard') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Layers.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
                                    <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M5,5 L5,15 C5,15.5948613 5.25970314,16.1290656 5.6719139,16.4954176 C5.71978107,16.5379595 5.76682388,16.5788906 5.81365532,16.6178662 C5.82524933,16.6294602 15,7.45470952 15,7.45470952 C15,6.9962515 15,6.17801499 15,5 L5,5 Z M5,3 L15,3 C16.1045695,3 17,3.8954305 17,5 L17,15 C17,17.209139 15.209139,19 13,19 L7,19 C4.790861,19 3,17.209139 3,15 L3,5 C3,3.8954305 3.8954305,3 5,3 Z" fill="#000000" fill-rule="nonzero" transform="translate(10.000000, 11.000000) rotate(-315.000000) translate(-10.000000, -11.000000)" />
                                    <path d="M20,22 C21.6568542,22 23,20.6568542 23,19 C23,17.8954305 22,16.2287638 20,14 C18,16.2287638 17,17.8954305 17,19 C17,20.6568542 18.3431458,22 20,22 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Product</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">

                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link"><span class="menu-text">Categories</span></span>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('product.categories.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text">Categories</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('product.brands.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text">Brand</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                            <a href="{{ route('products.all.product') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text">Product List</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('products.add.view') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text">Add Product</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('product.variants.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text">Variants</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('barcode.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text">Generate Barcode</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('product.warranties.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text">Warranties/Guaranties</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                @if (auth()->user()->role == 1 || auth()->user()->role == 2)
                    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="svg-icon menu-icon">
                                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                                <i class="fas fa-shopping-cart"></i>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="menu-text">Purchases</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">

                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Purchases</span>
                                    </span>
                                </li>
                                
                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('purchases.create') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Add Purchase</span>
                                    </a>
                                </li>

                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('purchases.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Purchase List</span>
                                    </a>
                                </li>

                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('purchases.returns.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Purchase Return List</span>
                                    </a>
                                </li>

                                <li class="menu-item" aria-haspopup="true">
                            </ul>
                        </div>
                    </li>
                @else
                    @if (auth()->user()->branch->purchase_permission == 1)
                        <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="svg-icon menu-icon">
                                    <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                                    <i class="fas fa-shopping-cart"></i>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-text">Purchases</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu">
                                <i class="menu-arrow"></i>
                                <ul class="menu-subnav">

                                    <li class="menu-item menu-item-parent" aria-haspopup="true">
                                        <span class="menu-link">
                                            <span class="menu-text">Purchases</span>
                                        </span>
                                    </li>
                                    
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('purchases.create') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">Add Purchase</span>
                                        </a>
                                    </li>

                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('purchases.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">Purchase List</span>
                                        </a>
                                    </li>

                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('purchases.returns.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">Purchase Return List</span>
                                        </a>
                                    </li>

                                    <li class="menu-item" aria-haspopup="true">
                                </ul>
                            </div>
                        </li>
                    @endif    
                @endif
         

                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                            <i class="fas fa-shopping-cart"></i>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Sales</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">

                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">Sales</span>
                                </span>
                            </li>
                            
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('sales.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Sale List</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('sales.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Add Sale</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('sales.drafts') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Draft List</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('sales.quotations') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Quotation List</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('sales.challans') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Challan List</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('sales.returns.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Sale Return List</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('sales.shipments') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Shipments</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                            <i class="fas fa-shopping-cart"></i>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Transfer Stock</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            @if (auth()->user()->role == 1 || auth()->user()->role == 2)
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Transfer Stock</span>
                                    </span>
                                </li>
                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('transfer.stock.to.branch.create') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Add Transfer <small class="ml-1">(To Branch)</small> </span>
                                    </a>
                                </li>

                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('transfer.stock.to.branch.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Transfer List <small class="ml-1">(To Branch)</small></span>
                                    </a>
                                </li>

                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('transfer.stocks.to.branch.receive.stock.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Receive Stocks</span>
                                    </a>
                                </li>
                            @else   
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Transfer Stock (To Warehouse)</span>
                                    </span>
                                </li> 
                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('transfer.stock.to.warehouse.create') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Add Transfer <small class="ml-1"> (To Warehouse)</small> </span>
                                    </a>
                                </li>

                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('transfer.stock.to.warehouse.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Transfer List <small class="ml-1"> (To Warehouse)</small></span>
                                    </a>
                                </li> 

                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ route('transfer.stocks.to.warehouse.receive.stock.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Receive Stocks</span>
                                    </a>
                                </li>
                            @endif
                            <li class="menu-item" aria-haspopup="true">
                        </ul>
                    </div>
                </li>


                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                            <i class="fas fa-address-book"></i>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Expanses</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">

                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">Expanses</span>
                                </span>
                            </li>
                            
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('expanses.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Expanse List</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('expanses.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Add Expanse</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('expanses.categories.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Expanse Category</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                        </ul>
                    </div>
                </li>


                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                            <i class="fas fa-address-book"></i>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Contacts</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">

                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">Contacts</span>
                                </span>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('contacts.supplier.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Supplier</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('contacts.customer.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Customer</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('contacts.customers.groups.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Customer Group</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Bucket.svg-->
                            <i class="fas fa-address-book"></i>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Accounting</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">

                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">Accounting</span>
                                </span>
                            </li>
                            
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('accounting.banks.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Banks</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('accounting.types.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Account Type</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('accounting.accounts.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Accounts</span>
                                </a>
                            </li>
                            
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('accounting.balance.sheet') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Balance Sheet</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('accounting.trial.balance') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Trial Balance</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('accounting.cash.flow') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Cash Flow</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                        </ul>
                    </div>
                </li>
            
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Home/Mirror.svg-->
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M13,17.0484323 L13,18 L14,18 C15.1045695,18 16,18.8954305 16,20 L8,20 C8,18.8954305 8.8954305,18 10,18 L11,18 L11,17.0482312 C6.89844817,16.5925472 3.58685702,13.3691811 3.07555009,9.22038742 C3.00799634,8.67224972 3.3975866,8.17313318 3.94572429,8.10557943 C4.49386199,8.03802567 4.99297853,8.42761593 5.06053229,8.97575363 C5.4896663,12.4577884 8.46049164,15.1035129 12.0008191,15.1035129 C15.577644,15.1035129 18.5681939,12.4043008 18.9524872,8.87772126 C19.0123158,8.32868667 19.505897,7.93210686 20.0549316,7.99193546 C20.6039661,8.05176407 21.000546,8.54534521 20.9407173,9.09437981 C20.4824216,13.3000638 17.1471597,16.5885839 13,17.0484323 Z" fill="#000000" fill-rule="nonzero" />
                                    <path d="M12,14 C8.6862915,14 6,11.3137085 6,8 C6,4.6862915 8.6862915,2 12,2 C15.3137085,2 18,4.6862915 18,8 C18,11.3137085 15.3137085,14 12,14 Z M8.81595773,7.80077353 C8.79067542,7.43921955 8.47708263,7.16661749 8.11552864,7.19189981 C7.75397465,7.21718213 7.4813726,7.53077492 7.50665492,7.89232891 C7.62279197,9.55316612 8.39667037,10.8635466 9.79502238,11.7671393 C10.099435,11.9638458 10.5056723,11.8765328 10.7023788,11.5721203 C10.8990854,11.2677077 10.8117724,10.8614704 10.5073598,10.6647638 C9.4559885,9.98538454 8.90327706,9.04949813 8.81595773,7.80077353 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg> --}}
                            <svg id="Capa_1" enable-background="new 0 0 512 512" height="512" 
                            viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
                            <g><path d="m170.834 304.223c8.917 0 17.542 3.251 24.287 9.154 4.429 3.877 10.715 4.804 16.076 2.373 5.36-2.432 8.803-7.774 8.803-13.66v-113.365c0-7.145-5.04-13.3-12.047-14.706-24.549-4.93-50.287-4.887-74.744.097-6.986 1.424-12.005 7.568-12.005 14.698v25.518c-1.667.654-3.32 1.34-4.959 2.059l-18.049-18.049c-5.043-5.043-12.94-5.838-18.886-1.901-10.495 6.946-20.344 14.996-29.277 23.928-8.943 8.944-16.994 18.795-23.928 29.278-3.933 5.947-3.137 13.84 1.904 18.882l18.048 18.048c-.719 1.641-1.406 3.294-2.06 4.962h-25.517c-7.13 0-13.275 5.02-14.698 12.006-2.509 12.318-3.782 24.977-3.782 37.623s1.273 25.304 3.782 37.623c1.423 6.986 7.568 12.006 14.698 12.006h25.517c.654 1.668 1.341 3.323 2.059 4.962l-18.044 18.045c-5.041 5.041-5.838 12.935-1.905 18.881 6.935 10.485 14.986 20.336 23.931 29.281 8.941 8.94 18.791 16.99 29.277 23.927 5.947 3.934 13.841 3.139 18.883-1.903l18.045-18.045c1.64.72 3.294 1.406 4.962 2.06v25.514c0 7.131 5.02 13.275 12.007 14.698 12.33 2.511 24.988 3.784 37.622 3.784h.002c12.463-.002 37.119 0 37.119 0s12.045-11.251 12.045-18.396v-113.36c0-5.886-3.443-11.228-8.803-13.659-5.361-2.433-11.648-1.505-16.076 2.373-6.744 5.902-15.369 9.153-24.287 9.153-20.372 0-36.945-16.573-36.945-36.944 0-20.372 16.573-36.945 36.945-36.945z" fill="#8dc2eb"/><path d="m211.197 366.586c-5.361-2.433-11.648-1.505-16.076 2.373-6.744 5.902-15.369 9.153-24.287 9.153-20.372 0-36.945-16.573-36.945-36.944h-133.889c0 12.646 1.273 25.304 3.782 37.623 1.423 6.986 7.568 12.006 14.698 12.006h25.517c.654 1.668 1.341 3.323 2.059 4.962l-18.044 18.045c-5.041 5.041-5.838 12.935-1.905 18.881 6.935 10.485 14.986 20.336 23.931 29.281 8.941 8.94 18.791 16.99 29.277 23.927 5.947 3.934 13.841 3.139 18.883-1.903l18.045-18.045c1.64.72 3.294 1.406 4.962 2.06v25.514c0 7.131 5.02 13.275 12.007 14.698 12.33 2.511 24.988 3.784 37.622 3.784h.002c12.463-.002 37.119 0 37.119 0s12.045-11.251 12.045-18.396v-113.36c0-5.886-3.443-11.228-8.803-13.659z" fill="#5e9ff6"/>
                                <path d="m497 0h-292c-8.284 0-15 6.716-15 15v482c0 8.284 6.716 15 15 15h292c8.284 0 15-6.716 15-15v-482c0-8.284-6.716-15-15-15z" fill="#00dd80"/>
                                <path d="m190 256v241c0 8.284 6.716 15 15 15h292c8.284 0 15-6.716 15-15v-241z" fill="#00aa95"/>
                                <path d="m305 439c-8.284 0-15-6.716-15-15v-336c0-8.284 6.716-15 15-15s15 6.716 15 15v336c0 8.284-6.716 15-15 15z" fill="#00aa95"/><path d="m290 256v168c0 8.284 6.716 15 15 15s15-6.716 15-15v-168z" fill="#007579"/><path d="m401 439c-8.284 0-15-6.716-15-15v-336c0-8.284 6.716-15 15-15s15 6.716 15 15v336c0 8.284-6.716 15-15 15z" fill="#00aa95"/>
                                <path d="m386 256v168c0 8.284 6.716 15 15 15s15-6.716 15-15v-168z" fill="#007579"/>
                                <circle cx="305" cy="174" fill="#ffe470" r="47"/>
                                <circle cx="401" cy="338" fill="#fabe2c" r="47"/></g></svg>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Settings</span>
                        <i class="menu-arrow"></i>
                    </a>
                    
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">Settings</span>
                                </span>
                            </li>
                            <li class="menu-item" aria-haspopup="true">
                            <a href="{{ route('settings.branches.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Branches</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('settings.warehouses.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Warehouses</span>
                                </a>
                            </li>
                            <li class="menu-item" aria-haspopup="true">
                            <a href="{{ route('settings.units.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Unites</span>
                                </a>
                            </li>
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{ route('settings.taxes.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Taxes</span>
                                </a>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                            <a href="{{ route('settings.general.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Setup</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>