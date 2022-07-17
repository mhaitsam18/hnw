<!--content body start-->
<div id="content_wrapper">
	<!--page title start-->
	<div class="page_title" data-stellar-background-ratio="0" data-stellar-vertical-offset="0" style="background-image:url(<?= base_url('assets/') ?>images/bg/page_title_bg.jpg);">
		<ul>
			<li><a href="javascript:;">Maintenance </a></li>
		</ul>
	</div>
	<!--page title end-->
	<div class="clearfix"></div>
	<!-- 404 error page bg start -->
	<div class="full_width travelite_coming_soon_wrapper">
		<div class="container">
			<div class="row">
				<div class="main_coming_soon">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<div class="left_image_coming_soon">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="449.254px" height="515.123px" viewBox="0 0 449.254 515.123" enable-background="new 0 0 449.254 515.123" xml:space="preserve"><g><g><path fill="#86B817" d="M285.063,115.511c31.879,0,57.724-25.854,57.724-57.748c0-31.893-25.845-57.748-57.724-57.748c-31.881,0-57.727,25.855-57.727,57.748C227.336,89.657,253.182,115.511,285.063,115.511z M429.152,198.623c-51.584-5.308-101.598-61.15-114.184-68.884c-7.725-5.577-16.855-9.229-25.593-10.592c0,0-6.21-1.29-12.627-0.581c-6.143,0.682-15.482,1-29.157,11.197c-0.99,0.616-7.975,6.701-10.178,9.352c-17.021,17.649-64.183,69.839-87.659,128.879c-3.318,8.387-1.213,17.498,4.566,23.643l-27.57,49.145l-38.838-21.826c-4.636-2.611-10.52-0.98-13.125,3.662L1.278,452.563c-2.603,4.639-0.956,10.551,3.692,13.133l29.401,16.541c-4.681,8.336-3.554,19.117,3.517,26.285c8.526,8.705,22.534,8.813,31.222,0.258c8.075-7.971,8.735-20.625,1.892-29.35l40.139-70.447l28.121-50.129l34.855-60.447c7.537-1.199,14.292-6.225,17.327-13.85c9.935-25.025,25.321-49.138,40.181-69.119l7.001,80.503c0.812,9.455,4.749,17.389,10.588,23.65c-18.054,32.75-66.637,122.309-84.177,152.734c-7.857,13.654-3.187,31.104,10.455,38.973c5.121,2.938,10.79,4.146,16.278,3.752c9.129-0.662,17.768-5.707,22.676-14.225c16.341-28.342,58.963-114.354,77.659-146.686c14.987,24.916,40.522,70.768,72.919,143.986c4.709,10.672,15.147,16.994,26.104,16.994c3.874,0,7.778-0.779,11.54-2.449c14.396-6.371,20.911-23.219,14.524-37.617c-38.202-86.406-66.331-134.373-81.916-159.633c6.16-7.736,9.871-17.352,8.899-28.637l-6.627-76.25c19.198,15.335,44.249,28.254,87.021,32.678c0.796,0.085,1.566,0.124,2.344,0.124c11.329,0,21.07-8.576,22.26-20.104C450.418,210.905,441.471,199.89,429.152,198.623z M53.177,501.791c-4.441,0-8.043-3.602-8.043-8.043c0-4.439,3.602-8.043,8.043-8.043c4.436,0,8.039,3.602,8.039,8.043C61.214,498.191,57.614,501.791,53.177,501.791z"/></g></g></svg>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 right_side_coming_soon">
						<div class="comming_soon_logo">
							<a href="<?= base_url() ?>"><img src="<?= base_url('assets/') ?>svg/Logo.svg" alt="footer logo" /></a>
						</div>
						<div class="coming_soon_heading">
							<h2>Maintenance....!</h2>
							<p>We are under heavy re-condtruction at this time. The page will be online at your service after</p>
						</div>
						<div id="counter_comming_soon"  data-countdown="<?= $soon ?>"></div>
						<div class="full_width newsletter_wrapper_main">
							<?php if (!$this->session->userdata('email')): ?>
								<form action="<?= base_url('auth/registration') ?>" method="get">
									<p>Please sign-up for our newsletter</p>
									<div class="email_newsletter">
										<input type="email" name="email" id="email" placeholder="Your Email Id" class="news_email">
										<input type="submit" value="SEND" id="news_send">
									</div>
									<div class="coming_social_icons">
										<a href="#"><i class="fa fa-facebook"></i></a>
										<a href="#"><i class="fa fa-twitter"></i></a>
										<a href="#"><i class="fa fa-linkedin"></i></a>
										<a href="#"><i class="fa fa-google-plus"></i></a>
									</div>
								</form>
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 404 error page bg end -->
	<!-- counter section End -->
</div>
<!--content body end--> 