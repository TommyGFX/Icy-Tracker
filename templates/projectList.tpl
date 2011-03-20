{if $projects|count > 0}
	{cycle name='projectListCycle' values='1,2' advance=false print=false}
	<ul id="projectList">
		{foreach from=$projects item=project}
			{assign var="projectID" value=$project->projectID}
			{capture assign=projectDescription}{lang}{$project->description}{/lang}{/capture}
			{capture assign=projectImage}{$project->image}{/capture}
			<li class="border">
				<div class="container-{cycle name='projectListCycle'} projectListInner project{@$projectID}">
					<div class="containerIcon">
						<a href="index.php?page=Project&amp;projectID={@$projectID}{@SID_ARG_2ND}">
							<img alt="{lang}{$project->title}{/lang}" src="{if $projectImage|empty}{icon}projectXL.png{/icon}{else}{@$projectImage}{/if}" />
						</a>
					</div>
					<div class="containerContent">
						<h4>
							<a href="index.php?page=Project&amp;projectID={@$projectID}{@SID_ARG_2ND}">{lang}{$project->title}{/lang}</a>
						</h4>
						<p class="projectOwner"><strong>{lang}ict.project.owner{/lang}:</strong> <a href="index.php?page=User&amp;userID={@$project->getOwner()->userID}{@SID_ARG_2ND}">{$project->getOwner()->username}</a></p>
						{if $project->getDeveloper()|count > 1}<p class="projectDeveloper"><strong>{lang}ict.project.developer{/lang}:</strong> {implode from=$project->getDeveloper() key=developerID item=developerName}<a href="index.php?page=User&amp;userID={@$developerID}{@SID_ARG_2ND}">{$developerName}</a>{/implode}</p>{/if}
						{if !$projectDescription|empty}<p class="projectDescription"><strong>{lang}ict.project.description{/lang}:</strong> {@$projectDescription}</p>{/if}
					</div>
				</div>
			</li>
		{/foreach}
	</ul>
{/if}
