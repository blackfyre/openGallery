{include 'throne/header.tpl'}
<h3>{$moduleTitle}</h3>


    <div class="row">
        <div class="col-md-2">
            {$control}
        </div>
        <div class="{if isset($info)}col-md-8{else}col-md-10{/if}">
            {$content}
        </div>
        {if isset($info)}
        <div class="col-md-2">
            {$info}
        </div>
        {/if}
    </div>



{include 'throne/footer.tpl'}