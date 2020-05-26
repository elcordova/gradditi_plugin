<?php get_header(); ?>
<main class="container">
  <?php if (have_posts()) {
    while (have_posts()) {
      the_post();
      ?>
        <h1 class="my-3">
          <?php the_title(); ?>!!
        </h1>
        <?php the_content(); ?>

      <?php
    }
  }
  ?>
  <div class="lista-productos my-5">
    <h2 class="text-center">
      PRODUCTOS
    </h2>
    <div class="row">
      <div class="col-12">
        <select class="form-control" name="categorias-productos" id="categorias-productos">
          <option value="">Todas las categorías</option>
          <?php $terms = get_terms('categoria-productos', array('hide_empty' => true));?>
          <?php foreach($terms as $term) {
            echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
          } ?>
        </select>
      </div>
    </div>
    <div id="resultado-productos" class="row">
    <?php 
      $args = array(
        'post_type' => 'producto',//corresponde con el nombre de registro
        'post_per_page' => -1, // all default configuracion de administración
        'order'=> 'ASC',//por defecto 'DESC',
        'orderby'=> 'title' // modificando parámertro de búsqueda default 'date'
      );
      $productos= new WP_Query($args);

      if ($productos->have_posts()) {
        while ($productos->have_posts()) {
          $productos->the_post();
          ?>
          <div class="col-4 my-5">
            <figure>
              <?php the_post_thumbnail('large');?>
            </figure>
            <h4 class="my-3 text-center"><a href="<?php the_permalink(); ?>">
              <?php the_title(); ?>
            </a></h4>
          </div>
          <?php
        }
      }

    ?>
    </div>
  </div>

</main>

<?php get_footer();?>