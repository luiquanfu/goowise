<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">    
<title>Goowise Advisory</title>
<link rel="shortcut icon" href="{{ $app_url }}/website_assets/img/logo.ico" type="image/x-icon">
<link href="{{ $app_url }}/website_assets/css/font-awesome.css" rel="stylesheet">
<link href="{{ $app_url }}/website_assets/css/bootstrap.css" rel="stylesheet">
<!-- slick slider -->
<link rel="stylesheet" type="text/css" href="{{ $app_url }}/website_assets/css/slick.css">
<!-- price picker slider -->
<link rel="stylesheet" type="text/css" href="{{ $app_url }}/website_assets/css/nouislider.css">
<!-- Fancybox slider -->
<link rel="stylesheet" href="{{ $app_url }}/website_assets/css/jquery.fancybox.css" type="text/css" media="screen" /> 
<!-- Theme color -->
<link id="switcher" href="{{ $app_url }}/website_assets/css/theme-color/default-theme.css" rel="stylesheet">
<link href="{{ $app_url }}/website_assets/css/style.css" rel="stylesheet">
</head>

<body>
<!-- Pre Loader -->
<div id="aa-preloader-area">
<div class="pulse"></div>
</div>
<!-- SCROLL TOP BUTTON -->
<a class="scrollToTop" href="#"><i class="fa fa-angle-double-up"></i></a>
<!-- END SCROLL TOP BUTTON -->

<!-- header start -->
<header id="aa-header">  
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-header-area">
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-6">
<div class="aa-header-left">
<div class="aa-telephone-no">
<span class="fa fa-phone"></span>
65-6523-3564
</div>
<div class="aa-email hidden-xs">
<span class="fa fa-envelope-o"></span> enquiry@goowise.com
</div>
</div>              
</div>
<div class="col-md-6 col-sm-6 col-xs-6">
<div class="aa-header-right">
<!-- <a href="register.html" class="aa-register">Register</a>
<a href="signin.html" class="aa-login">Login</a> -->
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</header>

<!-- navigation menu start -->
<section id="aa-menu-area">
<!-- <nav class="navbar navbar-default main-navbar" role="navigation"> -->
<nav class="navbar navbar-default main-navbar navbar-fixed-top" role="navigation">
<div class="container">
<div class="navbar-header">
<!-- FOR MOBILE VIEW COLLAPSED BUTTON -->
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<!-- LOGO -->                                               
<!-- Text based logo -->
<!-- <a class="navbar-brand aa-logo" href="index.html"> Home <span>Property</span></a> -->
<!-- Image based logo -->
<a class="navbar-brand aa-logo-img" href=""><img src="{{ $app_url }}/website_assets/img/logo.jpg" alt="logo"></a>
</div>
<div id="navbar" class="navbar-collapse collapse">
<ul id="top-menu" class="nav navbar-nav navbar-right aa-main-nav">
<li class="active"><a href="#aa-header">HOME</a></li>
<li><a href="#aa-about-us">ABOUT US</a></li>
<li><a href="#aa-agents">THE TEAM</a></li>
<li><a href="#aa-service">SERVICE</a></li>
<li><a href="#aa-latest-blog">ARTICLES</a></li>
<!-- <li class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="properties.html">ABOUT US <span class="caret"></span></a>
<ul class="dropdown-menu" role="menu">
<li><a href="properties.html">PROPERTIES</a></li>
<li><a href="properties-detail.html">PROPERTIES DETAIL</a></li>                                            
</ul>
</li>
<li><a href="gallery.html">GALLERY</a></li>                                         
<li class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="blog-archive.html">BLOG <span class="caret"></span></a>
<ul class="dropdown-menu" role="menu">                
<li><a href="blog-archive.html">BLOG</a></li>
<li><a href="blog-single.html">BLOG DETAILS</a></li>                                            
</ul>
</li> -->
</ul>                            
</div><!--/.nav-collapse -->       
</div>          
</nav> 
</section>
<!-- navigation menu end -->

<!-- slider start -->
<section id="aa-slider">
<div class="aa-slider-area"> 
<div class="aa-top-slider">

<!-- slider 1 -->
<div class="aa-top-slider-single">
<img src="{{ $app_url }}/website_assets/img/slider/overall.jpg" alt="img">
<div class="aa-top-slider-content">
<span class="aa-top-slider-catg">Mortgage Advisory</span>
<h2 class="aa-top-slider-title">Goowise</h2>
<p class="aa-top-slider-location"><i class="fa fa-map-marker"></i>Singapore</p>
<span class="aa-top-slider-off">Mortgage and refinance your property</span>
<p class="aa-top-slider-price">Realise your dream</p>
<!-- <a href="#" class="aa-top-slider-btn">Read More <span class="fa fa-angle-double-right"></span></a> -->
</div>
</div>

