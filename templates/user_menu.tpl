<h3>{$userMenuName}</h3>
<ul class="sideNav">
{foreach from=$userMenu item=i}
    <a href="{$i.link}" title="{$i.alt}" target="{$i.target}" hreflang="{$siteLang}">
        <li>{$i.title}</li>
    </a>
{/foreach}
</ul>