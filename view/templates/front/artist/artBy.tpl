{include 'front/header.tpl'}

<div class="jumbotron{if isset($headerImg)} shadowedJumbo{/if}" {if isset($headerImg)}style="background-image: url(/uploads/{$headerImg});background-size: 100% auto;"{/if}>
    <div class="container">
        <h1 class="artista">{$artistName} <small>{$subTitle}</small></h1>
        <div class="artista">{$excerpt}</div>
        <p><a href="{$biokLink}" class="btn btn-primary btn-lg">{$bioButton} &raquo;</a></p>
    </div>
</div>

<div class="container">

{$content}

</div>

{include 'front/footer.tpl'}