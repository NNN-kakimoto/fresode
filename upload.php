<?php 
include 'src/key.php';



?>
<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">
$(function(){
	
	$(document).on('change','#image-select',function(){
		console.log('aaa');
		var blob = document.getElementById('image-select').files[0];
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
		}).fail(function(jqXHR, textStatus, errorThrown) {
			var errorString = (errorThrown === '') ? 'Error. ' : errorThrown + ' (' + jqXHR.status + '): ';
			errorString += (jqXHR.responseText === '') ? '' : (jQuery.parseJSON(jqXHR.responseText).message) ?
					jQuery.parseJSON(jqXHR.responseText).message : jQuery.parseJSON(jqXHR.responseText).error.message;
		});
	});
});

</script>
<input type="file" id="image-select" >
<!-- <input name="image_area" id="image_area" type="file" class="js-example-textarea" > -->
</textarea>

</body>
</html>