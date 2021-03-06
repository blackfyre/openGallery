{include 'front/header.tpl'}

<div class="container" style="margin-top: 20px">

    <div class="row">

        <div class="col-md-2">
            <div class="well">
                <ul class="list-inline">
                    {foreach from=$index item=i}
                        <li><a href="{$i.link}" hreflang="{$smarty.session.lang}">{$i.title}</a></li>
                    {/foreach}
                </ul>
            </div>
        </div>
        <div class="col-md-9 col-md-offset-1">
            {foreach from=$artists item=i}

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

    </div>

</div>



{include 'front/footer.tpl'}