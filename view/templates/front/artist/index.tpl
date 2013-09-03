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
                <div class="jumbotron artistIndexElement" style="background-image: url(/img/art/{$i.background}); color: #ffffff; background-position: top center">
                    <h1 class="artista">{$i.name}<br><small>{$i.life}</small></h1>
                    <p class="artistIndexElementButton"><a href="{$i.link}" class="btn btn-primary" hreflang="{$smarty.session.lang}" >Bio</a> </p>
                </div>
            {/foreach}
        </div>

    </div>

</div>



{include 'front/footer.tpl'}