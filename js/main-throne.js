/* Refreshers */

function refreshLangTable() {
    $.ajax({
        url: '/responders/refreshLangTable.php',
        success: function(retData) {
            $('.langtable').fadeOut(500).replaceWith(retData).fadeIn(500);
        },
        error: function () {
            alert('Hiba történt a kiszolgálóval való kommunikáció közben!');
        }
    });
}

function refreshNewsArticleTable() {
    $.ajax({
        url: '/responders/newsResponder.php?action=getNewTable',
        success: function(retData) {
            $('.newstable').fadeOut(500).replaceWith(retData).fadeIn(500);
        },
        error: function () {
            alert('Hiba történt a kiszolgálóval való kommunikáció közben!');
        }
    });
}

function refreshMenuTable() {

    location.reload();
}

/*
Actions
 */

function deactivateLang(langId) {
    $.ajax({
        url: '/responders/modLang.php?lid=' + langId + '&action=deactivate',
        success: function() {
            refreshLangTable();
        },
        error: function () {
            alert('Hiba történt a kiszolgálóval való kommunikáció közben!');
        }
    });
}

function activateLang(langId) {
    $.ajax({
        url: '/responders/modLang.php?lid=' + langId + '&action=activate',
        success: function() {
            refreshLangTable();
        },
        error: function () {
            alert('Hiba történt a kiszolgálóval való kommunikáció közben!');
        }
    });
}
function publishArticle(articleId) {
    $.ajax({
        url: '/responders/newsResponder.php?articleId=' + articleId + '&action=publish',
        success: function(data) {
            $('.jMsg').html(data);
            refreshNewsArticleTable();
        },
        error: function () {
            alert('Hiba történt a kiszolgálóval való kommunikáció közben!');
        }
    });
}

function unpublishArticle(articleId) {
    $.ajax({
        url: '/responders/newsResponder.php?articleId=' + articleId + '&action=unpublish',
        success: function(data) {
            $('.jMsg').html(data);
            refreshNewsArticleTable();
        },
        error: function () {
            alert('Hiba történt a kiszolgálóval való kommunikáció közben!');
        }
    });
}


function setMenu(action,menuId) {
    $.ajax({
        url: '/responders/setMenu.php?action=' + action + '&id=' + menuId,
        success: function() {
            refreshMenuTable();
        },
        error: function () {
            alert('Hiba történt a kiszolgálóval való kommunikáció közben!');
        }
    });
}

function addMenuElement(lang,position) {
    var target = $('#modalBox');
    target.find('#myModalLabel').html('Új átirányítás menü hozzáadása a menühöz');
    target.find('.modal-body').empty().load('/responders/loadMenuForm.php?menu=redirect&lang='+lang+'&pos='+position);
    target.modal('show');
}

function addMenuArticle(lang,position) {
    var target = $('#modalBox');
    target.find('#myModalLabel').html('Új cikk hozzáadása a menühöz');
    target.find('.modal-body').empty().load('/responders/loadMenuForm.php?menu=article&lang='+lang+'&pos='+position);
    target.modal('show');
}

var addLangOptions = {
    success: refreshLangTable,
    clearForm: true
};
/*
$('#form-addlang').ajaxForm(addLangOptions);
*/


function menuListener(modalToClose) {
    var modal = $(modalToClose);

    var formSelector = $('form[data-async]');

    formSelector.unbind('submit');

    formSelector.on('submit', function(event) {

        var $form = $(this);

        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),

            success: function(data, status) {
                modal.modal('hide');
                formSelector.unbind('submit');
                refreshMenuTable(data);
            }
        });

        event.preventDefault();
    });
}

/*
Figyelők
 */

$('input[id^=date]').datepicker({ dateFormat: "yy-mm-dd" });

$('#saveMenu').on('click', function() {
    menuListener('#modalBox');
});

$('.ckeditor').ckeditor();


