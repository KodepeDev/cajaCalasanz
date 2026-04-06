<div>
    <button class="btn btn-secondary mb-2" data-toggle="modal" data-target="#schoolYearModal">AÑO ESCOLAR: {{ $schoolYear->year }}</button>
    <!-- Modal -->
    <div class="modal fade" id="schoolYearModal" tabindex="-1" aria-labelledby="schoolYearModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="schoolYearModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="year">Año</label>
                <input type="text" class="form-control" id="year" name="year" value="{{ $schoolYear->year }}">
              </div>
              <div class="form-group">
                <label for="start_date">Fecha de inicio</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $schoolYear->start_date }}">
              </div>
              <div class="form-group">
                <label for="end_date">Fecha de fin</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $schoolYear->end_date }}">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
</div>
