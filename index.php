<?php include("assets/layouts/header.php");?>

  <table width="600">
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <tr>
        <td width="20%">Select file</td>
        <td width="80%"><input type="file" name="file" id="file" /></td>
      </tr>
      <tr>
        <td>Submit</td>
        <td><input type="submit" name="submit" /></td>
      </tr>
    </form>
  </table>
  <?php include("assets/layouts/footer.php"); ?>
