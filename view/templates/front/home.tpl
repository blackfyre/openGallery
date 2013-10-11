{include 'front/header.tpl'}



<!-- Jumbotron -->
<div class="jumbotron">
    <div class="container">
        <h1>openGallery Project</h1>
        <p class="lead">A project for the sole purpose of recreating the projects <a href="http://www.hung-art.hu/">Fine Arts in Hungary</a> and <a href="http://www.wga.hu/">Web Gallery of Art</a> in a new, modern database driven environment with all the goodness of the webs latest and greatest technologies.</p>
    </div>
</div>

<div class="container">

<!-- Example row of columns -->
<div class="row">
    <div class="col-lg-6">
        <h2>Introduction</h2>
        <p>Within the often changing borders of Hungary during its history, fine arts developed in strong interaction with European art, and although they always reflected European tendencies, they retained a strong character of their own. All artists, irrespective of origin, who worked in the country, contributed to their formation. In addition, Hungarian artists who spent a significant part of their career away from the country but retained contacts with Hungarian art and artists, also participated in the development of fine arts in Hungary. In spite of the abundance of invaluable artworks, Hungarian fine arts are somewhat underrated outside the country. It is, therefore, our objective to present a full range of painting and sculpture in Hungary to a world-wide general public by introducing artists and their most important artworks.</p>
        <p>By viewing the pictures, browsing through the biographies and comments on the artworks, you can have an outline of all trends, from the fragments of Romanesque wall paintings and architectural sculpture, to be followed by Gothic and Renaissance miniatures, winged altarpieces and carvings, then the works of baroque, classicist and romanticist periods, up to the modern era which started at the end of the 19th century and culminated in the abundance of great artists and artworks in the first half of the 20th century. Guided tours are introduced to explain the historical and stylistic relationships existing between artists and artworks.</p>
        <p>Fine Arts in Hungary (5.100 images) is part of a larger project aiming to utilize the Internet technologies in public education, in schools and in research on the fields of fine arts. Web Gallery of Art (22.000 images) provides a European background by the comprehensive presentation of European painting and sculpture from the 12-18th centuries, while Szentendre Virtual Art Exhibition (1.800 images) explores the possibilities to present contemporary art. István Szõnyi and his Circle (250 images), and Mattis Teutsch and Der Blaue Reiter (450 images) aim to elaborate Internet tools for art historians and museum curators, while Landscapes by Thomas Ender (220 images) those for librarians.</p>
        <p>The project is continued for achieve the above objective. Simultaneously to adding new images we intend to broaden the scope of the collection by extending it to the fields of architecture and applied arts, as well as extending the time-frame up to the end of the 20th century. Background information related to history, literature and culture will be added. It is highly important to work out the methods by which the interactivity of the visitors - both with the editors of the website and with each other - can be increased. Regarding the research activities, the main objective is to build a large, professional database consisting authentic data. To support the work of the museum curators our objective is to create virtual models of planned exhibitions then digital archives of realized exhibitions.</p>

    </div>
    {if isset($news)}
    <div class="col-lg-3">
        <h2>{$newsTitle}</h2>

            {$news}

    </div>
    {/if}
    <div class="col-lg-3">
        <h2>We're looking for</h2>

        <div class="media">
            <a class="pull-left btn btn-info" href="/en/openings/openPositions/developer.html">
                Details
            </a>
            <div class="media-body">
                <h4 class="media-heading">Developers</h4>
                <p>Developing this site is time consuming and tedious work, and any help would be welcome!</p>
            </div>
        </div>
        <div class="media">
            <a class="pull-left btn btn-info" href="/en/openings/openPositions/translator.html">
                Details
            </a>
            <div class="media-body">
                <h4 class="media-heading">Translators</h4>
                <p>Translating the content to every language that the engine supports is a daunting task at best, but we are looking for eager people who are willing to topple this quest</p>
            </div>
        </div>
        <div class="media">
            <a class="pull-left btn btn-info" href="/en/openings/openPositions/editor.html">
                Details
            </a>
            <div class="media-body">
                <h4 class="media-heading">Editors</h4>
                <p>Content is one thing, but it also has to presentable, this is also an immense task which requires more than one persons attention.</p>
            </div>
        </div>


    </div>
</div>

<!-- START THE FEATURETTES -->

<hr class="featurette-divider">

<div class="row featurette">
    <div class="col-md-8">
        <h2 class="featurette-heading">Wiki-like open database. <span class="text-muted">Are you up to the task?</span></h2>
        <p class="lead">Once the initial development is complete the site will sport a front-end editing interface through which registered users can expand the database!</p>
    </div>
    {*
    <div class="col-md-5">
        <img class="featurette-image img-responsive" src="data:image/png;base64," data-src="/_bootstrap3/assets/js/holder.js/500x500/auto" alt="Generic placeholder image">
    </div>
    *}
</div>

<hr class="featurette-divider">

<div class="row featurette">
    {*
    <div class="col-md-5">
        <img class="featurette-image img-responsive" src="data:image/png;base64," data-src="/_bootstrap3/assets/js/holder.js/500x500/auto" alt="Generic placeholder image">
    </div>
    *}
    <div class="col-md-8 col-md-offset-4">
        <h2 class="featurette-heading">Postcard System <span class="text-muted">See for yourself.</span></h2>
        <p class="lead">Users will be able to create personalized postcards for their friends and family to send in email, or share it on facebook, twitter, google+</p>
    </div>
</div>

<hr class="featurette-divider">

<div class="row featurette">
    <div class="col-md-8">
        <h2 class="featurette-heading">Guided tours <span class="text-muted">Interested in spreading the knowledge?</span></h2>
        <p class="lead">Create your very own guided tours for your students, friends and family!</p>
    </div>
    {*
    <div class="col-md-5">
        <img class="featurette-image img-responsive" src="data:image/png;base64," data-src="/_bootstrap3/assets/js/holder.js/500x500/auto" alt="Generic placeholder image">
    </div>
    *}
</div>

<hr class="featurette-divider">

<div class="row featurette">
    {*
    <div class="col-md-5">
        <img class="featurette-image img-responsive" src="data:image/png;base64," data-src="/_bootstrap3/assets/js/holder.js/500x500/auto" alt="Generic placeholder image">
    </div>
    *}
    <div class="col-md-8 col-md-offset-4">
        <h2 class="featurette-heading">API <span class="text-muted">If you need something.</span></h2>
        <p class="lead">The 1.0 release will provide an easy and secure API access to the database containing all the artist and art data that's available!</p>
    </div>
</div>



<!-- /END THE FEATURETTES -->


{include 'front/footer.tpl'}
