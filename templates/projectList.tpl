{if $projects|count > 0}
	{cycle name='projectListCycle' values='1,2' advance=false print=false}
	<ul id="projectList">
		{foreach from=$projects item=project}
			{assign var="projectID" value=$project->projectID}
			{capture assign=projectDescription}{lang}{$project->description}{/lang}{/capture}
			<li class="border">
				<div class="container-{cycle name='projectListCycle'} projectListInner project{@$projectID}">
					<div class="containerIcon">
						<a href="index.php?page=Project&amp;projectID={@$projectID}{@SID_ARG_2ND}">
							<img alt="{lang}{$project->title}{/lang}" src="{icon}{if $project->image|empty}projectXL.png{else}{/if}$project->image{/icon}" />
						</a>
					</div>
					<div class="containerContent">
						<h4>
							<a href="index.php?page=Project&amp;projectID=2{@SID_ARG_2ND}">{lang}{$project->title}{/lang}</a>
						</h4>
						<p class="projectOwner"><strong>{lang}it.project.owner{/lang}:</strong> {$project->getOwner()->username}</p>
						{if !$projectDescription|empty}<p class="projectDescription"><strong>{lang}it.project.description{/lang}:</strong> {@$projectDescription}</p>{/if}
					</div>
				</div>
			</li>
		{/foreach}
	</ul>
{/if}
