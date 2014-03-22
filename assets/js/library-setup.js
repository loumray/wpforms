jQuery(document).ready(function($){

    var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment,
      options = {
          'frame': 'select',
          'state': 'library',
          'title':  'Select an image',//wp.media.view.l10n.addMedia,
          'multiple': false
      };

    $('.add_media').on('click', function(){
      _custom_media = false;
    });

    $.each(wpforms_medialibrary_setup, function (uploaderParams) {
        var that = this;

        $('#'+this.container+' .dropdown-content').click(function(e) {
            mediaBox = null;
            // Create the media frame.
            mediaBox = wp.media({
                title: that.mediaBox.title,
                library: {
                    type: that.mediaBox.type
                },
                button: {
                    text: that.mediaBox.button
                }
            });

            mediaBox.on( "select", function() {
                // Grab the selected attachment.
                var attachment = mediaBox.state().get("selection").first();
                $('#'+that.container+' .preview-thumbnail .dropdown-status').hide();
                $("#"+that.input).val(attachment.get('url'));
                $("#"+that.preview).attr('src', attachment.get('url'));
                $("#"+that.preview).show();
               
            });
            mediaBox.open();
            return false;
        });

    });
    
});