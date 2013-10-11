{include 'throne/header.tpl'}


<div class="row">
    <div class="span9 offset3">
        <h3>Edit fixed content</h3>
    </div>
</div>

{$msg}

<div class="row">
    <div class="span3">
        <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <li class="nav-header">Navigation</li>

                <li><a href="/throne/content/fixedContent.html">Back</a></li>

            </ul>
        </div><!--/.well -->
    </div>
    <div class="span9">{$content}</div>
</div>

{include 'throne/footer.tpl'}