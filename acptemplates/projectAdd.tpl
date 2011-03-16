{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabMenu.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	var tabMenu = new TabMenu();
	onloadEvents.push(function() { tabMenu.showSubTabMenu("{$activeTabMenuItem}") });
	//]]>
</script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}acp/js/AccessList.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	
	var developerEntities = new Array();
	{assign var=i value=0}
	{foreach from=$developerEntities item=developerEntity}
		developerEntities[{@$i}] = new Object();
		developerEntities[{@$i}]['name'] = '{@$developerEntity.name|encodeJS}';
		developerEntities[{@$i}]['type'] = '{@$developerEntity.type}';
		developerEntities[{@$i}]['id'] = '{@$developerEntity.id}';
		{assign var=i value=$i+1}
	{/foreach}
	
	var accessEntities = new Array();
	{assign var=i value=0}
	{foreach from=$accessEntities item=accessEntity}
		accessEntities[{@$i}] = new Object();
		accessEntities[{@$i}]['name'] = '{@$accessEntity.name|encodeJS}';
		accessEntities[{@$i}]['type'] = '{@$accessEntity.type}';
		accessEntities[{@$i}]['id'] = '{@$accessEntity.id}';
		{assign var=i value=$i+1}
	{/foreach}
	
	onloadEvents.push(function() {
		// add observer for filling project owner-select
		$('developerEntities').observe('access:refresh', function(e) {
			var select = $('ownerID');
			if (activeOwnerID == null) activeOwnerID = select.value;
			select.childElements().each(function(element) {
				element.remove();
			});
			var emptyElement = new Element('option', {
				value: 0
			}).insert('&nbsp;');
			select.insert(emptyElement);
			
			var developers = Event.element(e).accessList.entities;
			developers.each(function(developer) {
				if (developer.type == 'user') {
					var element = new Element('option', {
						value: developer.id
					}).update(developer.name);
					if (developer.id == activeOwnerID) {
						element.writeAttribute('selected');
					}
					select.insert(element);
				}
			});
			
			activeOwnerID = null;
		});
		
		// developer
		var developer = new AccessList('developerEntities', developerEntities, AccessList.FILTER_USER);
		
		// access
		var access = new AccessList('accessEntities', accessEntities);
		
		// add onsubmit event
		$('projectAddForm').onsubmit = function() { 
			if (suggestion.selectedIndex != -1) return false;
			if (developer.inputHasFocus || access.inputHasFocus) return false;
			
			developer.submit(this);
			access.submit(this);
		};
	});
	//]]>
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_ICT_DIR}icon/project{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}ict.acp.project.{@$action}{/lang}</h2>
		{if $projectID|isset}<p>{lang}{$project->title}{/lang}</p>{/if}
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}ict.acp.project.{@$action}.success{/lang}</p>
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=ProjectList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}ict.acp.menu.link.content.project.view{/lang}"><img src="{@RELATIVE_ICT_DIR}icon/projectM.png" alt="" /> <span>{lang}ict.acp.menu.link.content.project.view{/lang}</span></a></li></ul>
	</div>
