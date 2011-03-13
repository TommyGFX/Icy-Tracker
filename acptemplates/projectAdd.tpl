{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabMenu.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	var tabMenu = new TabMenu();
	onloadEvents.push(function() { tabMenu.showSubTabMenu("{$activeTabMenuItem}") });
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

<script type="text/javascript">
	//<![CDATA[
	onloadEvents.push(function() {
		// add onsubmit event
		document.forms[0].onsubmit = function() {
			if (suggestion.selectedIndex != -1) return false;
		};
	});
	//]]>
</script>

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=ProjectList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}ict.acp.menu.link.content.project.view{/lang}"><img src="{@RELATIVE_ICT_DIR}icon/projectM.png" alt="" /> <span>{lang}ict.acp.menu.link.content.project.view{/lang}</span></a></li></ul>
	</div>
</div>
<form method="post" action="index.php?form=Project{@$action|ucfirst}">
	<div class="tabMenu">
		<ul>
			<li id="general"><a onclick="tabMenu.showSubTabMenu('general');"><span>{lang}ict.acp.project.general{/lang}</span></a></li>
			<li id="permissions"><a onclick="tabMenu.showSubTabMenu('permissions');"><span>{lang}ict.acp.project.developer{/lang}</span></a></li>
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

			<div id="ownernameDiv" class="formElement{if $errorField == 'ownername'} formError{/if}">
				<div class="formFieldLabel">
					<label for="ownername">{lang}ict.acp.project.ownername{/lang}</label>
				</div>
				<div class="formField">
					<input type="text" class="inputText" id="ownername" name="ownername" value="{$ownername}" />
					<script type="text/javascript">
						//<![CDATA[
						suggestion.setSource('index.php?page=UserSuggest{@SID_ARG_2ND_NOT_ENCODED}');
						suggestion.enableIcon(true);
						suggestion.init('ownername');
						//]]>
					</script>
					{if $errorField == 'ownername'}
						<p class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notValid'}{lang username=$ownername}wcf.user.error.username.notFound{/lang}{/if}
						</p>
					{/if}
				</div>
				<div class="formFieldDesc hidden" id="ownernameHelpMessage">
					{lang}ict.acp.project.ownername.description{/lang}
				</div>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				inlineHelp.register('ownername');
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
	
	<div class="border tabMenuContent hidden" id="permissions-content">
		<div class="container-1">
			<h3 class="subHeadline">{lang}ict.acp.project.developer{/lang}</h3>
			
			<div class="formElement">
				<div class="formFieldLabel" id="developerTitle">
					{lang}ict.acp.project.developer.title{/lang}
				</div>
				<div class="formField"><div id="developer" class="accessRights"></div></div>
			</div>
			<div class="formElement">
				<div class="formField">	
					<input id="developerAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
					<script type="text/javascript">
						//<![CDATA[
						suggestion.enableIcon(true);
						suggestion.init('developerAddInput');
						//]]>
					</script>
					<input id="developerAddButton" type="button" value="{lang}ict.acp.project.developer.add{/lang}" />
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