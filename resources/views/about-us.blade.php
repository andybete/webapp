
@include("header");
<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('styles.css') }}" />
  <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/image_slide.js') }}"></script>
</head>  
<body>
  <div id="site_content">			  
    <ul class="slideshow">
      <li><img width="870" height="250" src="{{ asset('images/images.jpg') }}"/></li>
      <li><img width="870" height="250" src="{{ asset('images/inaction.jpg') }}"/></li>
      <li><img width="870" height="250" src="{{ asset('images/inaction.jpg') }}"/></li>
    </ul> <br>  
      <div id="content">
        <div id="content_item">
        <p><span><h3>mission</h3><font size="4"/></span> <font size="4"/>To establish strong and quality assessment system lead by industry and producing globally competent work force in 2020</p>
        <p><span><h3>vission</h3><font size="4"/></span>Amhara Occupational Competency Assessment and Certification center promotes occupational assessment throughout the Amhara National Regional State in collaboration with the industry through preparation of assessment tools development, training of industry assessors and accredit the assessment centers and gives occupational competency assessment in various occupations for candidates who graduate from different TVET Colleges and Industry workers.</p>
      </div>
	  </div>
	</div>  

@include("footer");
	