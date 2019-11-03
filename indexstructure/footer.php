</div><br><br>
<!--------------------------------Footer------------------------------------>
<footer class="text-center" id="footer">&copy; Copyright 2014-2019 Xplitciet Stores</footer>
<script>
function detailsModal(id) {
  var data = {"id" : id};
  jQuery.ajax({
        //url : <?//BASEURL;?>+'indexstructure/detailsModal(lightbox).php',
        url : '/xplitcietstores/indexstructure/detailsModal(lightbox).php',
        method : 'POST',
        data : data,
        success : function(data) {
            jQuery('body').append(data);
            jQuery('#details-modal').modal('toggle');
        },
        error : function() {
            alert('Shit went wrong somewhere');
        }
  });
}
</script>
</body>
</html>

