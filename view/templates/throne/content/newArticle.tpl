{include 'throne/header.tpl'}
<div class="row">
    <div class="span9 col-md-offset-3">
        <h3>{$moduleTitle}</h3>
    </div>
</div>

{$msg}

<div class="row">
    <div class="col-md-2" role="navigation">
        <div class="well sidebar-nav">
            <ul class="nav">
                <li>{$navTitle}</li>

                <li><a href="/throne/content/articles.html">{$backLink}</a></li>

            </ul>
        </div><!--/.well -->
    </div>
    <div class="col-md-10">{$content}</div>
</div>

{include 'throne/footer.tpl'}