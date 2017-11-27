<?php

echo '<div class="col-md-7 col-md-offset-2"><h3>Your trips</h3><br>';

if (empty($maps)) {
    echo 'You dont have eny map yet';
} else {
    echo '<p>'.$listing_info.'</p>';
    echo '<ul>';
    foreach ($maps as $key => $value) {
        echo "<li class='list-group-item'><a class='link' href='index.php?r=map&id=".$value['id']."'>".ucfirst($value['title'])." (".$value['map_name'].")</a><a class='delete_map pull-right' data-pk = '".$value['id']."'>X</a></li>";
    }
    echo '</ul>';
    echo '<ul class="pagination">';
    if ($pages >= 1) {
        for ($i = 0; $i <= $pages; $i++) {
            echo "<li".($i == $page ? ' class="active"': "")."><a href='index.php?page=" . $i . "'>" . ($i + 1) . "</a></li>";
        }
    }

    echo '</ul>';
}
echo '</div>';
?>

<script>

//ajax for delete map from db & folder
$(document).on('click','.delete_map', function(e){
    e.preventDefault();
    
    if(confirm('Are you shure you want to delete this map?!')){
    var id = $(this).data('pk');
    $.ajax({
        url:'index.php?r=delete',
        type:'post',
        data: {id:id},
        success:function(){
            window.location.reload();
        },
        error:function(){
            alert('Error deleting map');
        }
    })
    }
})

</script>