</div>
<form method="post" action="index.php?form=Project{@$action|ucfirst}" id="projectAddForm">
	<div class="tabMenu">
		<ul>
			<li id="general"><a onclick="tabMenu.showSubTabMenu('general');"><span>{lang}ict.acp.project.general{/lang}</span></a></li>
			<li id="developers"><a onclick="tabMenu.showSubTabMenu('developers');"><span>{lang}ict.acp.project.developer{/lang}</span></a></li>
			<li id="access"><a onclick="tabMenu.showSubTabMenu('access');"><span>{lang}ict.acp.project.access{/lang}</span></a></li>
			{if $additionalTabs|isset}{@$additionalTabs}{/if}
		</ul>
	</div>
	<div class="subTabMenu">
		<div class="containerHead"><div> </div></div>
	</div>
	
	<div class="border tabMenuContent hidden" id="general-content">
		<div class="container-1">
			<h3 class="subHeadline">{lang}ict.acp.project.general{/lang}</h3>
			
			<div id="titleDiv" class="formElement{if $errorField == 'title'} formError{/if}">
				<div class="formFieldLabel">
					<label for="title">{lang}ict.acp.project.title{/lang}</label>
				</div>
				<div class="formField">
					<input type="text" class="inputText" id="title" name="title" value="{$title}" />
					{if $errorField == 'title'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notUnique'}{lang}ict.acp.project.error.title.notUnique{/lang}{/if}
						</p>
					{/if}
				</div>
				<div class="formFieldDesc hidden" id="titleHelpMessage">
					{lang}ict.acp.project.title.description{/lang}
				</div>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				inlineHelp.register('title');
				//]]>
			</script>

			<div class="formElement{if $errorField == 'ownerID'} formError{/if}" id="ownerIDDiv">
				<div class="formFieldLabel">
					<label for="ownerID">{lang}ict.acp.project.owner{/lang}</label>
				</div>
				<div class="formField">
					<select name="ownerID" id="ownerID">
						<option value="0">&nbsp;</option>
					</select>
					{if $errorField == 'ownerID'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						</p>
					{/if}
				</div>
				<div class="formFieldDesc hidden" id="ownerIDHelpMessage">
					<p>{lang}ict.acp.project.ownerID.description{/lang}</p>
				</div>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				inlineHelp.register('ownerID');
				var activeOwnerID = {if $ownerID|isset && $ownerID > 0}{@$ownerID}{else}null{/if};
				//]]>
			</script>

			<div id="descriptionDiv" class="formElement">
				<div class="formFieldLabel">
					<label for="description">{lang}ict.acp.project.description{/lang}</label>
				</div>
				<div class="formField">
					<textarea id="description" name="description" cols="40" rows="10">{$description}</textarea>
				</div>
				<div class="formFieldDesc hidden" id="descriptionHelpMessage">
					{lang}ict.acp.project.description.description{/lang}
				</div>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				inlineHelp.register('description');
				//]]>
			</script>

			<div id="imageDiv" class="formElement">
				<div class="formFieldLabel">
					<label for="image">{lang}ict.acp.project.image{/lang}</label>
				</div>
				<div class="formField">
					<input type="text" class="inputText" id="image" name="image" value="{$image}" />
				</div>
				<div class="formFieldDesc hidden" id="imageHelpMessage">
					{lang}ict.acp.project.image.description{/lang}
				</div>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				inlineHelp.register('image');
				//]]>
			</script>

			<div id="showOrderDiv" class="formElement">
				<div class="formFieldLabel">
					<label for="showOrder">{lang}ict.acp.project.showOrder{/lang}</label>
				</div>
				<div class="formField">
					<input type="text" class="inputText" id="showOrder" name="showOrder" value="{$showOrder}" />
				</div>
				<div class="formFieldDesc hidden" id="showOrderHelpMessage">
					{lang}ict.acp.project.showOrder.description{/lang}
				</div>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				inlineHelp.register('showOrder');
				//]]>
			</script>

			{if $additionalGeneralFields|isset}{@$additionalGeneralFields}{/if}
		</div>
	</div>
	
	<div class="border tabMenuContent hidden accessRightsContent" id="developers-content">
		<div class="container-1">
			<h3 class="subHeadline">{lang}ict.acp.project.developer{/lang}</h3>
			
			<div class="formElement">
				<div class="formFieldLabel" id="developerEntitiesTitle">
					{lang}ict.acp.project.developerEntities.title{/lang}
				</div>
				<div class="formField"><div id="developerEntities" class="accessRights container-4"></div></div>
			</div>
			<div class="formElement">
				<div class="formField">	
					<input id="developerEntitiesAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
					<div id="developerEntitiesAddInputSuggestions" class="pageMenu popupMenu"></div>
					<input id="developerEntitiesAddButton" type="button" value="{lang}ict.acp.project.developerEntities.add{/lang}" />
				</div>
			</div>
			
			{if $additionalDeveloperFields|isset}{@$additionalDeveloperFields}{/if}
		</div>
	</div>
	
	<div class="border tabMenuContent hidden accessRightsContent" id="access-content">
		<div class="container-1">
			<h3 class="subHeadline">{lang}ict.acp.project.access{/lang}</h3>
			
			<div class="formElement">
				<div class="formFieldLabel" id="developerTitle">
					{lang}ict.acp.project.accessEntities.title{/lang}
				</div>
				<div class="formField"><div id="accessEntities" class="accessRights container-4"></div></div>
			</div>
			<div class="formElement">
				<div class="formField">	
					<input id="accessEntitiesAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
					<div id="accessEntitiesAddInputSuggestions" class="pageMenu popupMenu"></div>
					<input id="accessEntitiesAddButton" type="button" value="{lang}ict.acp.project.accessEntities.add{/lang}" />
				</div>
			</div>
			
			{if $additionalDeveloperFields|isset}{@$additionalDeveloperFields}{/if}
		</div>
	</div>

	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		{if $projectID|isset}<input type="hidden" name="projectID" value="{@$projectID}" />{/if}
 		<input type="hidden" id="activeTabMenuItem" name="activeTabMenuItem" value="{$activeTabMenuItem}" />
 	</div>
</form>

{include file='footer'}