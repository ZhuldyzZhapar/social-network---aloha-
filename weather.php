<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<!-- Ен бырыншы турган Алматы нын коды -->
	<!-- <a target="_blank" href="https://nochi.com/weather/almaty-10297"><img src="https://w.bookcdn.com/weather/picture/11_10297_1_20_ffffff_118_2373ca_333333_ffffff_1_ffffff_333333_0_6.png?scode=124&domid=589&anc_id=54919"  alt="booked.net"/></a> -->
	<!-- weather widget end -->

	<!-- Екыншы турган Астананын коды -->
	<!-- <a target="_blank" href="https://nochi.com/weather/astana-w1465"><img src="https://w.bookcdn.com/weather/picture/11_w1465_1_20_ffffff_118_2373ca_333333_ffffff_1_ffffff_333333_0_6.png?scode=124&domid=589&anc_id=54919"  alt="booked.net"/></a> -->
	<!-- weather widget end -->

<!-- <div id="m-booked-small-t3-3841">
	 <div class="booked-weather-160x36 w160x36-03" style="background-color:#fff5d9; color:#333333; border-radius:4px; -moz-border-radius:4px;"> 
	 	<a target="_blank" style="color:#08488D;" href="https://nochi.com/weather/almaty-10297" class="booked-weather-160x36-city">Алматы</a> 
	 	<a target="_blank" class="booked-weather-160x36-right" href="https://www.booked.net/">
	 		<img src="//s.bookcdn.com/images/letter/s5.gif" alt="https://www.booked.net/" /></a> 
	 		<div class="booked-weather-160x36-degree">
	 			<span class="plus">+</span>17&deg;<span>C</span>
	 		</div>
	 	</div> 
 </div> -->
	<script type="text/javascript"> var css_file=document.createElement("link"); css_file.setAttribute("rel","stylesheet"); css_file.setAttribute("type","text/css"); css_file.setAttribute("href",'https://s.bookcdn.com/css/w/bw-160-36.css?v=0.0.1'); 
	document.getElementsByTagName("head")[0].appendChild(css_file); 
	function setWidgetData(data) { if(typeof(data) != 'undefined' && data.results.length > 0) { for(var i = 0; i < data.results.length; ++i) { var objMainBlock = document.getElementById('m-booked-small-t3-3841'); 
if(objMainBlock !== null) { 
	var copyBlock = document.getElementById('m-bookew-weather-copy-'+data.results[i].widget_type); 
	objMainBlock.innerHTML = data.results[i].html_code; 

if(copyBlock !== null) objMainBlock.appendChild(copyBlock); } } } 

else { alert('data=undefined||data.results is empty'); } } 
</script>
	 <script type="text/javascript" charset="UTF-8" src="https://widgets.booked.net/weather/info?action=get_weather_info&ver=6&cityID=10297&type=13&scode=124&ltid=3539&domid=589&anc_id=44478&cmetric=1&wlangID=20&color=fff5d9&wwidth=158&header_color=fff5d9&text_color=333333&link_color=08488D&border_form=0&footer_color=fff5d9&footer_text_color=333333&transparent=0"></script><!-- weather widget end -->
</body>
</html>