<?php 

require_once(BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/my-account-top-menu.php');

?>
<div class="row">
      
   <?php 
$default = [
   [
      'document-name'   => esc_html__( 'ID Card', 'brighty-core' ),
      'document-description'    => 'JPG, GIF or PNG. Max size of 800K',
      'document-id' =>'idcard',
      'file-type' => '.png,.jpg,.jpeg,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
   ],
   [
      'document-name'   => esc_html__( 'Address Proof', 'brighty-core' ),
      'document-description'    => 'JPG, GIF or PNG. Max size of 800K',
      'document-id' => 'addressproof',
          'file-type' => '.png,.jpg,.jpeg,.pdf,.doc,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
   ]
   ];
$documents = get_theme_mod( 'documents_required',$default); 




foreach($documents as $document) {
   ?>

      <div class="col-6">
         
            <div class="card card-body border-0 shadow mb-4">

            <form enctype="multipart/form-data" method="post" action="/wp-admin/admin-ajax.php" >

	            <input name="security" value="<?php echo wp_create_nonce("brighty_upload_document"); ?>" type="hidden">
               <input name="action" value="brighty_upload_document" type="hidden"/>
               <input name="document-name" value="<?php echo $document['document-id']; ?>" type="hidden"/>
	

               <h2 class="h5 mb-4"><?php echo $document['document-name']; ?></h2>
               <div class="d-flex align-items-center">

               <?php 
               
               $file_url = get_user_meta(get_current_user_id(),$document['document-id'],true);

               
               if($file_url){
                  
                     $name_array = explode('.', $file_url);
                     
                     $ext = strtolower(end($name_array));

               
               if($ext =='png' || $ext =='PNG' || $ext =='jpg' || $ext =='JPG' || $ext =='jpeg' || $ext =='JPEG'  ){
               
               ?>
                  <div class="me-3"> <img class="rounded avatar-xl" style="max-height:50px" src="<?php 
                  
                 
                        echo $file_url; ?>
                " alt="change avatar"></div>
               <?php
               }
               }

                  
                  ?>


                  <div class="file-field">
                     <div class="d-flex justify-content-xl-center ms-xl-3">
                        <div class="d-flex">
                           <svg class="icon text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                           </svg>
                           <input
                           name="file"
                           type="file"
                           accept="<?php echo $document['file-type']; ?>"
                           >
                           <div class="d-md-block text-left">
                              <div class="fw-normal text-dark mb-1">Choose File</div>
                              <div class="text-gray small"><?php echo $document['document-description']; ?></div>
                           
                              <input name="submit" value="upload" type="submit" class="btn btn-outline btn-sm btn-primary"/>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            </div>
         </div>
<?php } ?>

       

         </div>