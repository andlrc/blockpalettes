<?php 
session_start();
include "include/logic.php";

 $palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY id DESC LIMIT 15");
 $palettePull->execute();
 $result = $palettePull->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Infinite Scroll Pagination Using JQuery And Ajax In PHP</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
  <div class="row content">
    <h3 class="text-success text-center">Infinite Scroll Pagination Using JQuery And Ajax In PHP</h3>
    <br>    
    <div class="col-sm-12">      
      <div class="row" id="post-data">
     <?php foreach($result as $row):  ?>
        <div class="col-sm-3">
          <div class="palette-float" id="<?php echo $row['id']; ?>">
            <h4><?=$row['id']?> : <?=$row['blockThree']?></h4>
          </div>
        </div>
    <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>   
<script>

    $(window).scroll(function() {
    if(($(window).scrollTop() == $(document).height() - $(window).height())) { //Add in condition
        var last_id = $(".palette-float:last").attr("id");
        loadMoreData(last_id);
    }
    });

    function loadMoreData(last_id){
    $.ajax({
        url: 'showMoreData.php?last_id=' + last_id,
        type: "get",
        beforeSend: function()
        {
            $('.ajax-load').show();
        }
    })
    .done(function(data)
    {
            $('.ajax-load').hide();
            $("#post-data").append(data);
        
    })
    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
            alert('No response...');
    
    });
    }
</script>
</body>
</html>