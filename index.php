<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Editor</title>
    <meta name="description" content="Video Editor bookmyshow" />
    <meta name="keywords" content="button styles, css3, modern, flat button, subtle, effects, hover, web design" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>
    
    <script src="https://code.jquery.com/jquery-1.12.3.min.js"   integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ="   crossorigin="anonymous"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <style>
        .inputfile-1 + label {
            color: #f1e5e6;
            background-color: #226fbe;
        }
        
        .inputfile-1:focus + label,
        .inputfile-1.has-focus + label,
        .inputfile-1 + label:hover {
            background-color: #226fbe;
        }
        
        .inputfile + label svg {
            width: 1em;
            height: 1em;
            vertical-align: middle;
            fill: currentColor;
            margin-top: -0.25em;
            /* 4px */
            margin-right: 0.25em;
            /* 4px */
        }
    </style>
</head>

<body>
    <!--<img src="Result/meow.gif"/>-->
    		<div style="z-index:50;" class="la-anim-10"></div>
    <div class="container">
        <!-- Top Navigation -->
        <div class="main" style="display:none">
				<div id="la-buttons" class="column">
	<button data-anim="la-anim-10" id="loading" >Corner indicator</button>
    </div>
    </div>
        <section class="color-1" style="height: 100%;width: 100%;padding: 0em 0em;">
            <form action="home.php" id="frmVideo" method="post" enctype="multipart/form-data">
                <h1>
                <div style="height:150px;width:100%;background-color:white;color:#89867e;font-family:'Lato', Calibri, Arial, sans-serif;font-size:2.625em;line-height:1.3;font-weight:300;cursor:pointer;" onclick="javascript:window.location='index.php'">
                        Video Editor
                </div>                    
            </h1>
        <div id ="uploadpage">
                    <table align="center">
                        <tr style="text-align:left;">
                            <td style="padding-bottom:30px;" >Trailer Video</td>
                            <td style="padding-bottom:30px;">
                                <input type="file" name="trailer" id="trailer" style="margin-left:24px" required/>
                            </td>
                        </tr>
                        <tr style="text-align:left;">
                            <td >Output Format</td>
                            <td>
                                <select name="format" id="format" style="width: 70px; margin-left: 24px;" required>
                                    <option value="mp4">mp4</option>
                                    <option value="avi">avi</option>
                                    <option value="webm">mkv</option>
                                    <option value="flv">mov</option>
                                    <option value="flv">flv</option>
                                    <option value="flv">wmv</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </p>
                <table id="DynamicAd" align="center">
                    <tr style="font-size:1.5em;">
                       <td>Advertisement link</td>
                        <td>Advetisement Position<input type="button" onclick="addRow();" value="+"  ></td>
                         
                    </tr>
                    <tr id="dynamicRow">
                           <td>
                            <p style="font-size:1.5em;">
                                <input type="file" name="ad1" id="ad1" required /> </p>
                        </td>
                        <td>
                            <p style="font-size:1.5em;">
                                <input type="text" name="position1" id="position1" required />
                            </p>
                        </td>
                     
                    </tr>
                </table>

                <p>
                    <input type="submit" onclick="formLoading();" style="margin: 15px 30px;" name="submit" class="btn btn-1 btn-1a" value="Submit" />
                </p>
            </form>
            </div>
        <div id="previewPage" style="display:none;">
            <video width="850" height="480" controls id="video">
                <source id ="previewVid" src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <img src="Assets/NoPreview.jpg" style="display:none;" id="previewImg">
            </img>
            <p>
                <a class="btn btn-1 btn-1a" id="downloadVid" >Download Video</a>
                 <a class="btn btn-1 btn-1a" id="downloadImg" >Download ThumbNail</a>
                </p>
          </div>
            </form>
        </section>

    </div>
    <!-- /container -->
    <script src="js/classie.js"></script>
    <script>
        var buttons7Click = Array.prototype.slice.call( document.querySelectorAll( '#btn-click button' ) ),
				buttons9Click = Array.prototype.slice.call( document.querySelectorAll( 'button.btn-8g' ) ),
				totalButtons7Click = buttons7Click.length,
				totalButtons9Click = buttons9Click.length;

			buttons7Click.forEach( function( el, i ) { el.addEventListener( 'click', activate, false ); } );
			buttons9Click.forEach( function( el, i ) { el.addEventListener( 'click', activate, false ); } );

			function activate() {
				var self = this, activatedClass = 'btn-activated';

				if( classie.has( this, 'btn-7h' ) ) {
					// if it is the first of the two btn-7h then activatedClass = 'btn-error';
					// if it is the second then activatedClass = 'btn-success'
					activatedClass = buttons7Click.indexOf( this ) === totalButtons7Click-2 ? 'btn-error' : 'btn-success';
				}
				else if( classie.has( this, 'btn-8g' ) ) {
					// if it is the first of the two btn-8g then activatedClass = 'btn-success3d';
					// if it is the second then activatedClass = 'btn-error3d'
					activatedClass = buttons9Click.indexOf( this ) === totalButtons9Click-2 ? 'btn-success3d' : 'btn-error3d';
				}

				if( !classie.has( this, activatedClass ) ) {
					classie.add( this, activatedClass );
					setTimeout( function() { classie.remove( self, activatedClass ) }, 1000 );
				}
			}

			// document.querySelector( '.btn-7i' ).addEventListener( 'click', function() {
			// 	classie.add( document.querySelector( '#trash-effect' ), 'trash-effect-active' );
			// }, false );
            
            var rowCount = 1;
            function addRow(){
                   table=document.getElementById("DynamicAd");
                    rowCount++; 
                    var recRow='<td><p style="font-size:1.5em;"><input type="file" name="ad'+rowCount+'" id="ad'+rowCount+'" required/> </p></td><td><p style="font-size:1.5em;"><input type="text" name="position'+rowCount+'" id="position'+rowCount+'" required /></p></td>';
                     var trEle=document.createElement("tr");
                        trEle.innerHTML= recRow;
                        table.appendChild(trEle);
                   
                     
            }
            
        var qs = (function(a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i)
        {
        var p=a[i].split('=', 2);
            if (p.length == 1)
            b[p[0]] = "";
        else
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
        })(window.location.search.substr(1).split('&'));
        
        //Url validation
        if(qs["error"]==1){
            alert("Advertisement position is greater than Trailer duration!!");
        }   if(qs["error"]==2){
            alert("Trailer video format not supported!!");
        }   if(qs["error"]==3){
            alert("Advertisement video format not supported !!");
        }
        if(qs["id"]!=undefined && qs["format"]!=undefined){
            document.getElementById("uploadpage").style.display="none";
              document.getElementById("previewPage").style.display="";
              
              if(qs["format"]=="flv"||qs["format"]=="avi"){
                  document.getElementById("previewImg").style.display="";
                 document.getElementById("video").style.display="none";
                  
              }else{
                  videoTag=document.getElementById("previewVid");
                  videoTag.src="Assets/"+qs["id"]+"/output."+qs["format"];
                  videoTag.type="video/"+qs["format"];
                  document.getElementById("video").load();
              }
            
          
            document.getElementById("downloadVid").setAttribute("href","download.php?type=video&id="+qs["id"]+"&format="+qs["format"]);
            document.getElementById("downloadImg").setAttribute("href","download.php?type=image&id="+qs["id"]+"&format=png");
        }
    </script>
    
    <script>

			var inProgress = false;

			Array.prototype.slice.call( document.querySelectorAll( '#la-buttons > button' ) ).forEach( function( el, i ) {
				var anim = el.getAttribute( 'data-anim' ),
					animEl = document.querySelector( '.' + anim );

				el.addEventListener( 'click', function() {
					if( inProgress ) return false;
					inProgress = true;
					classie.add( animEl, 'la-animate' );

					if( anim === 'la-anim-6' ) {
						PieDraw();
					}

					setTimeout( function() {
						classie.remove( animEl, 'la-animate' );
						
						if( anim === 'la-anim-6' ) {
							PieReset();
						}
						
						inProgress = false;
					}, 600000 );
				} );
			} );
		</script>
        <script>
            jQuery.extend(jQuery.validator.messages, {
                required: ""
            });
            
            function formLoading(){
                if($("#frmVideo").valid()){
                    document.getElementById("loading").click();
                }
            }
        </script>
</body>

</html>