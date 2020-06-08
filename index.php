<?php 

require_once "assets/layouts/header.php";

require_once "assets/layouts/titles.php";

echo '<title>'.$homePageTitle.'</title>';

require_once "assets/layouts/body.php";

?>
<div class="container-fluid div-center div-def-padding" style="background: #03A9F4;">
  <section>
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <h1 style="color:white; font-weight:700;">Bulk Whois Checker</h1>
        <hr/>
        <h4>by Hitendra Singh Rathore</h4>
        <p class="social-icons"><a href="https://profiles.wordpress.org/anonymousraft/" target="_blank"><i class="fab fa-wordpress"></i></a><a href="https://github.com/anonymousraft/" target="_blank"><i class="fab fa-github"></i></a><a href="https://twitter.com/anonymous_raft/" target="_blank"><i class="fab fa-twitter"></i></a></p>
      </div>
    </div>
  </section>
</div>
<div class="container-fluid div-def-padding div-center" style="text-align:left !important;">
  <div class="row h-100">
    <div class="col-md-12 col-lg-12">
     <div class="card card-block w-25">
       <h2>Basic Points to use tool properly</h2>
       <h3>Preparing CSV File</h3>
       <ul>
         <li>This tool support only <b>CSV Files</b>, So upload a CSV files that has domain names in the first column.</p>
           <li>There should be only one domain name in each row. <b>And That's it</b>. You are good to go.</li>
           <li style="margin-top: 10px;"><a href="downloads/list-of-domains.csv">Downlaod sample csv file.</a></li>
         </ul>
       </div>
     </div>
   </div>
 </div>
 <div class="container-fluid div-def-padding" style="padding-top:0px !important">
  <div class="row h-100">
    <div class="col-md-12 col-lg-12">
     <div class="card card-block w-25">
      <table align="center">
        <tr>
          <td width="20%"><span class="td-lables">Select file</span></td>
          <td width="80%"><input type="file" name="file" id="file" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple/><label for="file"><span>Choose a file</span></label></td>
        </tr>
        <tr>
          <td><span class="td-lables">Submit</span></td>
          <td><button id="upload" class="button button1">Upload</button></td>
        </tr>
      </table>
    </div>
  </div>
</div>
</div>
<div class="container-fluid div-def-padding div-center" style="padding-top:0px !important">
  <div class="row h-100">
    <div class="col-md-12 col-lg-12">
     <div id="view" class="card card-block w-25">
     </div>
   </div>
 </div>
</div>
<footer>
  <div class="container-fluid div-def-padding div-center" style="background:#696969;">
    <div class="row h-100">
      <div class="col-md-12 col-lg-12">
       <div class="card card-block w-25">
         <p style="color:white;">This project is made possible by the PHP WHOIS script provided by Michael on <a href="https://github.com/doorman1/Whois-Lookup" target="_blank" style="color:white;"><i>Github</i></a></p>
         <p style="color:white;">Made with <i class="far fa-heart"></i> by <a href="https://hitendra.co" target="_blank" style="color:white;">Hitendra</a>.</p>
       </div>
     </div>
   </div>
 </div>
</footer>
<script type='text/javascript'>
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
</script>
<script type="text/javascript">
  'use strict';

  ;( function( $, window, document, undefined )
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
</script>
<?php 

require_once "assets/layouts/footer.php";

?>
