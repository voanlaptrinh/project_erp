$(document).ready(function() {
    $('#user_id').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: "-- Chọn select --",
        allowClear: true
    });
});
$(document).ready(function() {
    $('.select_ted').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: "-- Chọn select --",
        allowClear: true
    });
});
toastr.options = {
    "progressBar": true, // Hiển thị thanh tiến trình
    "timeOut": 2000, // Thời gian hiển thị thông báo (2 giây)
    "extendedTimeOut": 1000, // Thời gian hiển thị khi người dùng di chuột vào thông báo (1 giây)

};

function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const timeString = `${hours}:${minutes}:${seconds}`;
    document.getElementById('realtime-clock').textContent = timeString;
}

setInterval(updateClock, 1000);
updateClock(); // chạy ngay lần đầu