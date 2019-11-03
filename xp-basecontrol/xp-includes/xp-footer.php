</div><br><br>
<!--------------------------------Footer------------------------------------>
<footer class="text-center" id="footer">&copy; Copyright 2014-2019 Xplitciet Stores</footer>

<script>
    function updateSizes() {
        var sizeString = '';
        for (var i=1;i<=12;i++){
            if (jQuery('#size'+i).val() != ''){
                sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
            }
        }
        jQuery('#sizes').val(sizeString);
    }

    function get_child_options(){
        var parentID = jQuery('#parent').val();
        jQuery.ajax({
           url: '/xplitcietstores/xp-basecontrol/xp-parsers/child_categories.php',
            type: 'POST',
            data: {parentID : parentID},
            success: function(data){
               jQuery('#child').html(data);
            },
            error: function () {alert("Something Went Wrong with the Child Options")},
        });
    }
    jQuery('select[name="parent"]').change(get_child_options);

</script>
</body>
</html>