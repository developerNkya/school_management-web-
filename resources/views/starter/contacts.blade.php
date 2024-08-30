
@include('starter.helpers.header')
    <div class="page-content bg-white">
        <!-- inner page banner -->
        <div class="page-banner ovbl-dark" style="background-image:url(assets/images/banner/banner3.jpg);">
            <div class="container">
                <div class="page-banner-entry">
                    <h1 class="text-white">Contact Us</h1>
				 </div>
            </div>
        </div>
		<!-- Breadcrumb row -->
		<div class="breadcrumb-row">
			<div class="container">
				<ul class="list-inline">
					<li><a href="/">Home</a></li>
					<li>Contact Us </li>
				</ul>
			</div>
		</div>
		<!-- Breadcrumb row END -->
	
        <!-- inner page banner -->
        <div class="page-banner contact-page section-sp2">
            <div class="container">
                <div class="row">
					<div class="col-lg-5 col-md-5 m-b30">
						<div class="bg-primary text-white contact-info-bx">
							<h2 class="m-b10 title-head">Contact <span>Information</span></h2>
							<p>Feel free to reach us through any of the below links</p>
							<div class="widget widget_getintuch">	
								<ul>
									<li><i class="ti-location-pin"></i>Mbezi luis,Dar-es-salaam,Tanzania</li>
									<li><i class="ti-mobile"></i>+255 620 416 606 (24/7 Support Line)</li>
									<li><i class="ti-email"></i>developernkya@gmail.com</li>
								</ul>
							</div>
							<h5 class="m-t0 m-b20">Follow Us</h5>
							<ul class="list-inline contact-social-bx">
								<li><a href="#" class="btn outline radius-xl"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#" class="btn outline radius-xl"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#" class="btn outline radius-xl"><i class="fa fa-linkedin"></i></a></li>
								<li><a href="#" class="btn outline radius-xl"><i class="fa fa-google-plus"></i></a></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-7 col-md-7">
						@include('helpers.message_handler')
						<form class="contact-bx ajax-form" action="{{ route('contact.message') }}" method="POST">
							@csrf
							<div class="ajax-message"></div>
							<div class="heading-bx left">
								<h2 class="title-head">Get In <span>Touch</span></h2>
								<p>We will be very happy to hear from you! kindly provide us with feedback, or contact us for any inquiry</p>
							</div>
							<div class="row placeani">
								<div class="col-lg-6">
									<div class="form-group">
										<div class="input-group">
											<label>Your Name</label>
											<input name="name" type="text" required class="form-control valid-character">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<div class="input-group"> 
											<label>Your Email Address</label>
											<input name="email" type="email" class="form-control" required>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<div class="input-group">
											<label>Your Phone</label>
											<input name="phone" type="text" required class="form-control int-value">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<div class="input-group">
											<label>From</label>
											<input name="from" type="text" required class="form-control">
										</div>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<div class="input-group">
											<label>Type Message</label>
											<textarea name="message" rows="4" class="form-control" required></textarea>
										</div>
									</div>
								</div>
								<div class="col-lg-12">
									<button name="submit" type="submit" value="Submit" class="btn button-md">Send Message</button>
								</div>
							</div>
						</form>
						
					</div>
				</div>
            </div>
		</div>
        <!-- inner page banner END -->
    </div>
@include('starter.helpers.footer') 