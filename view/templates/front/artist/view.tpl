{include 'front/header.tpl'}

<div class="jumbotron" style="background-image: url(/img/art/142a41af4abc885e1c9f08274287f4024aee8605.jpg); color: #ffffff">
    <div class="container">
        <h1 class="artista">{$artistName} <small>{$subTitle}</small></h1>
        <p class="artista">Artist bio excerpt</p>
        <p><a class="btn btn-primary btn-lg">{$artworkButton} &raquo;</a></p>
    </div>
</div>

<div class="container">

    <div class="row">
        <div class="col-md-6">
            <h3>Bio</h3>
            {$bio}
        </div>
        <div class="col-md-6">
            <h3>Works</h3>
            {foreach from=$artData item=i}
                <div class="media">
                    <a class="pull-left" href="#">
                        <img style="max-width: 64px; max-height: 64px" class="media-object" src="/img/art/{$i.img}" alt="{$i.title_en}">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading">{$i.title_en}</h4>
                        {$i.description_en}
                    </div>
                </div>
            {/foreach}

        </div>
    </div>



</div>

{include 'front/footer.tpl'}