<!-- slider end -->
</div>
</div>
</section>

<!-- Advance Search -->
<section id="aa-advance-search" style="display: none;">
<div class="container">
<div class="aa-advance-search-area">
<div class="form">

<div class="aa-advance-search-top">

<!-- title -->
<div class="row">
<div class="col-md-12">
<div class="seminar_title">Upcoming Seminar</div>
</div>
</div>

<!-- date address -->
<div class="row">
<div class="col-md-12">
<div class="seminar_text">30 March 2019 | 42D North Canal Road, Singapore 059298</div>
<br>
<div class="seminar_text">1:00 PPM to 4:00 PM (Refresment Provided)</div>
<br>
<div class="seminar_text">Invited Speakers: Guru Guru</div>
</div>
</div>

<!-- name -->
<div class="row">
<div style="height: 20px"></div>
<div class="col-md-12">
<div class="aa-single-advance-search">
<input id="register_name" type="text" placeholder="Name">
</div>
</div>
</div>

<!-- email -->
<div class="row">
<div style="height: 20px"></div>
<div class="col-md-12">
<div class="aa-single-advance-search">
<input id="register_email" type="text" placeholder="Email Address">
</div>
</div>
</div>

<!-- mobile -->
<div class="row">
<div style="height: 20px"></div>
<div class="col-md-12">
<div class="aa-single-advance-search">
<input id="register_mobile" type="text" placeholder="Mobile Contact">
</div>
</div>
</div>

<!-- reason -->
<div class="row">
<div style="height: 20px"></div>
<div class="col-md-12">
<select id="register_reason" class="seminar_select">
<option value="NA" selected>Your Interest</option>
<option value="How to own your 2nd property">How to own your 2nd property</option>
<option value="Is property the only asset to invest?">Is property the only asset to invest?</option>
<option value="HDB loan or Bank Loan? Which is better?">HDB loan or Bank Loan? Which is better?</option>
<option value="Best way to refinance my property">Best way to refinance my property</option>
<option value="Review of cooling measures">Review of cooling measures</option>
<option value="Identify a good property agent">Identify a good property agent</option>
<option value="Should you use your CPF to pay installment?">Should you use your CPF to pay installment?</option>
</select>
</div>
</div>

<!-- recaptcha -->
<div style="height: 20px"></div>
<div class="g-recaptcha" data-sitekey="6LdGaZIUAAAAAJk-DMSSC5fHoFHR_fGC5NXKJvEu"></div>

<!-- register button -->
<div id="register_button" class="row">
<div style="height: 20px"></div>
<div class="col-md-12">
<div class="seminar_button" onclick="register()">REGISTER</div>
</div>
</div>

<!-- error message -->
<div class="row">
<div style="height: 20px"></div>
<div class="col-md-12">
<div id="register_result" class="seminar_result">Please check that you are not a robot</div>
</div>
</div>

</div>

<!-- <div class="aa-advance-search-bottom">
<div class="row">
<div class="col-md-6">
<div class="aa-single-filter-search">
<span>AREA (SQ)</span>
<span>FROM</span>
<span id="skip-value-lower" class="example-val">30.00</span>
<span>TO</span>
<span id="skip-value-upper" class="example-val">100.00</span>
<div id="aa-sqrfeet-range" class="noUi-target noUi-ltr noUi-horizontal noUi-background">
</div>                  
</div>
</div>
<div class="col-md-6">
<div class="aa-single-filter-search">
<span>PRICE ($)</span>
<span>FROM</span>
<span id="skip-value-lower2" class="example-val">30.00</span>
<span>TO</span>
<span id="skip-value-upper2" class="example-val">100.00</span>
<div id="aa-price-range" class="noUi-target noUi-ltr noUi-horizontal noUi-background">
</div>
</div>
</div>
</div> -->

</div>
</div>
</div>
</div>
</section>
<!-- / Advance Search -->

<!-- About us -->
<section id="aa-about-us">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-about-us-area">
<div class="row">
<div class="col-md-5">
<div class="aa-about-us-left">
<img src="{{ $app_url }}/website_assets/img/about_us.jpg" alt="image">
</div>
</div>
<div class="col-md-7">
<div class="aa-about-us-right">
<div class="aa-title">
<h2>About Us</h2>
<span></span>
</div>
<p>We deliver fast and accurate mortgage advice you can trust in all your mortgage endeavors. Our mortgage advisers have a superb understanding of the lenders to give you a better understanding and in-depth knowledge of the product they are advising you on, rather than a generalist view of a host of lenders. You will receive straightforward and transparent best package available to you based on your individual circumstances.</p>
<ul>
<li>As a young and dynamic company, we are extremely focused on where we want to be and what we offer you. This is best demonstrated by our company mission: “Taking the stress, uncertainty and unnecessary expense out of taking up a mortgage. We want to help those who want to own their own home to realise their dream.” </p></li>
<li>We have also added other carefully chosen products to our offering like insurance, peer-to-peer lending and investing.</li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- / About us -->

