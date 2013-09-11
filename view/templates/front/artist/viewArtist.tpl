{include 'front/header.tpl'}

<div class="jumbotron{if isset($headerImg)} shadowedJumbo{/if}" {if isset($headerImg)}style="background-image: url(/uploads/{$headerImg});background-size: 100% auto;"{/if}>
    <div class="container">
        <h1 class="artista">{$artistName} <small>{$subTitle}</small></h1>
        <div class="artista">{$excerpt}</div>
        <p><a href="{$artworkLink}" class="btn btn-primary btn-lg">{$artworkButton} &raquo;</a></p>
    </div>
</div>

<div class="container">

    <div class="row">
        <div class="col-md-6">
            <h3>{$bioTitle}</h3>
            {$bio}
        </div>
        <div class="col-md-6">
            <h3>{$artworkButton}{if $showMore} <a href="{$artworkLink}" class="btn btn-primary">{$moreButton} &raquo;</a>{/if}</h3>
            {foreach from=$artData item=i}
                <div class="media">
                    <a class="pull-left" href="{$i.link}">
                        {*
                        <img style="max-width: 64px; max-height: 64px" class="media-object" src="/image.php?width=64&height=64&cropratio=1:1&image=/img/art/{$i.img}" alt="{$i.title}">
                        *}
                        <img style="max-width: 64px; max-height: 64px" class="media-object" src="/images/small-thumbnail/{$i.artId}/{$i.slug}" alt="{$i.title}">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading">{$i.title}</h4>
                        {$i.description}
                    </div>
                </div>
            {/foreach}

        </div>
    </div>



</div>

{include 'front/footer.tpl'}