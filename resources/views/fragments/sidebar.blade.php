<!DOCTYPE html>
<html lang="en">
<header class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <div class="brand d-flex justify-content-between align-items-center" id="kt_brand">
        <a href="/" class="d-flex">
            <img alt="Logo" src="{{ asset('assets/images/logo-white.png') }}" />
        </a>
    </div>
    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="aside-menu mb-1" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
            <ul class="menu-nav">
                <li class="menu-item menu-item-active" aria-haspopup="true">
                    <a href="/dashboard" class="menu-link" {{ $key == 'dashboard' ? 'menu-active' : '' }}>
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
                                    <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-section">
                    <h4 class="menu-text">Manager</h4>
                </li>
                <li class="menu-item d-flex flex-column justify-content-center">
                    <a data-toggle="collapse" href="#menu-item-collapse-1"
                       {{ $key == 'film' ? 'menu-active' : '' }}
                       aria-expanded="{{ $key == 'film' ? 'true' : 'false' }}"
                       class="menu-link menu-toggle align-items-center"
                       aria-expanded="false"
                       aria-controls="menu-item-collapse-1"
                       role="button"
                    >
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z" fill="#000000"></path>
                                    <rect fill="#000000" opacity="0.3" x="2" y="4" width="5" height="16" rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                        <span class="menu-text" >Film Management</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <ul class="collapse menu-item flex-column justify-content-center" id="menu-item-collapse-1" 
                        @class(['show' => $key == 'film'])>
                        
                        <li class="menu-link menu-item-sub align-items-center" 
                            @class(['menu-active' => $subkey == 'film_all'])>
                            <a href="{{ route('films.index') }}">
                                <span class="menu-text">See All</span>
                            </a>
                        </li>
                        
                        <li class="menu-link menu-item-sub align-items-center" 
                            @class(['menu-active' => $subkey == 'film_new'])>
                            <a href="{{ route('films.create') }}">
                                <span class="menu-text">Create new </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item d-flex flex-column justify-content-center">
                    <a   data-toggle="collapse"
                         href="#menu-item-collapse-2"
                         th:classappend="${key =='category' ? 'menu-active' : ''}"
                         th:attr="aria-expanded=${key == 'category' ? true : false}"
                         class="menu-link menu-toggle align-items-center"
                         role="button"
                         aria-expanded="false"
                         aria-controls="menu-item-collapse-2"
                    >
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
                                    <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>

                        <span class="menu-text">Room Management</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <ul class="collapse menu-item flex-column justify-content-center" id="menu-item-collapse-2" th:classappend="${key =='category' ? 'show' : ''}">
                      
                        <li class="menu-link menu-item-sub align-items-center" 
                            @class(['menu-active' => $subkey == 'room_all'])>
                            <a href="{{ route('rooms.index') }}">
                                <span class="menu-text">See All</span>
                            </a>
                        </li>
                        <li class="menu-link menu-item-sub align-items-center" th:classappend="${subkey =='category_new' ? 'menu-active' : ''}">

                        <a href="/admin/category/add">
                                <span class="menu-text">Create new</span>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="menu-item d-flex flex-column justify-content-center">
                    <a
                        data-toggle="collapse"
                        href="#menu-item-collapse-3"
                        th:classappend="${key =='user' ? 'menu-active' : ''}"
                        th:attr="aria-expanded=${key == 'user' ? true : false}"
                        class="menu-link menu-toggle align-items-center"
                        role="button"
                        aria-expanded="false"
                        aria-controls="menu-item-collapse-3"
                    >
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"></path>
                                    <path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                        </span>
                        <span class="menu-text">Seat Management</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <ul class="collapse menu-item flex-column justify-content-center" id="menu-item-collapse-3" th:classappend="${key =='user' ? 'show' : ''}">
                        <li class="menu-link menu-item-sub align-items-center" th:classappend="${subkey =='user_all' ? 'menu-active' : ''}">
                            <a href="{{ route('seats.index') }}">
                                <span class="menu-text">See All</span>
                            </a>
                        </li>
                        <li class="menu-link menu-item-sub align-items-center" th:classappend="${subkey =='user_new' ? 'menu-active' : ''}">
                            <a href="/admin/user/add">
                                <span class="menu-text">Create new</span>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="menu-item d-flex flex-column justify-content-center">
                    <a data-toggle="collapse" href="#menu-item-collapse-showtime"
                       th:classappend="${key =='showtime' ? 'menu-active' : ''}"
                       th:attr="aria-expanded=${key == 'showtime' ? true : false}"
                       class="menu-link menu-toggle align-items-center"
                       aria-expanded="false"
                       aria-controls="menu-item-collapse-showtime"
                       role="button">
                        <span class="svg-icon menu-icon">
                            <!-- SVG icon -->
                        </span>
                        <span class="menu-text">Showtime Management</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <ul class="collapse menu-item flex-column justify-content-center" id="menu-item-collapse-showtime" 
                        th:classappend="${key =='showtime' ? 'show' : ''}">
                        <li class="menu-link menu-item-sub align-items-center" 
                            th:classappend="${subkey =='showtime_all' ? 'menu-active' : ''}">
                            <a href="{{ route('showtimes.index') }}">
                                <span class="menu-text">See All</span>
                            </a>
                        </li>
                        <li class="menu-link menu-item-sub align-items-center" 
                            th:classappend="${subkey =='showtime_new' ? 'menu-active' : ''}">
                            <a href="/admin/showtime/add">
                                <span class="menu-text">Create New</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="menu-item d-flex flex-column justify-content-center">
                    <a data-toggle="collapse" href="#menu-item-collapse-user"
                       th:classappend="${key =='user' ? 'menu-active' : ''}"
                       th:attr="aria-expanded=${key == 'user' ? true : false}"
                       class="menu-link menu-toggle align-items-center"
                       aria-expanded="false"
                       aria-controls="menu-item-collapse-user"
                       role="button">
                        <span class="svg-icon menu-icon">
                            <!-- SVG icon -->
                        </span>
                        <span class="menu-text">User Management</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <ul class="collapse menu-item flex-column justify-content-center" id="menu-item-collapse-user" 
                        th:classappend="${key =='user' ? 'show' : ''}">
                        <li class="menu-link menu-item-sub align-items-center" 
                            th:classappend="${subkey =='user_all' ? 'menu-active' : ''}">
                            <a href="{{ route('users.index') }}">
                                <span class="menu-text">See All</span>
                            </a>
                        </li>
                        <li class="menu-link menu-item-sub align-items-center" 
                            th:classappend="${subkey =='user_new' ? 'menu-active' : ''}">
                            <a href="{{ route('users.create') }}">
                                <span class="menu-text">Create New</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                
            </ul>
        </div>
    </div>
</header>