<table class="artistList">
    <thead>
    <tr>
        <th colspan="5">{$artistName}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th colspan="5">{$artistName}</th>
    </tr>
    </tfoot>
    <tbody>

    {foreach from=$artists item=i}

    <tr class="{if $i.active=='0'}inactiveRow{/if}">
        <td>
            <strong>
                {$i.artistFullName}
            </strong>
        </td>
        <td>
            {$i.dateOfBirth} - {$i.dateOfDeath}
        </td>
        <td class="activeState">
            <a href="/throne/artists/toggle-active/{$i.slug}.html">
                {if $i.active=='1'}
                    <img src="/img/icons/small.active.true.png" alt="{$artistActive}"/>
                    {elseif $i.active=='0'}
                    <img src="/img/icons/small.active.false.png" alt="{$artistInactive}"/>
                {/if}
            </a>
        </td>
        <td class="preview">
            <a href="/artist/{$i.slug}.html" hreflang="{$siteLang}" target="_blank" title="{$previewLinkTitle}">
                <img src="/img/icons/small.preview.png"/>
            </a>
        </td>
        <td class="edit">
            <a href="/throne/artist/{$i.slug}.html" hreflang="{$siteLang}" target="_self" title="{$editLinkTitle}">
                <img src="/img/icons/small.edit.png"/>
            </a>
        </td>
    </tr>

    {/foreach}


    </tbody>

</table>