{include file="documentHeader"}
<head>
	<title>{lang}wcf.global.error.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>
<body id="tplOffline">
{include file='header' sandbox=false}

<div id="main">
	
	<div class="warning">
		{lang}ict.global.offline{/lang}
		<p>{if OFFLINE_MESSAGE_ALLOW_HTML}{@OFFLINE_MESSAGE}{else}{@OFFLINE_MESSAGE|htmlspecialchars|nl2br}{/if}</p>
	</div>

</div>

{include file='footer' sandbox=false}
</body>
</html>