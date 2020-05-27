<?php
function init_template() {
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');

  register_nav_menus( 
    array(
      'top_menu' => 'Menú Principal'
    )
  );
}

add_action('after_setup_theme', 'init_template');

function assets() {
  wp_register_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', '', '4.5.0', 'all');
  wp_register_style('montserrat','https://fonts.googleapis.com/css2?family=Montserrat&display=swap','','1.0', 'all');
  wp_enqueue_style( 'estilos', get_stylesheet_uri(), array('bootstrap','montserrat'),'1.0', 'all');

  wp_register_script('popper','https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js','', '1.16.0', true);
  wp_enqueue_script( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', array('jquery', 'popper'), '4.5.0', true );
  wp_enqueue_script( 'custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true);

  wp_localize_script('custom','pg', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'api_url' => home_url('wp-json/pg/v1/')
  ));
}

add_action( 'wp_enqueue_scripts','assets');

function sidebar() {
  register_sidebar( 
    array(
      'name' => 'Pie de página',
      'id' => 'footer',
      'description' => 'Zona de widges para pie de página',
      'before_title' => '<p>',
      'after_title' => '</p>',
      'before_widget' => '<div id="%1$s" class="%2$s">',
      'after_widget' => '</div>'
    )
  );
}

add_action( 'widgets_init', 'sidebar');

function productos_type(){

    $labels = array(
        'name' => 'Productos',
        'singular_name' => 'Producto',
        'menu_name' => 'Productos'
    );

    $args = array(
        'label' => 'Productos',
        'description' => 'Productos de platzi',
        'labels' => $labels,
        'supports' => array('title','editor','thumbnail','revision'),
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-cart',
        'can_export' => true,
        'publicy_queryable' =>  true,
        'rewrite'=>true,
        'show_in_rest' => true
    );

    register_post_type('producto', $args);
}

add_action('init','productos_type');

add_action('wp_enqueue_scripts','enqueue_my_script');
function enqueue_my_script(){
    if(is_404()){
      wp_enqueue_style( 'estilo404', get_template_directory_uri().'/style404.css', '','1.0', 'all');
    }
}

function pgRegisterTax() {
  $args = array(
    'hierarchical' => true,
    'labels' => array(
      'name' => 'categorías de Productos',//siempre se usa en plural
      'singular_name' => 'categoría de Productos'
    ),
    'show_in_nav_menu' => true,
    'show_admin_column' => true,
    'rewrite' => array('slug' => 'categoria-productos')
  );
  register_taxonomy('categoria-productos', array('producto'), $args); 
}

add_action( 'init', 'pgRegisterTax');

add_action( 'wp_ajax_nopriv_pgFiltroProductos', "pgFiltroProductos");
add_action( 'wp_ajax_pgFiltroProductos', "pgFiltroProductos");
function pgFiltroProductos() {
  $args = array(
    'post_type' => 'producto',
    'post_per_page' => -1,
    'order' => 'ASC',
    'orderby' => 'title',
  );
  if($_POST['categoria']) {
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'categoria-productos',
        'field' => 'slug',
        'terms' => $_POST['categoria']
      )
    );
  }
  $productos = new WP_Query($args);

  if($productos->have_posts()) {
    $return = array();
    while ($productos->have_posts()) {
      $productos->the_post();
      $return[] = array(
        'imagen' => get_the_post_thumbnail(get_the_id(), 'large'),
        'link' => get_the_permalink(),
        'titulo' => get_the_title()
      );
    }
    wp_send_json($return);
  }
}
add_action('rest_api_init', 'novedadesAPI');
function novedadesAPI() {
  register_rest_route( 'pg/v1', '/novedades/(?P<cantidad>\d+)',
  array( 'methods' => 'GET',
          'callback' => 'pedidoNovedades')
  );
}

function pedidoNovedades($data) {
  $args = array(
    'post_type' => 'post',
    'post_per_page' => $data['cantidad'],
    'order' => 'ASC',
    'orderby' => 'title',
  );
  $novedades = new WP_Query($args);
  if($novedades->have_posts()) {
    $return = array();
    while ($novedades->have_posts()) {
      $novedades->the_post();
      $return[] = array(
        'imagen' => get_the_post_thumbnail(get_the_id(), 'large'),
        'link' => get_the_permalink(),
        'titulo' => get_the_title()
      );
    }
    return $return;
  }
}


add_action('init', 'pgRegisterBlock');
function pgRegisterBlock() {
  $assets = include_once get_template_directory().'/blocks/build/index.asset.php';
  wp_register_script(
    'pg-block',
    get_template_directory_uri().'/blocks/build/index.js',
    $assets['dependencies'],
    $assets['version']
  );
  register_block_type(
    'pg/basic',
    array(
      'editor_script' => 'pg-block',
      'attributes'      => array( // Repetimos los atributos del bloque, pero cambiamos los objetos por arrays
        'content' => array(
            'type'    => 'string',
            'default' => 'Hello world'
        )
      ),
      'render_callback' => 'pgRenderDinamycBlock'
    )
  );
}

function pgRenderDinamycBlock($attribute, $content) {
  return '<h2>'.$attribute['content'].'</h2>';
}