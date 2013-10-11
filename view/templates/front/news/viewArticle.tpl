{include 'front/header.tpl'}

<div class="container" style="margin-top: 20px">

    <div class="row">
        <div class="col-md-3">
            <h1>{$latestNews}</h1>
            {$latest}
        </div>
        <div class="col-md-9">
            <article>
                <header>
                    <h1>{$title}</h1>
                    <p class="muted"><small><em>{$created}</em></small></p>
                </header>
                <section>
                    {$content}
                </section>
                <footer>
                    <p class="muted"><small><em>{$keywords}</em></small></p>
                </footer>
            </article>
        </div>
    </div>

</div>

{include 'front/footer.tpl'}