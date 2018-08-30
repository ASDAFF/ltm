/**
 * Created by dmitry on 08.08.15.
 */
$(document).ready(function(){
	photoCount = 0;
	if(typeof maxPhotoCount == "undefined")
	{
		maxPhotoCount = 12; //на всякий случай, если из компонента что-то не получится
	}
	//составляем список изначальных фотографий
	arPhotos = new Object();
	photoSortUpdate();


	$(function() {
		$("#sortable" ).sortable({
			revert: true
		});

		$( "#sortable" ).disableSelection();


		$(".photo-list").on('click', ".photo-delete", photoDelete);
	});

	//пересчитываем сортировку при перемещении
	$( "#sortable" ).on( "sortupdate", photoSortUpdate);





	function photoSortUpdate()
	{
		$("#sortable li").each(function(index, element){
			var photo = $(element);
			var id = photo.attr("data-id");

			if(!arPhotos[id])
			{
				arPhotos[id] = {};
			}
			arPhotos[id]["SORT"] = index;

		});

		//удаяем временные
		for(var id in arPhotos)
		{
			if(id.indexOf("tmp") != -1)
			{
				delete arPhotos[id];
			}
			else if(id.indexOf("new") != -1 && arPhotos[id].DELETE == 1)
			{
				delete arPhotos[id];
			}
		}

		photoCount = $("#sortable li").length;
		$("#photo_count").text(maxPhotoCount - photoCount);
	}

	function photoDelete()
	{
		var photo = $(this).closest("li");
		var id = photo.data("id");

		if(!arPhotos[id])
		{
			arPhotos[id] = {};
		}
		arPhotos[id]["DELETE"] = 1;
		photo.remove();
		photoSortUpdate();
	}






	//Загрузка файлов
	(function() {
		var dropzone = document.getElementById("dropzone");
		dropzone.ondragover = function() {
			this.className = 'dropzone dragover';
			return false;
		};

		dropzone.ondragleave = function() {
			this.className = 'dropzone';
			return false;
		};

		dropzone.ondrop = function(e) {
			this.className = 'dropzone ';
			e.preventDefault();

			var canUpload = maxPhotoCount - photoCount;

			if(canUpload <= 0)
			{
				canUpload = 0;
			}


			for(x = 0; (x < e.dataTransfer.files.length) && (x < canUpload); x++) {
				photoUpload(e.dataTransfer.files[x]);
			}


		};
	})();

	//Загрузка через кнопку
	$("#uploadbtn").on("change", function(){
		var files = $(this)[0].files;
		var canUpload = maxPhotoCount - photoCount;

		if(canUpload <= 0)
		{
			canUpload = 0;
		}

		for(x = 0; (x < files.length) && (x < canUpload); x++) {
			photoUpload(files[x]);
		}

		//очищаем
		$(this).val("");
	});

	function photoUpload(file)
	{
		var imagesType = ["image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/tiff"];
		//проверяем тип файла, если не картинка, то игнорируем
		if(imagesType.indexOf(file.type) == -1)
		{
			return ;
		}

		if(file.size >= 3145728)
		{
			return ;
		}

		var formData = new FormData();
		var xhr = new XMLHttpRequest();

		formData.append('photo-file', file);

		//добавляем признак аяксовой загрузки
		formData.append("ajax_load_file", "1");

		var new_id = "tmp:" + Math.floor(Math.random( ) * (1000)) + 1;
		xhr.upload.addEventListener('progress', function(event){
			var percent = parseInt(event.loaded / event.total * 100);

			//Создаем пустой элемент
			var li = $(".photo-list li[data-id='"+new_id+"']");
			//если нашли обновляем
			if(li.length)
			{
				li.find("progress").val(percent);
			}
			else
			{
				var li = $("<li />").attr("data-id", new_id).addClass("ui-sortable-handle");
				var div = $("<div />").addClass("img-wrapper");
				var progress = $("<progress />", {"value": percent, "max": "100"}).appendTo(div);
				li.append(div);
				li.appendTo(".photo-list");
			}
			//иначе создаем новый

		}, false);



		xhr.onload = function() {
			var item = JSON.parse(this.responseText);

			var li = $(".photo-list li[data-id='"+new_id+"']");

			if(li.length)
			{
				var div = li.find(".img-wrapper");
				div.empty();
				var img = $("<img />").attr("src", item.SRC).appendTo(div);
				var close = $("<span />").addClass("photo-delete").attr("title", "Удалить").html("&times;").appendTo(li);
				li.attr("data-id", "new:" +item.ID);

			}
			else
			{
				var li = $("<li />").attr("data-id", "new:" +item.ID).addClass("ui-sortable-handle");
				var div = $("<div />").addClass("img-wrapper");
				var img = $("<img />").attr("src", item.SRC);
				var close = $("<span />").addClass("photo-delete").attr("title", "Удалить").html("&times;");
				div.append(img);
				li.append(div).append(close);

				li.appendTo(".photo-list");
			}

			//пересчитываем
			photoSortUpdate();
		};

		xhr.open('post', '/cabinet/edit/participant-company-photo.php', true);
		xhr.send(formData);
	}


	//сохраняем профиль
	$("form[name=company-update]").on("submit", function(){
		var form = $(this);

		//добавляем данные по фотографиям
		for(var index in arPhotos)
		{
			var arPhoto = arPhotos[index];

			var input = $("<input />", {
				type: "hidden",
				value: arPhoto.SORT,
				name: "PHOTO["+index+"]"
			});

			if(typeof arPhoto.DELETE != "undefined")
			{
				input.val("DELETE");
			}
			form.append(input);
		}
	});

});