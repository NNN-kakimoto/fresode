<?php 
include 'src/key.php';



?>
<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="./src/remodal.min.js"></script>
		<link rel="stylesheet" href="src/remodal.css">
		<link rel="stylesheet" href="src/remodal-default-theme.css">
</head>
<body>

<script type="text/javascript">
    $(function(){
			$('#submit_button').click(function(){
				url = $('#inputImage').val();
				
				if(url != ''){
					processImage(url);
				}
			});
        $(document).on('change','#image-select',function(){
            // console.log('aaa');
						var blob = document.getElementById('image-select').files[0];
						$('#loading_sign').attr('src', 'src/load.gif');
            var formData = new FormData();
            formData.append('image', blob);
            // this.uploadImage(formData);
            // this.writed = true;	
            // var formData = new FormData();
            // if ($("input[name='image_area']").val()!== '') {
            // 	formData.append( "file", $("input[name='image_area']").prop("files")[0] );
            // }
            // formData.append('image', blob);
            var addres = $.ajax({
                contentType: false,
                data: formData,
                datatype: 'json',	
                headers: {
                    Authorization: 'Client-ID ee3b05e88daaede',
                },
                processData: false,
                type: 'POST',
                url: 'https://api.imgur.com/3/image'
            }).done(function(data){
                console.log(data);
                var addres = data.data.link;
                console.log(addres);
                
                processImage(addres);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                var errorString = (errorThrown === '') ? 'Error. ' : errorThrown + ' (' + jqXHR.status + '): ';
                errorString += (jqXHR.responseText === '') ? '' : (jQuery.parseJSON(jqXHR.responseText).message) ?
                jQuery.parseJSON(jqXHR.responseText).message : jQuery.parseJSON(jqXHR.responseText).error.message;
		    		}).always(function(){
							$('#loading_sign').attr('src','');
						});
				});
						function modal_open(input){
							if(input){
								$('[data-remodal-id=modal_free]').remodal();
							}else{
								$('[data-remodal-id=modal_lock]').remodal();
							}
							
						}

           function processImage(url) {
						 console.log(url);
						 if(url == ''){
							 url = $('#inputImage').val();
						 }
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************

        // Replace <Subscription Key> with your valid subscription key.
        
    //API切り替え
    
        var subscriptionKey = '<?php echo $key1; ?>';

        // You must use the same region in your REST call as you used to get your
        // subscription keys. For example, if you got your subscription keys from
        // westus, replace "westcentralus" in the URI below with "westus".
        //
        // Free trial subscription keys are generated in the westcentralus region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
            "https://westcentralus.api.cognitive.microsoft.com/vision/v2.0/analyze";

        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "ja",
        };

        // Display the image.
        document.querySelector("#sourceImage").src = url;

        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),

            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },

            type: "POST",

            // Request body.
            data: '{"url": ' + '"' + url + '"}',
        })

        .done(function(data) {
            // Show formatted JSON on webpage.
             //$("#responseTextArea").val(
            // console.log(JSON.stringify(data, null, 2))//)
            console.log(data);
            console.log(data.description.tags);
            arraytags = data.description.tags;
            

            if(arraytags.indexOf('人') >= 0 && arraytags.indexOf('メガネ') < 0 && arraytags.lengtj >= 5){
                modal_open(false);
            }
            else{
                modal_open(true);
            }


            
                
             })
            
        })

        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
        
    };              

    });
 
</script>

<h1>FRESODE</h1>
<div class="remodal-bg"></div>
<input type="file" id="image-select" ><img id="loading_sign" src=""><br>
<input type="text" name="inputImage" id="inputImage"
    value="" placeholder="http://example.com/image.jpg" />
<button id="submit_button">Go!</button>
<br><br>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>
<a href="#modal">Call the modal with data-remodal-id="modal"</a>
<div class="remodal" data-remodal-id="modal_free">
  <button data-remodal-action="close" class="remodal-close"></button>
  <h1>フリー素材です！</h1>
  <p></p>
  <br>
  <button data-remodal-action="confirm" class="remodal-confirm">OK</button>
</div>
<div class="remodal" data-remodal-id="modal_lock">
  <button data-remodal-action="close" class="remodal-close"></button>
  <h1>フリー素材ではありません・・・</h1>
  <p></p>
  <br>
  <button data-remodal-action="confirm" class="remodal-confirm">OK</button>
</div>
</body>
</html>