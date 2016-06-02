<article <?php post_class("df_article row"); ?>>
    <div class="col-md-12">
    <div class="col-md-2">
     <div class=imgcontainer>
        <img class=category_small src="<?= get_template_directory_uri(); ?>/dist/images/cat-<?= get_the_category()[0]->name; ?>.svg" />
        <?php the_post_thumbnail("thumbnail", array( 'class' => 'img-responsive' )); ?>
     </div>
    </div>
    <div class=col-md-8>
      <header>
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php get_template_part('templates/entry-meta'); ?>
      </header>
      <div class="entry-summary">
        <?php the_excerpt(); ?>
      </div>
    </div>
</article>
