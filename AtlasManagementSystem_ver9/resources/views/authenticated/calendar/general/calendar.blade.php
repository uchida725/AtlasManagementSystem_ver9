<x-sidebar>
<div class="vh-100 pt-4" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:10px; background:#FFF;">
    <p class="text-center" style="font-size:20px;">{{ $calendar->getTitle() }}</p>
    <div class="w-75 m-auto" style="border-radius:5px;">


      <div class="">
        {!! $calendar->render() !!}
      </div>
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="reserve-btn btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>

<!-- ▼ キャンセル確認モーダル -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalLabel">キャンセル確認</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
      </div>
      <!-- <div class="modal-body">
        本当にキャンセルしてもよろしいですか？
      </div> -->
      <div class="modal-body">
      <p>予約日：<span id="modalReserveDate"></span></p>
      <p>時間：<span id="modalReservePart"></span></p>
      <p>上記の予約をキャンセルしてもよろしいですか？</p>
    </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
        <form method="POST" action="{{ route('calendar.cancel') }}" id="cancelForm">
  @csrf
  @method('DELETE')
  <input type="hidden" name="delete_date" id="modalDeleteDate">
  <button type="submit" class="btn btn-danger">キャンセルする</button>
</form>

      </div>
    </div>
  </div>
</div>

</x-sidebar>

<!-- ✅ BootstrapのJS（モーダル動作用） -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const cancelButtons = document.querySelectorAll('.cancel-modal-btn');
    const modalInput = document.getElementById('modalDeleteDate');
    const modalDate = document.getElementById('modalReserveDate');
    const modalPart = document.getElementById('modalReservePart');

    cancelButtons.forEach(button => {
      button.addEventListener('click', function () {
        const date = this.getAttribute('data-reserve');
        const part = this.getAttribute('data-part');

        if (modalInput) modalInput.value = date;
        if (modalDate) modalDate.textContent = date;
        if (modalPart) modalPart.textContent = part;
      });
    });
  });
</script>
