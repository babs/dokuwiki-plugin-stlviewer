Stl Viewer v1.13
================

Installation:
-------------
upload those files into your web server:
stl_viewer.min.js 
parser.min.js 
load_stl.min.js 
webgl_detector.js 
CanvasRenderer.js 
OrbitControls.js
TrackballControls.js
Projector.js 
three.min.js


Usage:
------
At the html body:

<script src="stl_viewer.min.js"></script>
<div id="stl_cont"></div>
<script>
	var stl_viewer=new StlViewer(document.getElementById("stl_cont"), { models: [ {id:0, filename:"mystl.stl"} ] });
</script>



Documentation & License details:
--------------------------------
https://www.viewstl.com/plugin/ 

* Viewstl Javscript plugin is licensed under the MIT License - Copyright (c) 2019 Viewstl.com
* three.js is licensed under the MIT License - Copyright © 2010-2019 three.js authors



by Omri Rips, Viewstl.com
