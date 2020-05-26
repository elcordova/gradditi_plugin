<?php get_header(); ?>
<main class="container my-3">
  <?php if (have_posts()) {
    while (have_posts()) {
      the_post();
      ?>
      <?php $taxonomy = get_the_terms( get_the_ID(), 'categoria-productos')?>
      <h1 class='my-5'><?php the_title(); ?></h1>
      <div class="row">
        <div class="col-4">
          <?php the_post_thumbnail('large'); ?>
        </div>
        <div class="col-8">
          <?php the_content(); ?>
        </div>
      </div>
      <?php 
      $args = array(
        'post_type' => 'producto',
        'post_per_page' => 6,
        'order' => 'ASC',
        'orderby' => 'title',
        'post__not_in' => array(get_the_ID()),
        'tax_query' => array(
          array(
            'taxonomy' => 'categoria-productos',
            'field' => 'slug',
            'terms' => $taxonomy[0]->slug
          )
        )
      );
      $productos = new WP_Query($args);
      if($productos->have_posts()) {
        ?>
          <div class="row justify-content-center productos-relacionados">
          <div class="col-12">
            <h3 class="text-center"> Productos Relacionados</h3>
          </div>
            <?php 
              while ($productos->have_posts()) {
                $productos->the_post();
                ?>
                <div class="col-2 my-3">
                  <?php the_post_thumbnail('thumbnail') ?>
                  <h4 class="text-center">
                    <a href="<?php the_permalink()?>">
                      <?php the_title( )?>                  
                    </a>
                  </h4>
                </div>
                <?php
              }
            ?>
          </div>
        <?php
      }

    }
  } ?>
</main>
<?php get_footer(); ?>