{extends file="s3:layouts/layout.tpl"}
{block name=head}{/block}
{block name=htmlclasses}{{if $user}}logged_in{{/if}}{/block}
{block name=bodyclasses}{{if $user}}logged_in{{/if}}{/block}

{block name=body}
<div class="container">
		<div class="row">	
			<div class="col-xl-9 col-md-7">		
{$page->showArea('main', [
		'area_classes' => 'row', 
		'block_wrap' => [
			'before' => '<div class="col-md-12">', 
			'after' => '</div>' 
		]
	])}
	
{*
{foreach $page->blocks as $block}
{{$block}}
{{/foreach}}
*}
			</div>
			<div class="col-xl-3 col-md-5">
				{include "s3:parts/sidebar.tpl"}
			</div>
		</div> <!-- close .row -->
</div>

{/block}

{block name=footer_extras}
{*
{if edit_mode()}
{include 's3:parts/frontend_editor.tpl'}
{/if}
*}
{/block}