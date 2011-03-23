{if $this->request->form == 'IssueAddForm' || $this->user->userID == $project->ownerID || $project->getDeveloperPermission('canHideIssue')}
	<div class="formField">
		<label><input type="checkbox" name="hidden" value="1" {if $hidden == 1}checked="checked" {/if}/> {lang}ict.project.issue.hide{/lang}</label>
	</div>
	<div class="formFieldDesc">
		<p>{lang}ict.project.issue.hide.description{/lang}</p>
	</div>
{/if}