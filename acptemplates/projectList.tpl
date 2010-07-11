{include file='header'}
{* SVN-ID: $Id$ *}
<div class="mainHeadline">
	<img src="{@RELATIVE_IT_DIR}icon/projectL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}it.acp.project.list{/lang}</h2>
	</div>
</div>

{if $deletedProjectID}
	<p class="success">{lang}it.acp.project.delete.success{/lang}</p>	
{/if}

{if $this->user->getPermission('admin.project.canAddProject')}
	<div class="contentHeader">
		<div class="largeButtons">
			<ul><li><a href="index.php?form=ProjectAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}it.acp.project.add{/lang}"><img src="{@RELATIVE_IT_DIR}icon/projectAddM.png" alt="" /> <span>{lang}it.acp.project.add{/lang}</span></a></li></ul>
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
						
						<li>
							<div class="buttons">
								{if $this->user->getPermission('admin.project.canEditProject')}
									<a href="index.php?form=ProjectEdit&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}it.acp.project.edit{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}it.acp.project.edit{/lang}" />
								{/if}
								{if $this->user->getPermission('admin.project.canDeleteProject')}
									<a onclick="return confirm('{lang}it.acp.project.delete.sure{/lang}')" href="index.php?action=ProjectDelete&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}it.acp.project.delete{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}it.acp.project.delete{/lang}" />
								{/if}
								
								{if $child.additionalButtons|isset}{@$child.additionalButtons}{/if}
							</div>
							<h3 class="itemListTitle">
								<img src="{@RELATIVE_IT_DIR}icon/projectS.png" alt="" title="{lang}it.acp.project{/lang}" />	
							
								{if $this->user->getPermission('admin.project.canEditProject')}
									<select name="showOrder[{@$project->projectID}]">
										{section name='showOrder' loop=$child.maxShowOrder}
											<option value="{@$showOrder+1}"{if $showOrder+1 == $child.showOrder} selected="selected"{/if}>{@$showOrder+1}</option>
										{/section}
									</select>
								{/if}
								
								{if $this->user->getPermission('admin.project.canEditProject') || $this->user->getPermission('admin.project.canDeleteProject') || $this->user->getPermission('admin.project.canAddVersion') || $this->user->getPermission('admin.project.canEditVersion') || $this->user->getPermission('admin.project.canDeleteVersion')}
									ID-{@$project->projectID} <a href="index.php?form=ProjectView&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}{$project->title}{/lang}</a>
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
