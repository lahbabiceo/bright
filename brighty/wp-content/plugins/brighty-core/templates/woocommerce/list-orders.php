<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		

		<tbody>
			<?php

            global $listwa;
            $listwa = [];
           function push_to_list($id, $name, $pid, $pname, $oid){
               global $listwa; 
                $exists =0;
               foreach ($listwa as $key => $listitem) {
                if(isset($listitem[$id][0])){
                    $exists = 1;
                    
                    if(!in_array($oid, $listitem[$id][4]))
                    {
                        array_push($listwa[$key][$id][4],$oid);
                    }
                    if(!in_array($pid, $listitem[$id][2]))
                    {
                        array_push($listwa[$key][$id][2],$pid);
                    }
                }

               }
               if(!$exists)
                array_push($listwa, ["$id" => [$id, $name, [$pid], $pname, [$oid], 1]]);
           }


            $args = array(
                'customer_id' => get_current_user_id(),
                'limit' => -1, // to retrieve _all_ orders by this user
            );
            $customer_orders = wc_get_orders($args);

            $category_list = [
                [10,11,12],
                [12,12,13],
            ];

            $cat_list = array(
                "16" => [
                    "name",
                    15,
                    "product Name"
                ]
                
                );
          

			foreach ( $customer_orders as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				// print_r($order);

                foreach ($order->get_items() as $item_key => $item ):

                    $product        = $item->get_product(); // Get the WC_Product object

                    //echo $product->get_name()."<br/>";

                    $terms = get_the_terms( $product->get_id(), 'product_cat' );;

                   

                    foreach ($terms as $term){
                        //print_r($term)."<br/>";
                        //$term_id= wp_list_pluck( $terms, 'term_ID' ); 
                        
                        
                        
                      push_to_list($term->term_id , $term->name, $product->get_id(),$product->get_name(), $order->get_id());

                       
                   
                    }



                    

                endforeach;


            ?>
				
                
		    <?php
			}

           //print_r($listwa);
			?>
		</tbody>
	</table>

    <div class="row dashboard-cards">
   <?php foreach($listwa as $key => $listitem) { ?>

    <?php foreach($listitem as $keywa  => $list) { 
         $thumbnail_id = get_term_meta( $list[0], 'thumbnail_id', true );
         $image_url    = wp_get_attachment_url( $thumbnail_id ); 

         if($list[1]=="Uncategorized"){
             continue;
         }

         ?>
   <div class="col-12 col-sm-6 col-xl-3 mb-3">
      <div class="card border-0 shadow">
         <div class="card-body">
            

          
            <div class="row d-block d-xl-flex align-items-center">
               <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                  <div class="icon-shape icon-shape-success rounded me-4 me-sm-0">
                 <img class="icon icon-md rounded" src="<?php echo $image_url; ?>">
                  <!-- <svg class="icon icon-md" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                  </svg> -->
                
                  </div>
                  <div class="d-sm-none">
                     <h2 class="h5"><?php echo $list[1]; ?></h2>
                     <h3 class="fw-extrabold mb-1"><?php echo count($list[4]); ?></h3>
                  </div>
               </div>
               <div class="col-12 col-xl-7 px-xl-0">
                  <div class="d-none d-sm-block">
                     <h4 class="h5"> <?php echo $list[1]; ?></h4>
                     <h3 class="fw-extrabold mb-1"><?php echo count($list[4]); ?></h3>
                  </div>
                 
                  <div class="small d-flex mt-1">
                    
                  </div>
               </div>
            </div>
        
         </div>
      </div>
   </div>
   <?php } ?>

   <?php } ?>

 
   
 
</div>
