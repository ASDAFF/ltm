$(function() {
	var $document = $(document);
	var $result = $(".js-data");

	$document.on("click", ".js-open-tab", function() {
		var $this = $(this);
		var openTab = $(".js-tab."+$this.data("tab-class"));

		$this.parent().find(".js-open-tab.chosen").removeClass("chosen");
		$this.addClass("chosen");

		openTab.parent().find(".js-tab").hide();
		openTab.show();
	});

	$document.on("click", ".js-show-country", function() {
		$(".alphabetic-filter .chosen").removeClass("chosen");
		$(".filter-result .chosen .current").removeClass("current");

		var result = {};

		for(member in membersData) {
			for(country in membersData[member]["PRIORAREA"]) {
				if(result[membersData[member]["PRIORAREA"][country]] === undefined) {
					result[membersData[member]["PRIORAREA"][country]] = [member];//[membersData[member]["ID"]];
				} else {
					result[membersData[member]["PRIORAREA"][country]].push(member/*membersData[member]["ID"]*/);
				}
			}
		}

		console.log(result);

		writeMembers($result, result);
	});

	$document.on("click", ".js-filter-priorarea, .js-filter-category", function() {
		$(".alphabetic-filter .chosen").removeClass("chosen");

		$result.children("li").show();
		var $this = $(this);
		$(".filter-result li.current").removeClass("current");
		$this.parent().addClass("current");

		$result.children("li.current").removeClass("current");
		var catName = $this.data('filter');
		var $findedCat = $result.find("li[data-category-name='"+catName+"']");
		$findedCat.addClass("current");
		//пр€чем все элементы до выбранного
		$findedCat.prevAll().hide();
		
		//находим следующую категорию
		$nextCat = $findedCat.nextAll("li[data-category-name]:first");
		if($nextCat.length > 0 )
		{
			$nextCat.nextAll().hide(); console.log($nextCat);
			$nextCat.hide();
		}
		
	});

	$document.on("click", ".js-show-category", function() {
		$(".alphabetic-filter .chosen").removeClass("chosen");
		$(".filter-result .chosen .current").removeClass("current");

		var result = {};

		for(member in membersData) {
			if(result[membersData[member]["CATEGORY"]] === undefined) {
				result[membersData[member]["CATEGORY"]] = [member];//[membersData[member]["ID"]];
			} else {
				result[membersData[member]["CATEGORY"]].push(member/*membersData[member]["ID"]*/);
			}
		}

		writeMembers($result, result);
	});

	
	$document.on("click", ".js-show-all", function() {
		$(".alphabetic-filter .chosen").removeClass("chosen");
		$(".js-very-big-letter").text($(this).text());
		
		var result = {};

		for(member in membersData) {
			if(membersData[member]["ID"] == undefined || membersData[member]["NAME"] == undefined) continue;
			var memberId = member;
			var memberName = membersData[member]["NAME"];
			var curLetter = memberName.substr(0, 1).toUpperCase();


			if(result[curLetter] === undefined) {
				result[curLetter] = [memberId];
			} else {
				result[curLetter].push(memberId);
			}
		}

		writeMembers($result, result);
	});
	
	
	$document.on("click", ".js-filter-letter", function() {
		$(".events-filter .chosen").removeClass("chosen");

		var $this = $(this);
		$this.addClass("chosen");

		var result = {};
		var lowerLetter = $this.data('filter').toUpperCase();

		$(".js-very-big-letter").text(lowerLetter);

		for(member in membersData) {
			if(membersData[member]["ID"] == undefined || membersData[member]["NAME"] == undefined) continue;
			var memberId = member;//membersData[member]["ID"];
			var memberName = membersData[member]["NAME"];
			var curLetter = memberName.substr(0, 1).toUpperCase();

			if(lowerLetter != curLetter) continue;

			if(result[curLetter] === undefined) {
				result[curLetter] = [memberId];
			} else {
				result[curLetter].push(memberId);
			}
		}

		writeMembers($result, result);
	});

	function getKeysSortedObject(myObj) {
		var keys = [];

		for (i in myObj) {
			if (myObj.hasOwnProperty(i)) {
				keys.push(i);
			}
		}

		keys.sort();

		return keys;
	}

	function writeMembers($result, result) {
		$result.empty();
		var keys = getKeysSortedObject(result);

		for(category in keys) {
			var category = keys[category];
			$result.append('<li class="title" data-category-name="'+category+'">'+category+'</li>');
			for(item in result[category]) {
				var id = result[category][item];
				$result.append('<li><a title="'+membersData[id]["NAME"]+'" href="/members/'+membersData[id]["ID"]+'/">'+membersData[id]["NAME"]+'</a></li>');
			}
		}
	}

});