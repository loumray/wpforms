jQuery(document).ready(function($){

    $.each(wpforms_plupload_setup, function (uploaderParams) {
        var that = this,
            uploader = new plupload.Uploader(this);

        $('#'+this.container+' .dropdown-content').click(function() {
            $('#'+that.container+' .library').toggle();
            $('#'+that.container+' .actions').toggle();
        });

        //
        $('#'+this.container+' .preview-thumbnail img').on('load', function () {
            var $status = $('#'+that.container+' .preview-thumbnail .dropdown-status');
            if (this.src && (this.src !== '')) {
                $status.hide();
            } else {
                $status.show();
            }
        });
        uploader.bind('Init', function(up){
          var uploaddiv = $('#'+this.container);

          if(up.features.dragdrop){
            uploaddiv.addClass('drag-drop');
              $('#'+that.drop_element)
                .bind('dragover.wp-uploader', function(){ uploaddiv.addClass('drag-over'); })
                .bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv.removeClass('drag-over'); });

          }else{
            uploaddiv.removeClass('drag-drop');
            $('#'+that.drop_element).unbind('.wp-uploader');
          }
        });

        uploader.init();

        // a file was added in the queue
        uploader.bind('FilesAdded', function(up, files){
          var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);

          plupload.each(files, function(file){
            if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5'){
              // file size error?
              alert(pluploadL10n.file_exceeds_size_limit);
            }else{
              // a file was added, you may want to update your DOM here...
              // console.log(file);
            }
          });

          up.refresh();
          up.start();
        });
        // a file was uploaded
        uploader.bind('FileUploaded', function(up, file, wpresponse) {
            response = $.parseJSON(wpresponse.response);
            if (response.success) {
                // console.log(response);
                $('#'+that.preview_thumb_id).attr('src',response.data.url);
                $('#'+that.preview_thumb_id).show();
                $('#'+that.container+' .imgurl').val(response.data.url);
                $('#'+that.container+' .library').hide();
                $('#'+that.container+' .actions').hide();
            } else {
                alert(response.msg);
            }
        });
        wpforms_plupload_setup[uploaderParams].uploader = uploader;
    });
    
});