{include 'throne/header.tpl'}
<h3>{$moduleTitle}</h3>

<div class="row">
    <div class="col-md-2" role="navigation">
        <div class="well sidebar-nav">
            <ul class="nav">
                <li>Index</li>

                {foreach from=$index item=i}
                    <li{if $i.active eq 1}  class="active"{/if}><a href="{$i.link}">{$i.title}</a></li>
                {/foreach}

            </ul>
        </div><!--/.well -->
    </div><!--/span-->
    <div class="col-md-10">{$content}</div>
</div>


{include 'throne/footer.tpl'}