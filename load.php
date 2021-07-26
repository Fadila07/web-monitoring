<?php
    echo '<span hidden id="load"></span>';
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        setInterval(function() {
            $("#load").load("tsukamoto.php");
        }, 4000);
    })

</script>