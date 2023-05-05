<?php
/**
* Template Name: Brighty - Full Width
*
* @package Brighty
*/

require_once(BRIGHTY_CORE_PLUGIN_DIR.'/template-parts/header.php');


?>




<main>
  <section class="mt-5 mt-lg-0 bg-soft d-flex align-items-center" data-background-lg="../../assets/img/illustrations/signin.svg" style="background: url(&quot;<?php echo get_the_post_thumbnail_url( get_the_ID(), 'medium' ); ?>&quot;); background-size:cover !important">
    <div class="container">
     
                <?php 

                    while(have_posts()){
                        the_post();
                        the_content();
                    }


                ?>
                
    </div>
  </section>
</main>




<?php

require_once(BRIGHTY_CORE_PLUGIN_DIR.'/template-parts/footer.php');

?>