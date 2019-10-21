<!-- MODAL RESERVATION -->
<div class="modal fade" id="modalReservation" tabindex="-1" role="dialog" aria-labelledby="modalReservationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReservationLabel">RESERVATION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action='#' method='GET'>
                    <div class="form-group">
                        <label for="startDate">Arrivée:</label>
                        <input id="startDate" name="arrive" required>
                    </div>
                    <div class="form-group">
                        <label for="endDate">Départ: </label>
                        <input id="endDate" name="depart" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-black" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-beige">Reserver</button>
                    </div>
                </form>
            </div>  
        </div>
    </div>
</div>
<!-- fin modale de réservation -->
<!-- //modale de résérvation: -->
<script>
var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
$('#startDate').datepicker({
  uiLibrary: 'bootstrap4',
  iconsLibrary: 'fontawesome',
  minDate: today,
  maxDate: function () {
    return $('#endDate').val();
  }
});
$('#endDate').datepicker({
  uiLibrary: 'bootstrap4',
  iconsLibrary: 'fontawesome',
  minDate: function () {
    return $('#startDate').val();
  }
});
</script>
