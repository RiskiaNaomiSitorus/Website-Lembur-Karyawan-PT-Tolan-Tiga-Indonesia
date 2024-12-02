$(document).ready(function () {
    $("#calendar").datepicker();

    // Toggle kalender dropdown
    $("#calendarButton").on("click", function () {
        $("#calendar").toggle();
    });

    // Set current time and date in WIB
    function updateTime() {
        var now = new Date();
        var optionsTime = {
            timeZone: "Asia/Jakarta",
            hour: "2-digit",
            minute: "2-digit",
        };
        var optionsDate = {
            timeZone: "Asia/Jakarta",
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
        };
        var formatterTime = new Intl.DateTimeFormat("id-ID", optionsTime);
        var formatterDate = new Intl.DateTimeFormat("id-ID", optionsDate);
        var formattedTime = formatterTime.format(now);
        var formattedDate = formatterDate.format(now);
        $("#currentTime").text(formattedTime);
        $("#currentDate").text(formattedDate);
    }

    updateTime();
    setInterval(updateTime, 60000); // Update time every minute
});

var ctxLembur = document.getElementById("lemburChart").getContext("2d");
var lemburChart = new Chart(ctxLembur, {
    type: "bar",
    data: {
        labels: [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ],
        datasets: [
            {
                label: "Total Jam Lembur",
                data: [30, 45, 60, 40, 50, 55, 70, 65, 60, 75, 80, 90], // contoh data lembur per bulan
                backgroundColor: "rgba(0, 123, 255, 0.5)",
                borderColor: "rgba(0, 123, 255, 1)",
                borderWidth: 1,
            },
        ],
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    },
});
