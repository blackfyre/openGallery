</div> <!-- /container -->


<script src="/js/vendor/jquery-1.10.1.js"></script>
<script src="/js/vendor/jquery-ui-1.10.3.custom.js"></script>

<script src="/_bootstrap3/dist/js/bootstrap.js"></script>
<script src="/plugins/form/jquery.form.js"></script>

<script src="/plugins/ckeditor/ckeditor.js"></script>

<script src="/js/plugins.js"></script>
<script src="/js/main-throne.js"></script>
<script src="/js/main-common.js"></script>

{if isset($jFunctions) OR isset($jQuery)}

<script type="text/javascript">
    {$jFunctions}

    {if isset($jQuery)}

    $(document).ready(function() {

        {$jQuery}

    });

    {/if}

</script>

{/if}

</body>
</html>