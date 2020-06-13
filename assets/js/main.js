'use strict';

    ( function( $, window, document, undefined )
    {
    $( '.inputfile' ).each( function()
    {
      var $input	 = $( this ),
      $label	 = $input.next( 'label' ),
      labelVal = $label.html();

      $input.on( 'change', function( e )
      {
      var fileName = '';

      if( this.files && this.files.length > 1 )
        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
      else if( e.target.value )
        fileName = e.target.value.split( '\\' ).pop();

      if( fileName )
        $label.find( 'span' ).html( fileName );
      else
        $label.html( labelVal );
    });

      // Firefox bug fix
      $input
      .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
      .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
    });
  })( jQuery, window, document );

  $('#upload').on('click', function() {
    var file_data = $('#file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    $.ajax({
      url: 'upload.php', 
      dataType: 'text',
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      type: 'post',
      success: function(php_script_response){
              $('#view').html(php_script_response); // display response from the PHP script, if any
            }
          });
  });