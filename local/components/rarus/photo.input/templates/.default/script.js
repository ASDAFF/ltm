// Check for the various File API support.
var isFileAPI = false;
if (window.File && window.FileReader && window.FileList && window.Blob) {
	isFileAPI = true;
}

//if browser supports File API  let's make image preview
if ( isFileAPI ) {
	function handleFileSelect(evt, el) {
		var files = evt.target.files;
		var f = files[0];
		var reader = new FileReader();

		reader.onload = (function(theFile) {
			return function(e) {
				$(el).closest('.profil-photo').find('.img_preview').css('background-image', "url('" + e.target.result + "')");
			};
		})(f);
		reader.readAsDataURL(f);
	}

	function flushInput(el, name){
		$(el).closest('.profil-photo').find('input[type=file]').val("");
		$(el).closest('.profil-photo').find('input[type=file]').after($("<input />", {type: "hidden", name: name, value: "Y"}));
		$(el).closest('.profil-photo').find('.img_preview').css('background-image', "");

	}
}

$(document).ready(function(){

});