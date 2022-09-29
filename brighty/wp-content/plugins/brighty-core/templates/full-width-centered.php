<?php
/**
* Template Name: Brighty - Full Width Centered
*
* @package Brighty
*/

require_once(BRIGHTY_CORE_PLUGIN_DIR.'/template-parts/header.php');


?>




<main>
  <section class="mt-5 mt-lg-0 bg-soft d-flex align-items-center" data-background-lg="../../assets/img/illustrations/signin.svg" style="background: url(&quot;<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>&quot;); background-repeat: no-repeat;background-position: center center;">
    <div class="container">
      
        <p class="text-center">
            <a href="../dashboard/dashboard.html" class="d-flex align-items-center justify-content-center mt-3">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg> 
            Back to homepage 
            </a>
        </p>



        <div class="row justify-content-center form-bg-image">
          <div class="col-12 d-flex align-items-center justify-content-center">
              <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500 mb-5">
                  <div class="text-center text-md-center mb-4 mt-md-0">
                      <h1 class="mb-0 h3">Create an Account</h1>
                  </div>

                 
                    <?php 

                        while(have_posts()){
                            the_post();
                            the_content();
                        }

                    ?>
                  
              </div>
          </div>
        </div>

              
              
            
    </div>
  </section>
</main>




<?php

require_once(BRIGHTY_CORE_PLUGIN_DIR.'/template-parts/footer.php');

?>