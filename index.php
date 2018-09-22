<?php 
include 'src/key.php';



?>
<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="./src/app.css">
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
            var send = window.confirm("送信しても後悔しませんか？");
            if(send == false){
                $('#image-select').val('');
                return false;
            }
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
								console.log('remodal free');
								$('.alert-success').css('display', 'block');
							}else{
								console.log('remodal lock');
								$('.alert-warning').css('display', 'block');
							}
							
						}

           function processImage(url) {
               $('.alert-success').css('display', 'none');
               $('.alert-warning').css('display', 'none');
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
            

            if(arraytags.indexOf('人') >= 0 && arraytags.indexOf('メガネ') < 0 && arraytags.length >= 5){
                modal_open(false);
            }
            else{
                modal_open(true);
            }


            
                
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

<header class="gray">
	<h1>FRESODE</h1>
</header>

<article>
	<!-- <div class="input_row">
		<input type="file"  id="image-select" >
	</div> -->
	<div class="input-group input_row">
    <label class="input-group-btn">
        <span class="btn btn-primary">Choose  your file
            <input type="file" id="image-select" style="display:none">
        </span>
    </label><img id="loading_sign" src="">
</div>
	<div  class="input_group input_row">
		<input type="text" name="inputImage" class="form-control" id="inputImage"
				value="" placeholder=" or http://example.com/image.jpg" />
		<button class="btn" id="submit_button">Go!</button>
	</div>
	<div id="wrapper" style="margin-top: 50px;width:1020px; display:table;">
			<div id="imageDiv" style="width:420px; display:table-cell;">
					
					<div class="alert alert-success" role="alert" style="display:none;" >
						<strong>やったね！</strong>この写真はフリー素材みたいだよ。
					</div>
					<div class="alert alert-warning" role="alert" style="display:none;" >
						<strong>残念！</strong>この写真はフリー素材じゃないみたい。
					</div>
					<div>送信された画像</div>
					<img id="sourceImage" width="400" />
			</div>
	</div>
</article>
</body>
</html>