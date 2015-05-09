<?//-->Таблица   ?>
<? include("shedule.php");?>
<?//<--Таблица   ?>
<div class="pull-overflow generate-file">
	<div class="pull-left">
		<a href="" title=""><?= GetMessage("PROFILE_GENERATE_WISHLIST_PDF")?></a>
	</div>
	<div class="pull-right">
		<a href="" title=""><?= GetMessage("PROFILE_GENERATE_SCHEDULE_PDF")?></a>
	</div>
</div>
<div class="request-guests">
	<div><?= GetMessage("PROFILE_SHEDULE")?></div>
	<table class="section-request">
		<tr>
			<td class="appointments">
				<div class="wish-list"><?= GetMessage("PROFILE_MY_WISHLIST1")?></div>
				<form action="">
					<table class="morning-time">
						<tr>
							<th>№</th>
							<th><?= GetMessage("PROFILE_COMPANY")?></th>
						</tr>
						<tr>
							<td>1</td>
							<td>Mandarin Oriental Barcelona</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Lorem ipsum dolor sit amet.</td>
						</tr>
						<tr>
							<td>7</td>
							<td>Lorem ipsum dolor sit amet.</td>
						</tr>
					</table>
					<div class="send-request"><a href="" title=""><?= GetMessage("PROFILE_SEND_REQUEST")?></a></div>
					
					<select name="" id="">
						<option value=""><?= GetMessage("PROFILE_CHOOSE_COMPANY")?></option>
					</select>
				</form>
			</td>
			<td>
				<div class="wish-list"><?= GetMessage("PROFILE_MY_WISHLIST2")?></div>
				<table class="morning-time">
					<tr>
						<th>№</th>
						<th><?= GetMessage("PROFILE_COMPANY")?></th>
					</tr>
					<tr>
						<td>1</td>
						<td>Mandarin Oriental Barcelona</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
