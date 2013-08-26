{include 'throne/header.tpl'}


<div class="row">
    <div class="span9 offset3">
        <h3>Edit email template</h3>
    </div>
</div>

{$msg}

<div class="row">
    <div class="span3">
        <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <li class="nav-header">Navigation</li>

                <li><a href="/throne/content/listEmailTemplates.html">Back</a></li>

            </ul>
        </div><!--/.well -->
        <div class="well">
            <h3 class="nav-header">Placeholders</h3>
            <dl>
                <dt>[userName]</dt>
                <dd>The name given by the user.</dd>
                <dt>[email]</dt>
                <dd>The email address given by the user</dd>
                <dt>[quizName]</dt>
                <dd>The title of the quiz</dd>
                <dt>[prize]</dt>
                <dd>The prize of the quiz</dd>
            </dl>
        </div>
    </div>
    <div class="span9">{$content}</div>
</div>

{include 'throne/footer.tpl'}