$(function(){
	$('table.list tr').on("click", "input[name^=CONFIRM]", function(){
		
		var $checkbox = $(this);
		var tr = $checkbox.parents("tr");
		var $groupCheckbox = tr.find("input[name^=SELECTED_USERS]:checkbox");
		//если есть чекнутые подтверждения, то отмечаем в колонке групповых действий
		var count = tr.find("input[name^=CONFIRM]:checkbox:checked");
		if(count.length > 0)
		{
			$groupCheckbox.prop("checked", "checked");
		}
		else
		{
			$groupCheckbox.prop("checked", "");
		}
		
	});
});