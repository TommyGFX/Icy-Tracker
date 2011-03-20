{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ItemListEditor.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	function init() {
		{if $projects|count > 0 && $projects|count < 50 && $this->user->getPermission('admin.project.canEditProject')}
			new ItemListEditor('projectList', { itemTitleEdit: false, tree: true, treeTag: 'ol' });
		{/if}
	}
	
	// when the dom is fully loaded, execute these scripts
	document.observe("dom:loaded", init);	
	//]]>
</script>
<div class="mainHeadline">
	<img src="{@RELATIVE_ICT_DIR}icon/projectL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}ict.acp.project.list{/lang}</h2>
	</div>
</div>

{if $deletedProjectID}
	<p class="success">{lang}ict.acp.project.delete.success{/lang}</p>	
{/if}

{if $this->user->getPermission('admin.project.canAddProject')}
	<div class="contentHeader">
		<div class="largeButtons">
			<ul><li><a href="index.php?form=ProjectAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}ict.acp.project.add{/lang}"><img src="{@RELATIVE_ICT_DIR}icon/projectAddM.png" alt="" /> <span>{lang}ict.acp.project.add{/lang}</span></a></li></ul>
		</div>
	</div>
{/if}

{if $projects|count > 0}
	<form method="post" action="index.php?action=ProjectSort">
		<div class="border content">
			<div class="container-1">
				<ol class="itemList" id="projectList">
					{foreach from=$projects item=child}
						{assign var="project" value=$child.project}
						
						<li id="item_{@$project->projectID}" class="deletable">
							<div class="buttons">
								{if $this->user->getPermission('admin.project.canEditProject')}
									<a href="index.php?form=ProjectEdit&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}ict.acp.project.edit{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}ict.acp.project.edit{/lang}" />
								{/if}
								{if $this->user->getPermission('admin.project.canDeleteProject')}
									<a class="deleteButton" title="{lang}ict.acp.project.delete{/lang}" href="index.php?action=ProjectDelete&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" longdesc="{lang}ict.acp.project.delete.sure{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}ict.acp.project.delete{/lang}" />
								{/if}
								
								{if $child.additionalButtons|isset}{@$child.additionalButtons}{/if}
							</div>
							<h3 class="itemListTitle">
								<img src="{@RELATIVE_ICT_DIR}icon/projectS.png" alt="" title="{lang}ict.acp.project{/lang}" />	
							
								{if $this->user->getPermission('admin.project.canEditProject')}
									<select name="projectListPositions[{@$project->projectID}][0]">
										{section name='showOrder' loop=$child.maxShowOrder}
											<option value="{@$showOrder+1}"{if $showOrder+1 == $child.showOrder} selected="selected"{/if}>{@$showOrder+1}</option>
										{/section}
									</select>
								{/if}
								
								{if $this->user->getPermission('admin.project.canEditProject') || $this->user->getPermission('admin.project.canDeleteProject') || $this->user->getPermission('admin.project.canAddVersion') || $this->user->getPermission('admin.project.canEditVersion') || $this->user->getPermission('admin.project.canDeleteVersion')}
									ID-{@$project->projectID} <a href="index.php?page=ProjectView&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}{$project->title}{/lang}</a>
								{else}
									ID-{@$project->projectID} {lang}{$project->title}{/lang}
								{/if}
							</h3>
						</li>
					{/foreach}
				</ol>
			</div>
		</div>
		
		<div class="formSubmit">
			<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
			<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
			{@SID_INPUT_TAG}
		</div>
	</form>
{/if}

{include file='footer'}
