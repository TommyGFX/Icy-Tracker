{include file="documentHeader"}
<head>
	<title>{lang}ict.index.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
</head>
<body id="tplIndex">
{include file='header' sandbox=false}

<div id="main">
	
	<div class="mainHeadline">
		<img src="{icon}indexL.png{/icon}" alt="" title="" />
		<div class="headlineContainer">
			<h2>{lang}{PAGE_TITLE}{/lang}</h2>
			<p>{lang}{PAGE_DESCRIPTION}{/lang}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	{include file='projectList'}

</div>

{include file='footer' sandbox=false}

</body>
</html>
