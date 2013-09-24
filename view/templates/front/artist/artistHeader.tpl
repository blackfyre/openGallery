<div class="jumbotron">
    <div class="container">

        {if isset($bioImg) AND isset($bioImgTitle)}

            <div class="row">
                <div class="col-md-3">
                    <img src="/uploads/{$bioImg}" title="{$bioImgTitle}" class="img-responsive">
                </div>
                <div class="col-md-9">
                    <h1 class="artista">{$artistName} <br><small>{$subTitle}</small></h1>
                    <div class="artista">{$excerpt}</div>
                    <p class="btn-group">
                        {if isset($bioLink) AND isset($bioButton)}
                            <a href="{$bioLink}" class="btn btn-primary btn-lg">{$bioButton} &raquo;</a>
                        {/if}
                        {if isset($artworkLink) AND isset($artworkButton)}
                            <a href="{$artworkLink}" class="btn btn-primary btn-lg">{$artworkButton} &raquo;</a>
                        {/if}
                    </p>
                </div>
            </div>

            {else}
            <h1 class="artista">{$artistName} <small>{$subTitle}</small></h1>
            <div class="artista">{$excerpt}</div>
            <p class="btn-group">
                {if isset($bioLink) AND isset($bioButton)}
                    <a href="{$bioLink}" class="btn btn-primary btn-lg">{$bioButton} &raquo;</a>
                {/if}
                {if isset($artworkLink) AND isset($artworkButton)}
                    <a href="{$artworkLink}" class="btn btn-primary btn-lg">{$artworkButton} &raquo;</a>
                {/if}
            </p>
        {/if}


    </div>
</div>