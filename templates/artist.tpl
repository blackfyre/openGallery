{include file='header.tpl'}

<h2 class="bioName">{$artistFirstName}, {$artistLastName}</h2>
<p class="bioDates">(b. {$dateOfBirth}, {$placeOfBirth}, d. {$dateOfDeath}, {$placeOfDeath})</p>
<p class="bioSchool">{$qualification}</p>
<div class="bio">
    {$bio}
</div>

<h2>{$works}</h2>

{include file='footer.tpl'}