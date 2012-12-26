{include file='header.tpl'}

<h2>{$title}</h2>

<table class="artistList">
    <thead>
        <tr>
            <th>{$artistName}</th>
            <th>{$bornDied}</th>
            <th>{$period}</th>
            <th colspan="2">{$school}</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>{$artistName}</th>
            <th>{$bornDied}</th>
            <th>{$period}</th>
            <th colspan="2">{$school}</th>
        </tr>
    </tfoot>
    <tbody>

    {foreach from=$artists item=i}

    <tr>
        <td><strong>{$i.artistFullName}</strong></td>
        <td>{$i.dateOfBirth} - {$i.dateOfDeath}</td>
        <td>{$i.period}</td>
        <td>{$i.school} {$i.profession}</td>
        <td><a href="/artist/{$i.slug}.html" hreflang="{$siteLang}">&raquo;</a></td>
    </tr>

    {/foreach}


    </tbody>

</table>

{include file='footer.tpl'}