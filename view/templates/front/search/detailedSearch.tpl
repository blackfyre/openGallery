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
        <div class="col-md-3">
            <p id="queryTitle">{$queryTitle}</p>
            {$queryParam}
        </div>
        <div class="col-md-9">
            <h1>{$resultHeader}</h1>

            {foreach from=$result item=i}

                {if isset($i.bioImg) ANd isset($i.bioImgTitle)}

                    <div class="jumbotron artistIndexElement">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="/uploads/{$i.bioImg}" title="{$i.bioImgTitle}" class="img-responsive">
                            </div>
                            <div class="col-md-9">
                                <h1 class="artista">{$i.name}<br><small>{$i.life}</small></h1>
                                {if isset($i.excerpt)}<p class="artista">{$i.excerpt}</p>{/if}
                                <p class="artistIndexElementButton"><a href="{$i.link}" class="btn btn-primary" hreflang="{$smarty.session.lang}" >{$bioTitle}</a></p>
                            </div>
                        </div>
                    </div>

                {else}
                    <div class="jumbotron artistIndexElement">
                        <h1 class="artista">{$i.name}<br><small>{$i.life}</small></h1>
                        {if isset($i.excerpt)}<p class="artista">{$i.excerpt}</p>{/if}
                        <p class="artistIndexElementButton"><a href="{$i.link}" class="btn btn-primary" hreflang="{$smarty.session.lang}" >{$bioTitle}</a></p>
                    </div>
                {/if}

            {/foreach}

        </div>
    {/if}
</div>
</div>

{include 'front/footer.tpl'}