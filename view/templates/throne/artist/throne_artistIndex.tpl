{include 'throne/header.tpl'}
<h3>{$moduleTitle}</h3>

<div class="row">
    <div class="col-md-2" role="navigation">

        <div class="well">
            <ul class="list-inline">
                {foreach from=$index item=i}
                    <li><a {if $i.active eq 1}  class="active"{/if} href="{$i.link}">{$i.title}</a></li>
                {/foreach}
            </ul>
        </div>

    </div><!--/span-->
    <div class="col-md-10">{$content}</div>
</div>


{include 'throne/footer.tpl'}