var _wpCustomizeHeader = _wpCustomizeHeader || {
    data: {
        width: 0,
        height: 0,
    }
};
jQuery(document).ready(function($){
    var _custom_media = true,
        _orig_send_attachment = wp.media.editor.send.attachment,
        options = {
            'frame': 'select',
            'state': 'library',
            'title':  'Select an image',//wp.media.view.l10n.addMedia,
            'multiple': false
        }.
        CroppingMediaBox,
        croppingEnable = false;

    if (wp.customize && wp.customize.HeaderControl) {
        croppingEnable = true;
    }
    if (croppingEnable) {
        CroppingMediaBox = wp.customize.HeaderControl.extend({
            initialize: function (id, param) {
                this.id = id;
                this.container = param.container;
                this.input = param.input;
                this.preview = param.preview;
                this.AttachIdInput = param.AttachIdInput;
            },

            setImageFromURL: function(url, attachmentId, width, height) {
                // Insert the selected attachment.
                $('#'+this.container+' .preview-thumbnail .dropdown-status').hide();
                $("#"+this.AttachIdInput).val(attachmentId);
                $("#"+this.input).val(url);
                $("#"+this.preview).attr('src', url);
                $("#"+this.preview).show();
                $('#'+this.container+' a.remove').show();
            }
        });
    }

    $('.add_media').on('click', function(){
        _custom_media = false;
    });

    $.each(wpforms_medialibrary_setup, function (uploaderParams) {
        var that = this;

        $('#'+this.container+' .dropdown-content').click(function(e) {
            var mediaBox = null;
            e.preventDefault();

            if (that.allowCropping && croppingEnable) {
                _wpCustomizeHeader.data.width  = that.suggestedWidth || _wpCustomizeHeader.data.width;
                _wpCustomizeHeader.data.height = that.suggestedHeight || _wpCustomizeHeader.data.height;
                _wpCustomizeHeader.data['flex-width'] = that.flexWidth || 0;
                _wpCustomizeHeader.data['flex-height'] = that.flexHeight || 0;
                var mediaBox = new CroppingMediaBox(that.container, that);
                mediaBox.openMedia(e);
                return false;
            }
            
            
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
                $("#"+that.AttachIdInput).val(attachment.get('id'));
                $("#"+that.input).val(attachment.get('url'));
                $("#"+that.preview).attr('src', attachment.get('url'));
                $("#"+that.preview).show();
                $('#'+that.container+' a.remove').show();
            });
            mediaBox.open();
            return false;
        });

        $('#'+this.container+' a.remove').click(function(e) {
            e.preventDefault();
            $('#'+that.container+' .preview-thumbnail .dropdown-status').show();
            $("#"+that.preview).hide();
            $("#"+that.preview).attr('src', '');
            $("#"+that.input).val('');
            $(e.currentTarget).hide();
        });
    });
    
});