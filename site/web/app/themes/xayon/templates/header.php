<header class="banner">
  <div class="container-fluid head">
    <div style="height:80px" class=col-md-12></div>
    <div id=head>
        <a href="/">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                        <img class=center-block src="<?= get_template_directory_uri(); ?>/dist/images/logo.svg" /><br/>
                </div>
                <div class=col-md-4> </div>
                <div class="col-md-12">
                    <h1 class=text-center>DAVID FRANCOS</h1>
                    <p class="text-center gourmet">gourmet coder</p>
                </div>
            </div>
        </a>
        <div style="height:30px" class=col-md-12></div>
<?php /* if(!is_single()){ */ ?>
        <p class=bio> Usuario de GNU/Linux desde que tengo memoria.<br/>
Enamorado de la terminal, desarrollador de software.<br/>
Me apasiona mi trabajo, la cocina y me gusta viajar y cuidarme.<br/>
Soy un loco de las automatizaciones y la eficiencia.</p>
<?php /* } */ ?>

        <div style="height:30px" class=col-md-12></div>

        <nav class="col-md-4 col-md-offset-4 text-center">
            <div class="col-md-4">
                <a href="/category/tech">
				    <img src="<?= get_template_directory_uri(); ?>/dist/images/tech.svg" /><br/><br/>
				    <span> Tecnologia </span>
                </a>
			</div>

            <div class="col-md-4">
                <a href="/category/food">
				    <img src="<?= get_template_directory_uri(); ?>/dist/images/cocina.svg" /><br/><br/>
		    		<span> Cocina </span>
                </a>
			</div>

            <div class="col-md-4">
                <a href="http://davidfrancos.net">
				    <img src="<?= get_template_directory_uri(); ?>/dist/images/cv.svg" /><br/><br/>
				    <span> Curriculum </span>
                </a>
			</div>
        </nav>

        <div style="height:40px" class=col-md-12></div>

    </div>
</header>

<div class=arrow-down>&nbsp;</div>
