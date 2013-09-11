{include 'front/header.tpl'}

<div class="jumbotron{if isset($headerImg)} shadowedJumbo{/if}" {if isset($headerImg)}style="background-image: url(/uploads/{$headerImg});background-size: 100% auto;"{/if}>
    <div class="container">
        <h1 class="artista">{$artistName} <small>{$subTitle}</small></h1>
        <div class="artista">{$excerpt}</div>
        <p>
            <a href="{$artworkLink}" class="btn btn-primary btn-lg">{$artworkButton} &raquo;</a>
            &nbsp;
            <a href="{$bioLink}" class="btn btn-primary btn-lg">{$bioButton} &raquo;</a>
        </p>
    </div>
</div>

<div class="container">

    <div class="row">
        <div class="col-md-6">
            <img src="{$artImg}" class="img-responsive">
        </div>
        <div class="col-md-6">
            <h1>{$artTitle}</h1>

            {if $artInfo != ''}
                {$artInfo}
            {/if}

        </div>
    </div>



</div>

{include 'front/footer.tpl'}