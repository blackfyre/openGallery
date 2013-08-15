{include 'throne/header.tpl'}

<h3>Fő menük</h3>

<div class="row">
    {$content}
</div>

<div id="modalBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="myModalLabel"></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Bezár</button>
        <button form="form-menuform" type="submit" class="btn btn-primary" id="saveMenu">Mentés</button>
    </div>
</div>

{include 'throne/footer.tpl'}