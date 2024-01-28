@extends('layouts.app')

@section('content')

<div class="h-100 w-100 d-flex">
    <div class="w-75 h-100 d-flex flex-column gap-2 p-2">

        <div class=" h-50 w-100 card d-flex flex-row">
            <div class="content w-25 h-100 p-2">
                <h4>Views dell appartamento XXX</h4>
            </div>
            <div class="canv w-75 h-100">
                <canvas id="view_stats" class="w-100 h-100"></canvas>
            </div>
        </div>

        <div class="card h-50 w-100 d-flex flex-row">
            <div class="canv w-75 h-100">
                <canvas id="message_stats" class="w-100 h-100">
            </div>
            <div class="content w-25 h-100 p-2">
                <h4>Messaggi ricevuti dell appartamento XXX</h4>
            </div>
        </div>
    </div>
    <div class="w-25 h-100 p-2">
        <div class="card w-100">sponsorizzazioni A TORTA</div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let viewStats = document.getElementById('view_stats');
    let messageStats = document.getElementById('message_stats');
    let data = @json($statsView);

    let dates = data.map(entry => entry.date);
    let counts = data.map(entry => entry.count);

    new Chart(messageStats, {
        type: 'bar',
        data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
        }]
        },
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        }
    });

    let pipolo = new Chart(viewStats, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Statistiche',
                data: counts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    pipolo.resize();
</script>

@endsection
