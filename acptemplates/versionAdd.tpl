{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_ICT_DIR}icon/version{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}ict.acp.project.version.{@$action}{/lang}</h2>
		<p>{lang}{$project->title}{/lang}{if $versionID|isset} - {$version->version}{/if}</p>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}ict.acp.project.version.{@$action}.success{/lang}</p>
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=ProjectView&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}ict.acp.project.view{/lang}"><img src="{@RELATIVE_ICT_DIR}icon/projectM.png" alt="" /> <span>{lang}ict.acp.project.view{/lang}</span></a></li></ul>
	</div>
</div>
<form method="post" action="index.php?form=Version{@$action|ucfirst}">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}ict.acp.project.version.general{/lang}</legend>
				<div id="versionnameDiv" class="formElement{if $errorField == 'versionname'} formError{/if}">
					<div class="formFieldLabel">
						<label for="versionname">{lang}ict.acp.project.version{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="versionname" name="versionname" value="{$versionname}" />
						{if $errorField == 'versionname'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'notUnique'}{lang}ict.acp.project.version.error.versionname.notUnique{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="versionnameHelpMessage">
						{lang}ict.acp.project.version.description{/lang}
					</div>
				</div>
				<script type="text/javascript">
					//<![CDATA[
					inlineHelp.register('versionname');
					//]]>
				</script>
			</fieldset>

			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>

	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		{if $versionID|isset}<input type="hidden" name="versionID" value="{@$versionID}" />{/if}
 		<input type="hidden" name="projectID" value="{@$project->projectID}" />
 	</div>
</form>

{include file='footer'}