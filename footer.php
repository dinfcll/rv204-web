<script language="JavaScript">
    jQuery(function ($) {
        $("#message").delay(4000).fadeOut(1000);
    });
    setTimeout(function(){
        document.getElementById('message').innerHTML = ""
    }, 5000)
</script>
</body>
</html>