<!-- our team start -->
<section id="aa-agents">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-agents-area">
<div class="aa-title">
<h2>Our Team</h2>
<span></span>
<p>Our team has more than 10 years of experience and is proficient in structuring and sourcing for the best financing terms for residential in Singapore, Malaysia, UK, Thailand and Australia.</p>
</div>
<!-- agents content -->
<div class="aa-agents-content">
<ul class="aa-agents-slider">

<!-- jean -->
<li>
<div class="aa-single-agents">
<div class="aa-agents-img">
<img src="{{ $app_url }}/website_assets/img/agents/jean.jpg" alt="agent member image">
</div>
<div class="aa-agetns-info">
<h4><a href="#">Jean Kwek</a></h4>
<span>9680 7646</span>
<span>jeankwek@goowise.com</span>
<span>Senior Mortgage Advisor</span>
<div class="aa-agent-social">
<a href="https://www.facebook.com/goowise"><i class="fa fa-facebook"></i></a>
<a href="#"><i class="fa fa-twitter"></i></a>
<a href="#"><i class="fa fa-linkedin"></i></a>
<a href="#"><i class="fa fa-google-plus"></i></a>
</div>
</div>
</div>
</li>

<!-- jason -->
<li>
<div class="aa-single-agents">
<div class="aa-agents-img">
<img src="{{ $app_url }}/website_assets/img/agents/jason.jpg" alt="agent member image">
</div>
<div class="aa-agetns-info">
<h4><a href="#">Jason Ow</a></h4>
<span>9689 8036</span>
<span>jasonow@goowise.com</span>
<span>Senior Mortgage Advisor</span>
<div class="aa-agent-social">
<a href="https://www.facebook.com/goowise"><i class="fa fa-facebook"></i></a>
<a href="#"><i class="fa fa-twitter"></i></a>
<a href="#"><i class="fa fa-linkedin"></i></a>
<a href="#"><i class="fa fa-google-plus"></i></a>
</div>
</div>
</div>
</li>

<!-- stanley -->
<li>
<div class="aa-single-agents">
<div class="aa-agents-img">
<img src="{{ $app_url }}/website_assets/img/agents/stanley.jpg" alt="agent member image">
</div>
<div class="aa-agetns-info">
<h4><a href="#">Stanley Sum</a></h4>
<span>9021 2842</span>
<span>stanleysum@goowise.com</span>
<span>Senior Mortgage Advisor</span>
<div class="aa-agent-social">
<a href="https://www.facebook.com/goowise"><i class="fa fa-facebook"></i></a>
<a href="#"><i class="fa fa-twitter"></i></a>
<a href="#"><i class="fa fa-linkedin"></i></a>
<a href="#"><i class="fa fa-google-plus"></i></a>
</div>
</div>
</div>
</li>

</ul>
</div>
</div>
</div>
</div>
</div>

<div class="container">
<div style="height: 20px;"></div>
<p>As property investors ourselves, we believed in delivering fast and accurate advice you can trust in all your mortgage endeavors in the quickest time.</p>
<p>As experts in financing solutions, we seek mortgage options from 11 banks and financial institutions in Singapore (i.e DBS, OCBC, HSBC, UOB, SCB, RHB, Maybank, Bank of China, CIMB, Singapura Finance, Hong Leong Finance)</p>
</div>
</section>
<!-- our team end -->

