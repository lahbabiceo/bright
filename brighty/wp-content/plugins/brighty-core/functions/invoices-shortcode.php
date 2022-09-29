<?php 

// create shortcode for invoices
// function that runs when shortcode is called

add_shortcode('brighty-invoices', 'invoices_shortcode');

function invoices_shortcode() { 

    $current_user = wp_get_current_user();

    $args = array(
        'nopaging' => true,
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'post_type'         =>'inspire_invoice',
        'meta_query' => array(
            array(
                'key'     => '_client_email',
                'value'   => $current_user->user_email,
            ),
        ),
    );
    
    $invoices = new WP_Query( $args );

    $invoice_list =     '
    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="border-gray-200"># Invoice ID/Ref</th>
                    <th class="border-gray-200">Bill For</th>
                    <th class="border-gray-200">Issue Date</th>
                    <th class="border-gray-200">Due Date</th>
                    <th class="border-gray-200">Total</th>
                    <th class="border-gray-200">Status</th>
                    <th class="border-gray-200">Action</th>
                </tr>
            </thead>
            <tbody>';


    // The Loop

    if ( $invoices->have_posts() ) {
    
        while ( $invoices->have_posts() ) {
                
            $invoices->the_post();

            $product_name = get_post_meta(get_the_ID(),'_products',true);
            $payment_status = get_post_meta(get_the_ID(),'_payment_status',true);
            $date = new DateTimeImmutable();


            if($payment_status=='topay'){
                $payment_status_text = '<span class="fw-bold text-danger">Unpaid</span>';
                
            }
            else{
                $payment_status_text = '<span class="fw-bold text-warning">'.$payment_status.'</span>';;
            }
                $overdue = '';
            if(get_post_meta(get_the_ID(),'_date_pay',true) > $date->getTimestamp()){

                $overdue = ' <span class="badge fw-bold text-light bg-danger"> <i class="fa fa-warning"></i>Overdue</span>';
            }

                
            $invoice_list .= ' 
            <tr>
                    <td><a href="'.get_permalink(get_the_ID()).'" class="fw-bold">'.get_the_title().'</a></td>
                    <td><span class="fw-normal">'.$product_name[0]['name'].'...</span></td>
                    <td><span class="fw-normal">'.wp_date(get_option( 'date_format' ),get_post_meta(get_the_ID(),'_date_issue',true)).'</span></td>
                    <td><span class="fw-normal">'.wp_date(get_option( 'date_format' ),get_post_meta(get_the_ID(),'_date_pay',true)).$overdue.'</span></td>
                    <td><span class="fw-bold">'.wc_price(get_post_meta(get_the_ID(),'_total_price',true)).'</span></td>
                    <td>'.$payment_status_text .'</td>
                    <td>

                    
                    <a href="#"  class=" " ><button onclick="open_invoice(\''.get_the_ID().'\')" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#invoice-modal" >View</button></a>
                    <a href="#"  class=" " ><button onclick="open_invoice(\''.get_the_ID().'\')" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#invoice-modal" >Pay</button></a>
                        <a href="/wp-admin/admin-ajax.php?action=invoice-get-pdf-invoice&id='.get_the_ID().'&hash='.get_post_meta(get_the_ID(),'_download_hash',true).'"><button class="btn btn-sm btn-outline-primary"> <i class="fa fa-file-pdf-o"></i></button></a>
                        <a href="/wp-admin/admin-ajax.php?action=invoice-get-pdf-invoice&id='.get_the_ID().'&hash='.get_post_meta(get_the_ID(),'_download_hash',true).'&save_file=1"><button class="btn btn-sm btn-outline-primary"><i class="fa fa-download"></i> </button></a>
                    </td>
            </tr>';
 
        }

        
        $invoice_list .= '
            </tbody>
        </table>






        <!-- Modal Content -->
        <div style="z-index:999999" class="modal right in" id="invoice-modal"  role="dialog" aria-labelledby="modal-default" aria-hidden="true">
            <div style="max-width:950px"  class="modal-dialog modal-dialog-right" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="h6 modal-title" id="notification-title"></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-2" id="notification-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        







            <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                <nav aria-label="Page navigation example">
                    <ul class="pagination mb-0">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
                    <div class="fw-normal small mt-4 mt-lg-0">Showing <b>5</b> out of <b>25</b> entries</div>
            </div>
        </div>
        ';

    } 
    else {
        
        return "No Invoices";
        
    }

    /* Restore original Post Data */

    wp_reset_postdata();
        
    return $invoice_list;

}