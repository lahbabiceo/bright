console.log("yo bro");
jQuery(".mark-notifications-read").click(function() {

    console.log(ajaxurl);
    console.log("yo bro");
    var notification_read_upto = jQuery(this).attr('last_notification_id');
    var email_read_upto = jQuery(this).attr('last_email_id');

    var data = {
       'action'   : 'brighty_notifications_mark_read',
       'brighty_notifications_read_upto' : notification_read_upto,
       'brighty_email_read_upto' : email_read_upto,

       };
     
    jQuery.post(ajaxurl, data, function(response) {

        console.log("yo bro",response);
        jQuery('.nav-link.notification-bell').removeClass('unread');
    });
 });



 /// open invoice

 function open_invoice(id){
    jQuery('#notification-title').html('Please wait...')
    jQuery('#notification-body').html('<div class="spinner-border" role="status"></div>');

    jQuery('#notification-body').load('/wp-admin/admin-ajax.php?action=brighty_open_invoice&id='+id)
    jQuery('#notification-title').load('/wp-admin/admin-ajax.php?action=brighty_open_invoice&id='+id+' #subject')

}