<!-- testimonal start -->
<section id="aa-client-testimonial">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-client-testimonial-area">
<div class="aa-title">
<h2>What Client Say</h2>
<span></span>
<p>The knowledge and expertise our advisers have allows us to deliver a quick and precise solution to you, whatever your circumstance or goals: We want you to be reassured that you will be guided by consummate professionals who will keep you informed of the progress of your mortgage application from start to finish.</p>
</div>
<div class="aa-testimonial-content">
<ul class="aa-testimonial-slider">
<li>
<div class="aa-testimonial-single">
<div class="aa-testimonial-img">
<img src="{{ $app_url }}/website_assets/img/jean.jpg" alt="testimonial img">
</div>
<div class="aa-testimonial-info">
<p>Jean does comprehensive financial planning for her clients with a niche in property purchase and mortgage planning.   Equipped with the knowledge of the rules and regulations on property purchase and property financing, Jean is one of the few financial advisors in Singapore who does professional advisory work on this area.   She has a network of mortgage bankers, accountants and conveyancing lawyers to serve the clients’ purchasing and financing needs. Being a property investor herself, she understands the concerns and perspectives both from the angle of a property owner as well as an investor.  She has helped hundreds of clients over the last 11 years with their property purchase planning and mortgage planning to ensure that their purchase is a save and prudent investment that will add on to their asset list rather than become a liability nightmare beyond what they can handle.</p>
</div>
<div class="aa-testimonial-bio">
<p>Jean Kwek</p>
<span>Senior Mortgage Advisor</span>
</div>
</div>
</li>
<li>
<div class="aa-testimonial-single">
<div class="aa-testimonial-img">
<img src="{{ $app_url }}/website_assets/img/jason.jpg" alt="testimonial img">
</div>
<div class="aa-testimonial-info">
<p>Jason, a Singapore Accredited Mortgage Planner (SAMP), has helped many property buyers & investors with their property purchases since year 2007. Besides helping them with the selection of housing loan packages, Jason also helps to ensure the suitability of the loan based on their individual profiles.</p>
</div>
<div class="aa-testimonial-bio">
<p>Jason Ow</p>
<span>Senior Mortgage Advisor</span>
</div>
</div>
</li>
<li>
<div class="aa-testimonial-single">
<div class="aa-testimonial-img">
<img src="{{ $app_url }}/website_assets/img/stanley.jpg" alt="testimonial img">
</div>
<div class="aa-testimonial-info">
<p>As a Singapore Accredited Mortgage Planner (SAMP) for the past 11 years, Stanley has helped numerous clients in planning for their property purchases. With valuable experience and knowledge, he is able to help them find their dream property that fits their financials. He is also able to help them identify the suitable interest rates packages for their mortgage loan that best fits their situation.</p>
</div>
<div class="aa-testimonial-bio">
<p>Stanley Sum</p>
<span>Senior Mortgage Advisor</span>
</div>
</div>
</li>
</ul>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- testimonial end -->

<!-- client start -->
<section id="aa-client-brand">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-client-brand-area">
<ul class="aa-client-brand-slider">
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/uob.png" alt="brand image">
</div>
</li>
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/scb.png" alt="brand image">
</div>
</li>
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/ocbc.png" alt="brand image">
</div>
</li>
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/maybank.png" alt="brand image">
</div>
</li>
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/hsbc.png" alt="brand image">
</div>
</li>
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/dbs.png" alt="brand image">
</div>
</li>
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/citi.png" alt="brand image">
</div>
</li>
<li>
<div class="aa-client-single-brand">
<img src="{{ $app_url }}/website_assets/img/banks/boc.png" alt="brand image">
</div>
</li>
</ul>
</div>
</div>
</div>
</div>
</section>
<!-- client end -->

<!-- service start -->
<section id="aa-service">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-service-area">
<div class="aa-title">
<h2>Our Service</h2>
<span></span>
<p>Mortgage and refinance your property with Goowise Advisory</p>
</div>
<div class="aa-service-content">
<div class="row">
<div class="col-md-3">
<div class="aa-single-service">
<div class="aa-service-icon">
<span class="fa fa-home"></span>
</div>
<div class="aa-single-service-content">
<h4><a href="#">Resident Property Loan</a></h4>
<p>We seek mortgage options from 11 banks and financial institutions</p>
</div>
</div>
</div>
<div class="col-md-3">
<div class="aa-single-service">
<div class="aa-service-icon">
<span class="fa fa-building"></span>
</div>
<div class="aa-single-service-content">
<h4><a href="#">Commercial Property Loan</a></h4>
<p>Let our team mortgage advisors help you get the best loan</p>
</div>
</div>
</div>
<div class="col-md-3">
<div class="aa-single-service">
<div class="aa-service-icon">
<span class="fa fa-crosshairs"></span>
</div>
<div class="aa-single-service-content">
<h4><a href="#">Refinance Loan</a></h4>
<p>Replace your existing debt obligation with a lower interest rate</p>
</div>
</div>
</div>
<div class="col-md-3">
<div class="aa-single-service">
<div class="aa-service-icon">
<span class="fa fa-bar-chart-o"></span>
</div>
<div class="aa-single-service-content">
<h4><a href="#">Market Analysis</a></h4>
<p>Make smarter decisions on your next property purchase</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- service end -->

