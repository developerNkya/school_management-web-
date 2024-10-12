<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Shule Yetu </title>
    <!-- plugins:css -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="/assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/select.dataTables.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="shortcut icon" href="/assets/images/favicon.png" />
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icon-menu"></span>
            </button>
          </div>
          <div>
            <a class="navbar-brand brand-logo" href="/school_admin/home">
              <img src="/assets/images/logo-icon.png" alt="logo" />
            </a>
          </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
          <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
              <?php
                $value = request()->session()->get('user_name', '');  
                $role = request()->session()->get('role_id', '');  
              ?>
              <h1 class="welcome-text">Welcome, <span class="text-black fw-bold">{{$value}}</span></h1>
              <h3 class="welcome-sub-text">Your personalized dashboard </h3>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">

            @if($role == 2)  
            <li class="nav-item">
              <a class="nav-link" href="{{ url('school_admin/home') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            @endif

            @if($role == 4)  
            <li class="nav-item">
              <a class="nav-link" href="{{ url('school_admin/teacher_home') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            @endif

            <li class="nav-item nav-category">Contents</li>
            @if($role == 2)  
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-account-group"></i>
                <span class="menu-title">Classes</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{ url('school_admin/classes/') }}">Classes</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                <i class="menu-icon mdi mdi-human-male-board"></i>
                <span class="menu-title">Teachers</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="form-elements">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="{{ url('school_admin/teachers/') }}">All Teachers</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="menu-icon mdi mdi-account-tie"></i>
                <span class="menu-title">Students</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/students'}}">All Students</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                <i class="menu-icon mdi mdi-book-open-variant-outline"></i>
                <span class="menu-title">Subjects</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/all-subjects'}}">All Subjects</a></li>
                </ul>
              </div>
            </li>
            @endif

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="menu-icon mdi mdi-list-box-outline"></i>
                <span class="menu-title">Examinations</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                  @if($role == 2)  
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/examinations'}}">Exam List</a></li>
                  @endif
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/marks'}}">Add Marks</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/tabulation'}}">Tabulations</a></li>
                  <li class="nav-item"> <a class="nav-link" href="{{'/results/sent-results'}}">Send Results</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-calendar-check-outline"></i>
                <span class="menu-title">Attendence</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                  @if($role == 2)  
                  <li class="nav-item"> <a class="nav-link" href="{{'/attendence/create-new'}}">Create New </a></li>
                  @endif
                  <li class="nav-item"> <a class="nav-link" href="{{'/attendence/add-attendence'}}">Take Attendence</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#assignment" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-lead-pencil"></i>
                <span class="menu-title">Assignments</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="assignment">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{'/assignment/add-assignment'}}">Add Assignment</a></li>
                </ul>
              </div>
            </li>
            @if($role == 2)  
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#suggestions" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-comment-text-outline"></i>
                <span class="menu-title">Suggestions</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="suggestions">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/view-suggestions'}}">View Suggestions</a></li>
                </ul>
              </div>
            </li>

            {{-- <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#bus_tracker" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-bus"></i>
                <span class="menu-title">Bus Tracker</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="bus_tracker">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{'/bus-management/all-drivers'}}">All Drivers</a></li>
                </ul>
              </div>
            </li> --}}
            @endif
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#events" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-calendar-check-outline"></i>
                <span class="menu-title">Events</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="events">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/organize_events'}}">Organize Events</a></li>
                </ul>
              </div>
            </li>
            @if($role == 2) 
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#promotion" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-plus"></i>
                <span class="menu-title">Promotion</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="promotion">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{'/school_admin/promote-class'}}">Promote</a></li>
                </ul>
              </div>
            </li>
            @endif
            <li class="nav-item">
              <a class="nav-link" href="{{'/passwords/change-password'}}">
                <i class="menu-icon mdi mdi-lock-reset"></i>
                <span class="menu-title">Change Password</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/">
                <i class="menu-icon mdi mdi-logout"></i>
                <span class="menu-title">Logout</span>
              </a>
            </li>
          </ul>
        </nav>
