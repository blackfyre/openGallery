{include 'front/header.tpl'}


<div class="container">
<div class="row">

    {if isset($artistSearchTitle) AND isset($artSearchTitle)}
        <div class="col-md-6">
            <h1>{$artistSearchTitle}</h1>
            {$artistSearchForm}
        </div>
        <div class="col-md-6">
            <h1>{$artSearchTitle}</h1>
            {$artSearchForm}
        </div>
    {/if}

    {if isset($resultHeader)}
        <div class="col-md-3"><p id="queryTitle">{$queryTitle}</p>Search info placeholder</div>
        <div class="col-md-9"><h1>{$resultHeader}</h1>Search results placeholder</div>
    {/if}
</div>
</div>

{include 'front/footer.tpl'}