<!-- article start -->
<section id="aa-latest-blog" style="display: none;">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-latest-blog-area">
<div class="aa-title">
<h2>Recent Articles</h2>
<span></span>
<p>Get Up To Speed On Mortgage News And Interest Rate Trends!</p>
</div>
<div class="aa-latest-blog-content">
<div class="row">

<!-- article -->
<div class="col-md-4">
<article class="aa-blog-single">
<figure class="aa-blog-img">
<a href="#"><img src="{{ $app_url }}/website_assets/img/blogs/1.jpg" alt="img"></a>
<span class="aa-date-tag">22 April 2019</span>
</figure>
<div class="aa-blog-single-content">
<h3><a href="#">Why use a mortgage specialist or broker?</a></h3>
<p>Mortgage specialists & brokers are independent. They are not owned or employed by any lenders and offer independent impartial advice to their customers based on their needs, savings, requirements and priorities.</p>
<div class="aa-blog-single-bottom">
<a href="#" class="aa-blog-author"><i class="fa fa-user"></i> Jean</a>
<a href="#" class="aa-blog-comments"><i class="fa fa-comment-o"></i>6</a>
</div>
</div>
</article>
</div>

<!-- article -->
<div class="col-md-4">
<article class="aa-blog-single">
<figure class="aa-blog-img">
<a href="#"><img src="{{ $app_url }}/website_assets/img/blogs/2.jpg" alt="img"></a>
<span class="aa-date-tag">15 April 2019</span>
</figure>
<div class="aa-blog-single-content">
<h3><a href="#">How much can I save when I refinance?</a></h3>
<p>Let’s take a look at the numbers on how much you will save every month when you refinance.</p>
<div class="aa-blog-single-bottom">
<a href="#" class="aa-blog-author"><i class="fa fa-user"></i> Jason</a>
<a href="#" class="aa-blog-comments"><i class="fa fa-comment-o"></i>6</a>
</div>
</div>                   
</article>
</div>

<!-- article -->
<div class="col-md-4">
<article class="aa-blog-single">
<figure class="aa-blog-img">
<a href="#"><img src="{{ $app_url }}/website_assets/img/blogs/3.jpg" alt="img"></a>
<span class="aa-date-tag">8 April 2019</span>
</figure>
<div class="aa-blog-single-content">
<h3><a href="#">5 tips to finding the best home loan package</a></h3>
<p>Compare the average interest rate within the lock in period.</p>
<div class="aa-blog-single-bottom">
<a href="#" class="aa-blog-author"><i class="fa fa-user"></i> Stanley</a>
<a href="#" class="aa-blog-comments"><i class="fa fa-comment-o"></i>6</a>
</div>
</div>                   
</article>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- article end -->

<!-- footer start -->
<footer id="aa-footer">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="aa-footer-area">
<div class="row">
<div class="col-md-3 col-sm-6 col-xs-12">
<div class="aa-footer-left">
<p>GOOWISE ADVISORY</p>
</div>
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
<div class="aa-footer-middle">
<a href="https://www.facebook.com/goowise"><i class="fa fa-facebook"></i></a>
<a href="#"><i class="fa fa-twitter"></i></a>
<a href="#"><i class="fa fa-google-plus"></i></a>
<a href="#"><i class="fa fa-youtube"></i></a>
</div>
</div>
<div class="col-md-6 col-sm-12 col-xs-12">
<div class="aa-footer-right">
<a href="#">Home</a>
<a href="#">Support</a>
<a href="#">License</a>
<a href="#">FAQ</a>
<a href="#">Privacy & Term</a>
</div>
</div>            
</div>
</div>
</div>
</div>
</div>
</footer>
<!-- footer end -->

<script src="{{ $app_url }}/website_assets/js/jquery.min.js"></script>
<script src="{{ $app_url }}/website_assets/js/bootstrap.js"></script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- slick slider -->
<script type="text/javascript" src="{{ $app_url }}/website_assets/js/slick.js"></script>
<!-- Price picker slider -->
<script type="text/javascript" src="{{ $app_url }}/website_assets/js/nouislider.js"></script>
<!-- mixit slider -->
<script type="text/javascript" src="{{ $app_url }}/website_assets/js/jquery.mixitup.js"></script>
<!-- Add fancyBox -->        
<script type="text/javascript" src="{{ $app_url }}/website_assets/js/jquery.fancybox.pack.js"></script>
<!-- Custom js -->
<script src="{{ $app_url }}/website_assets/js/custom.js"></script>
<script src="{{ $app_url }}/website_assets/js/website.js"></script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5c3409cd361b3372892ee897/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

<script>
var app_url = "{{ $app_url }}";
</script>

</body>
</html>