{include 'front/header.tpl'}

{include 'front/artist/artistHeader.tpl'}

<div class="container">

    <div class="row">
        <div class="col-md-6">
            <h3>{$bioTitle}</h3>
            {$bio}
        </div>
        <div class="col-md-6">
            <h3>{$artworkButton}{if $showMore} <a href="{$artworkLink}" class="btn btn-primary">{$moreButton} &raquo;</a>{/if}</h3>
            {if isset($artSlide)}
                {$artSlide}
                {elseif isset($artData)}
                {foreach from=$artData item=i}
                    <div class="media">
                        <a class="pull-left" href="{$i.link}">
                            <img style="max-width: 64px; max-height: 64px" class="media-object" src="/images/small-thumbnail/{$i.artId}/{$i.slug}" alt="{$i.title}">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">{$i.title}</h4>
                            {$i.description}
                        </div>
                    </div>
                {/foreach}
            {/if}

        </div>
    </div>



</div>

{include 'front/footer.tpl'}