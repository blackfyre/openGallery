{include 'front/header.tpl'}

{include 'front/artist/artistHeader.tpl'}

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