@include('starter.helpers.header')
    <div class="page-content bg-white">
        <!-- inner page banner -->
        <div class="page-banner ovbl-dark" style="background-image:url(assets/images/banner/banner2.jpg);">
            <div class="container">
                <div class="page-banner-entry">
                    <h1 class="text-white">Download Our App</h1>
				 </div>
            </div>
        </div>
		<!-- Breadcrumb row -->
		<div class="breadcrumb-row">
			<div class="container">
				<ul class="list-inline">
					<li><a href="#">Home</a></li>
					<li>Download</li>
				</ul>
			</div>
		</div>
		<!-- Breadcrumb row END -->
        <!-- inner page banner END -->
		<div class="content-block">
            <!-- About Us -->
			<div class="section-area section-sp1">
                <div class="container">
					 <div class="row d-flex flex-row-reverse">						
					
						<div class="col-lg-9 col-md-8 col-sm-12">
							<div class="courses-post">
								<div class="ttr-post-media media-effect">
									<a href="#"><img src="assets/starter/images/icon/phone_mockup.png" alt=""></a>
								</div>
								<div class="ttr-post-info">
									<div class="ttr-post-title ">
										<h2 class="post-title">Our App</h2>
									</div>
									<div class="ttr-post-text">
										<p>The App acts as a mobile portal for parents to login and view their student's school Progress.It is comprised of several functionalities such as Viewing student attendance,marks,assignments,upcoming events, provide suggestions and many other features.</p>
									</div>
								</div>
							</div>
							<div class="courese-overview" id="overview">
								<h4>Features</h4>
								<div class="row">
									<div class="col-md-12 col-lg-4">
										<ul class="course-features">
											<li><i class="fa fa-user"></i> <span class="label">Profile Section</span></li>
											<li><i class="fa fa-book"></i> <span class="label">Student Assignments</span></li>
											<li><i class="fa fa-calendar-check-o"></i> <span class="label">Student Attendance</span></li>
											<li><i class="fa fa-tasks"></i> <span class="label">Student Exams</span></li>
											<li><i class="fa fa-commenting-o"></i> <span class="label">Recommendation Platform</span</li>
										</ul>
									</div>
									<div class="col-md-12 col-lg-8">
										<h5 class="m-b5">Why Use Shule Yetu App</h5>
										<ul class="list-checked primary">
											<li>We ensure you with a proper management system for your institution.</li>
                                            <li>Our system aims at bridging the gap that exists between parents and your institution.</li>
                                            <li>Our system is designed to be customizable depending on your institution’s needs</li>
										</ul>

                                        <div class="course-buy-now text-center">
                                            <a href="{{ url('apps/shule_yetu.apk') }}" class="btn radius-xl text-uppercase" download>
                                                <i class="fa fa-download" aria-hidden="true"></i> Download App
                                            </a>
                                            <br>*Currently in Android
                                        </div>
                                        
									</div>
								</div>
							</div>							
						</div>
						
					</div>
				</div>
            </div>
        </div>
		<!-- contact area END -->
		
    </div>
@include('starter.helpers.footer') 