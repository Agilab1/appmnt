     <?php
      $name = session('staff_name');
      $initials = '';

      if ($name) {
        $parts = explode(' ', trim($name));
        $initials = strtoupper(
          substr($parts[0], 0, 1) .
            (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
        );
      }
      ?>

     <!--begin::Header-->
     <nav class="app-header navbar navbar-expand bg-body">
       <!--begin::Container-->
       <div class="container-fluid">
         <!--begin::Start Navbar Links-->
         <ul class="navbar-nav">
           <li class="nav-item">
             <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
               <i class="bi bi-list"></i>
             </a>
           </li>
           <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
           <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
         </ul>
         <!--end::Start Navbar Links-->
         <!--begin::End Navbar Links-->
         <ul class="navbar-nav ms-auto">
           <!--begin::Navbar Search-->
           <li class="nav-item">
             <a class="nav-link" data-widget="navbar-search" href="#" role="button">
               <i class="bi bi-search"></i>
             </a>
           </li>
           <!--end::Navbar Search-->
           <!--begin::Messages Dropdown Menu-->
           <li class="nav-item dropdown">
             <a class="nav-link" data-bs-toggle="dropdown" href="#">
               <i class="bi bi-chat-text"></i>
               <span class="navbar-badge badge text-bg-danger">3</span>
             </a>
             <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
               <a href="#" class="dropdown-item">
                 <!--begin::Message-->
                 <div class="d-flex">
                   <div class="flex-shrink-0">
                     <img
                       src="public/assets/dist/assets/img/user1-128x128.jpg"
                       alt="User Avatar"
                       class="img-size-50 rounded-circle me-3" />
                   </div>
                   <div class="flex-grow-1">
                     <h3 class="dropdown-item-title">
                       Brad Diesel
                       <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                     </h3>
                     <p class="fs-7">Call me whenever you can...</p>
                     <p class="fs-7 text-secondary">
                       <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                     </p>
                   </div>
                 </div>
                 <!--end::Message-->
               </a>
               <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item">
                 <!--begin::Message-->
                 <div class="d-flex">
                   <div class="flex-shrink-0">
                     <img
                       src="public/assets/dist/assets/img/user8-128x128.jpg"
                       alt="User Avatar"
                       class="img-size-50 rounded-circle me-3" />
                   </div>
                   <div class="flex-grow-1">
                     <h3 class="dropdown-item-title">
                       John Pierce
                       <span class="float-end fs-7 text-secondary">
                         <i class="bi bi-star-fill"></i>
                       </span>
                     </h3>
                     <p class="fs-7">I got your message bro</p>
                     <p class="fs-7 text-secondary">
                       <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                     </p>
                   </div>
                 </div>
                 <!--end::Message-->
               </a>
               <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item">
                 <!--begin::Message-->
                 <div class="d-flex">
                   <div class="flex-shrink-0">
                     <img
                       src="public/assets/dist/assets/img/user3-128x128.jpg"
                       alt="User Avatar"
                       class="img-size-50 rounded-circle me-3" />
                   </div>
                   <div class="flex-grow-1">
                     <h3 class="dropdown-item-title">
                       Nora Silvester
                       <span class="float-end fs-7 text-warning">
                         <i class="bi bi-star-fill"></i>
                       </span>
                     </h3>
                     <p class="fs-7">The subject goes here</p>
                     <p class="fs-7 text-secondary">
                       <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                     </p>
                   </div>
                 </div>
                 <!--end::Message-->
               </a>
               <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
             </div>
           </li>
           <!--end::Messages Dropdown Menu-->
           <!--begin::Notifications Dropdown Menu-->
           <li class="nav-item dropdown">
             <a class="nav-link" data-bs-toggle="dropdown" href="#">
               <i class="bi bi-bell-fill"></i>
               <span class="navbar-badge badge text-bg-warning">15</span>
             </a>
             <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
               <span class="dropdown-item dropdown-header">15 Notifications</span>
               <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item">
                 <i class="bi bi-envelope me-2"></i> 4 new messages
                 <span class="float-end text-secondary fs-7">3 mins</span>
               </a>
               <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item">
                 <i class="bi bi-people-fill me-2"></i> 8 friend requests
                 <span class="float-end text-secondary fs-7">12 hours</span>
               </a>
               <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item">
                 <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                 <span class="float-end text-secondary fs-7">2 days</span>
               </a>
               <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
             </div>
           </li>
           <!--end::Notifications Dropdown Menu-->
           <!--begin::Fullscreen Toggle-->
           <li class="nav-item">
             <a class="nav-link" href="#" data-lte-toggle="fullscreen">
               <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
               <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
             </a>
           </li>
           <!--end::Fullscreen Toggle-->
           <!--begin::User Menu Dropdown-->
           <li class="nav-item dropdown user-menu">
             <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">

               <!-- INITIALS AVATAR -->
               <div
                 class="rounded-circle d-flex align-items-center justify-content-center"
                 style="
      width: 35px;
      height: 35px;
      background-color: #6e7887;
      color: #fff;
      font-weight: 600;
      font-size: 14px;
      flex-shrink: 0;
    ">
                 <?= esc($initials) ?>
               </div>

               <!-- USER NAME -->
               <span class="d-none d-md-inline fw-semibold text-dark">
                 <?= esc(session('staff_name')) ?>
               </span>

             </a>

             <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
               <!--begin::User Image-->
               <li class="user-header text-bg-primary text-center">

                 <!-- INITIALS AVATAR -->
                 <div
                   class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                   style=" width: 80px; height: 80px; background-color: #ffffff; color: #0d6efd; font-size: 28px; font-weight: 700;">

                   <?= esc($initials) ?>
                 </div>

                 <!-- USER NAME -->
                 <p class="mb-0 fw-semibold">
                   <?= esc(session('staff_name')) ?>
                 </p>

               </li>

               <!--end::User Image-->
               <!--begin::Menu Body-->
               <li class="user-body">
                 <!--begin::Row-->

                 <!--end::Row-->
               </li>
               <!--end::Menu Body-->
               <!--begin::Menu Footer-->
               <li class="user-footer">
                 <a href="#" class="btn btn-default btn-flat">Profile</a>
                 <a href="staff/login" class="btn btn-default btn-flat float-end">Sign out</a>
               </li>
               <!--end::Menu Footer-->
             </ul>
           </li>
           <!--end::User Menu Dropdown-->
         </ul>
         <!--end::End Navbar Links-->
       </div>
       <!--end::Container-->
     </nav>
     <!--end::Header-->