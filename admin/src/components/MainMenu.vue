<script setup>
defineProps({
  msg: {
   
  }
})
</script>

<template>
  
 <nav>
    <div class="main-menu menu-fixed menu-light menu-expanded pt-3 ">
      <div class="navbar-header expanded">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto">
            
          </li>
          
        </ul>
      </div>
      <div class="shadow-bottom d-block"></div>
      <ul class="ps-container main-menu-content scroll-area ps ps--active-y">
        <ul class="navigation navigation-main">
          <li class="nav-item has-sub open sidebar-group-active">
            <a href="#" target="_self" class="d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
              <span class="menu-title text-truncate">Dashboard</span>
              <span class="badge mr-1 ml-auto badge-light-warning badge-pill"> 2 </span>
            </a>
            <ul class="menu-content collapse show" style="" id="__BVID__130">
              <li class="nav-item active">
                <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/dashboard/ecommerce" class="d-flex align-items-center router-link-exact-active router-link-active" aria-current="page">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                    <circle cx="12" cy="12" r="10"></circle>
                  </svg>
                  <span class="menu-title text-truncate">Reports</span>
                  <!---->
                </a>
              </li>
              
            </ul>
          </li>
        
       
          <li class="nav-item">
            <RouterLink to="/about" class="d-flex align-items-center">
            
              <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
              <span class="menu-title text-truncate">Email</span>
              <!---->
            
            </RouterLink>
          </li>
          <li class="nav-item">
            <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/apps/chat" class="d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
              <span class="menu-title text-truncate">Chat</span>
              <!---->
            </a>
          </li>
          <li class="nav-item">
            <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/apps/todo" class="d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
              </svg>
              <span class="menu-title text-truncate">Todo</span>
              <!---->
            </a>
          </li>
          <li class="nav-item">
            <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/apps/calendar" class="d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
              <span class="menu-title text-truncate">Calendar</span>
              <!---->
            </a>
          </li>
          <li class="nav-item has-sub">
            <a href="#" target="_self" class="d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              <span class="menu-title text-truncate">Invoice</span>
              <!---->
            </a>
            <ul class="menu-content collapse" style="display: none;" id="__BVID__153">
              <li class="nav-item">
                <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/apps/invoice/list" class="d-flex align-items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                    <circle cx="12" cy="12" r="10"></circle>
                  </svg>
                  <span class="menu-title text-truncate">List</span>
                  <!---->
                </a>
              </li>
              <li class="nav-item">
                <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/apps/invoice/preview/4987" class="d-flex align-items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                    <circle cx="12" cy="12" r="10"></circle>
                  </svg>
                  <span class="menu-title text-truncate">Preview</span>
                  <!---->
                </a>
              </li>
              <li class="nav-item">
                <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/apps/invoice/edit/4987" class="d-flex align-items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                    <circle cx="12" cy="12" r="10"></circle>
                  </svg>
                  <span class="menu-title text-truncate">Edit</span>
                  <!---->
                </a>
              </li>
              <li class="nav-item">
                <a href="/demo/vuexy-vuejs-admin-dashboard-template/demo-4/apps/invoice/add" class="d-flex align-items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                    <circle cx="12" cy="12" r="10"></circle>
                  </svg>
                  <span class="menu-title text-truncate">Add</span>
                  <!---->
                </a>
              </li>
            </ul>
          </li>






        </ul>
        <div class="ps__rail-x" style="left: 0px; bottom: -1305px;">
          <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 1305px; height: 384px; right: 0px;">
          <div class="ps__thumb-y" tabindex="0" style="top: 324px; height: 60px;"></div>
        </div>
      </ul>
    </div>
  </nav>


</template>

<style scoped>

    .menu-expanded.main-menu {
        width: 260px;
        border-right:var(--light-border);
    }

    .main-menu ul {
        list-style: none;
        margin:0; 
        padding:0; 

    }

    .main-menu li > a {
        margin: 0 15px;
        padding: 10px 15px 10px 15px;
    }
    li a span{

        font-weight: 700;
    }
    .main-menu li>a svg {
        margin-right: 1.1rem;
    }
    .navigation-header {
        color: #676d7d;
    }
    li ul .active {
        background: linear-gradient(118deg,#7367f0,rgba(115,103,240,.7));
        box-shadow: 0 0 10px 1px rgb(115 103 240 / 70%);
        border-radius: 4px;
            margin: 0 15px;
            color:white
    }
    .nav-item.open>a {
        background-color: #161d31!important;
        color:white;
        border-radius: 6px;
    }

    li>ul li:first-of-type {
        margin-top: 7px!important;
    }
    .badge.badge-light-warning {
        background-color: rgba(255,159,67,.12);
    }

    .badge.badge-light-warning {
        color: #ff9f43!important;
    }

    .main-menu {
        font-size: 1.1rem;
        
    }


    .main-menu li.has-sub>a:after {
        content: "";
        height: 1.1rem;
        width: 1.1rem;
        display: inline-block;
        position: absolute;
        top: 14px;
        -webkit-transition: all .2s ease-out;
        transition: all .2s ease-out;
    }

     a:after {
        color: #d0d2d6;
    }

     .main-menu  li.has-sub>a:after {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236e6b7b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpath d='M9 18l6-6-6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 50%;
            background-size: 1.1rem;
            -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
    }

    .main-menu  li.has-sub>a:after {
        right: 20px;
    }

    /* .main-menu  li.has-sub.open:not(.menu-item-closing)>a:after {
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    } */

</style>
