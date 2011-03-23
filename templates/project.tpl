{include file="documentHeader"}
<head>
	<title>{lang}{$project->title}{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
</head>
<body id="tplProject">
{include file='header' sandbox=false}

<div id="main">
	{capture assign=projectDescription}{lang}{$project->description}{/lang}{/capture}
	<div class="mainHeadline">
		<img src="{icon}projectL.png{/icon}" alt="" title="" />
		<div class="headlineContainer">
			<h2>{lang}{$project->title}{/lang}</h2>
			{if !$projectDescription|empty}<p>{lang}{$projectDescription}{/lang}</p>{/if}
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="contentHeader">
		{if $additionalLargeButtons|isset || $project->getPermission('canCreateBug') || $project->getPermission('canCreateFeatureRequest') || $project->getPermission('canCreateTask')}
			<div class="largeButtons">
				<ul>
					{if $project->getPermission('canCreateBug')}<li><a href="index.php?form=IssueAdd&amp;issueType=bug&amp;projectID={@$projectID}" title="{lang}ict.project.issue.bug.add{/lang}"><img src="{icon}bugAddM.png{/icon}" alt="{lang}ict.project.issue.bug.add{/lang}" /> <span>{lang}ict.project.issue.bug.add{/lang}</span></a></li>{/if}
					{if $project->getPermission('canCreateFeatureRequest')}<li><a href="index.php?form=IssueAdd&amp;issueType=featureRequest&amp;projectID={@$projectID}" title="{lang}ict.project.issue.featureRequest.add{/lang}"><img src="{icon}featureRequestAddM.png{/icon}" alt="{lang}ict.project.issue.featureRequest.add{/lang}" /> <span>{lang}ict.project.issue.featureRequest.add{/lang}</span></a></li>{/if}
					{if $project->getPermission('canCreateTask')}<li><a href="index.php?form=IssueAdd&amp;issueType=task&amp;projectID={@$projectID}" title="{lang}ict.project.issue.task.add{/lang}"><img src="{icon}taskAddM.png{/icon}" alt="{lang}ict.project.issue.task.add{/lang}" /> <span>{lang}ict.project.issue.task.add{/lang}</span></a></li>{/if}
					{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
				</ul>
			</div>
		{/if}
	</div>

</div>

{include file='footer' sandbox=false}

</body>
</html>
