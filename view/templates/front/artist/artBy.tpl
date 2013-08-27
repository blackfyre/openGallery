{include 'front/header.tpl'}

<div class="jumbotron" style="background-image: url(/img/art/142a41af4abc885e1c9f08274287f4024aee8605.jpg); color: #ffffff; background-position: top center">
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