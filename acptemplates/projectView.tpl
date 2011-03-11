{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

<div class="mainHeadline">
	<img src="{@RELATIVE_IT_DIR}icon/projectL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}{$project->title}{/lang}</h2>
		<p>{lang}{$project->description}{/lang}</p> {* TODO: Leave this here? or move it to details? (beacause of long descriptions) *} 
	</div>
</div>

<fieldset>
	<legend>{lang}it.acp.project.details{/lang}</legend>
	<div class="formElement">
		<p class="formFieldLabel">{lang}it.acp.project.ownername{/lang}</p>
		<p class="formField">{$project->getOwner()->username}</p>
	</div>
	
	{if $additionalFields|isset}{@$additionalFields}{/if}
</fieldset>

{if $actionVersionID}
	<p class="success">{lang}it.acp.project.version.{$actionType}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=ProjectView&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
	
	<div class="largeButtons">
		<ul><li><a href="index.php?page=ProjectList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}it.acp.menu.link.content.project.view{/lang}"><img src="{@RELATIVE_IT_DIR}icon/projectM.png" alt="" /> <span>{lang}it.acp.menu.link.content.project.view{/lang}</span></a></li></ul>
		{if $this->user->getPermission('admin.project.canDeleteProject')}<ul><li><a onclick="return confirm('{lang}it.acp.project.delete.sure{/lang}')" href="index.php?action=ProjectDelete&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_IT_DIR}icon/projectDeleteM.png" alt="" title="{lang}it.acp.project.delete{/lang}" /> <span>{lang}it.acp.project.delete{/lang}</span></a></li></ul>{/if}
		{if $this->user->getPermission('admin.project.canEditProject')}<ul><li><a href="index.php?form=ProjectEdit&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_IT_DIR}icon/projectEditM.png" alt="" title="{lang}it.acp.project.edit{/lang}" /> <span>{lang}it.acp.project.edit{/lang}</span></a></li></ul>{/if}
		{if $this->user->getPermission('admin.project.canAddVersion')}<ul><li><a href="index.php?form=VersionAdd&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_IT_DIR}icon/versionAddM.png" alt="" title="{lang}it.acp.project.version.add{/lang}" /> <span>{lang}it.acp.project.version.add{/lang}</span></a></li></ul>{/if}
		{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
	</div>
</div>

{if $versions|count}
	<div class="border titleBarPanel">
		<div class="containerHead"><h3>{lang}it.acp.project.version.view.count{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th class="columnVersionID{if $sortField == 'versionID'} active{/if}" colspan="2"><div><a href="index.php?page=ProjectView&amp;pageNo={@$pageNo}&amp;sortField=versionID&amp;sortOrder={if $sortField == 'versionID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}it.acp.project.version.versionID{/lang}{if $sortField == 'versionID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnVersion{if $sortField == 'version'} active{/if}"><div><a href="index.php?page=ProjectView&amp;pageNo={@$pageNo}&amp;sortField=version&amp;sortOrder={if $sortField == 'version' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}it.acp.project.version{/lang}{if $sortField == 'version'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					
					{if $additionalColumns|isset}{@$additionalColumns}{/if}
				</tr>
			</thead>
			<tbody>
			{foreach from=$versions item=version}
				<tr class="{cycle values="container-1,container-2"}">
					<td class="columnIcon">
						{if $this->user->getPermission('admin.project.canEditVersion')}
							{if $version->published}
								<a href="index.php?action=VersionUnpublish&amp;versionID={@$version->versionID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" title="{lang}it.acp.project.version.unpublish{/lang}" /></a>
							{else}
								<a href="index.php?action=VersionPublish&amp;versionID={@$version->versionID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" title="{lang}it.acp.project.version.publish{/lang}" /></a>
							{/if}
							
							<a href="index.php?form=VersionEdit&amp;versionID={@$version->versionID}&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}it.acp.project.version.edit{/lang}" /></a>
						{else}
							{if $version->published}
								<img src="{@RELATIVE_WCF_DIR}icon/enabledDisabledS.png" alt="" title="{lang}it.acp.project.version.unpublish{/lang}" />
							{else}
								<img src="{@RELATIVE_WCF_DIR}icon/disabledDisabledS.png" alt="" title="{lang}it.acp.project.version.publish{/lang}" />
							{/if}
							
							<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}it.acp.project.version.edit{/lang}" />
						{/if}
						{if $this->user->getPermission('admin.project.canDeleteVersion')}
							<a onclick="return confirm('{lang}it.acp.project.version.delete.sure{/lang}')" href="index.php?action=VersionDelete&amp;versionID={@$version->versionID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}it.acp.project.version.delete{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}it.acp.project.version.delete{/lang}" />
						{/if}
						
						{if $version->additionalButtons|isset}{@$version->additionalButtons}{/if}
					</td>
					<td class="columnVersionID columnID">{@$version->versionID}</td>
					<td class="columnVersion columnText">
						{if $this->user->getPermission('admin.project.canEditVersion')}
							<a href="index.php?form=VersionEdit&amp;versionID={@$version->versionID}&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$version->version}</a>
						{else}
							{$version->version}
						{/if}
					</td>
					
					{if $version->additionalColumns|isset}{@$version->additionalColumns}{/if}
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>

	<div class="contentFooter">
		{@$pagesLinks}
		
		<div class="largeButtons">
			<ul><li><a href="index.php?page=ProjectList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}it.acp.menu.link.content.project.view{/lang}"><img src="{@RELATIVE_IT_DIR}icon/projectM.png" alt="" /> <span>{lang}it.acp.menu.link.content.project.view{/lang}</span></a></li></ul>
			{if $this->user->getPermission('admin.project.canDeleteProject')}<ul><li><a onclick="return confirm('{lang}it.acp.project.delete.sure{/lang}')" href="index.php?action=ProjectDelete&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_IT_DIR}icon/projectDeleteM.png" alt="" title="{lang}it.acp.project.delete{/lang}" /> <span>{lang}it.acp.project.delete{/lang}</span></a></li></ul>{/if}
			{if $this->user->getPermission('admin.project.canEditProject')}<ul><li><a href="index.php?form=ProjectEdit&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_IT_DIR}icon/projectEditM.png" alt="" title="{lang}it.acp.project.edit{/lang}" /> <span>{lang}it.acp.project.edit{/lang}</span></a></li></ul>{/if}
			{if $this->user->getPermission('admin.project.canAddVersion')}<ul><li><a href="index.php?form=VersionAdd&amp;projectID={@$project->projectID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_IT_DIR}icon/versionAddM.png" alt="" title="{lang}it.acp.project.version.add{/lang}" /> <span>{lang}it.acp.project.version.add{/lang}</span></a></li></ul>{/if}
			{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
		</div>
	</div>
{/if}

{include file='footer'}
