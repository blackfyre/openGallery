{include 'front/header.tpl'}

<div class="jumbotron">
    <div class="container">
        <h1>{$positionTitle}</h1>
        <p>{$teaser}</p>
        <p><a href="mailto: galicz.miklos@blackworks.org" class="btn btn-primary btn-lg">{$signUpButton} &raquo;</a></p>
    </div>
</div>

<div class="container">
    {$description}
</div>

{include 'front/footer.tpl'}