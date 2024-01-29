@extends('layouts.app')

@section('content')

<div class="h-100 w-100 d-flex">
    <div class="w-75 h-100 d-flex flex-column gap-2 p-2">

        <div class=" h-50 w-100 card d-flex flex-row">
            <div class="content w-25 h-100 p-2">
                <h4>Views dell appartamento XXX</h4>
                <div class="card w-100"><label for="yearSelector">Group By:</label>
                    <select id="yearSelector" onchange="updateChart()">
                        @for ($year = date('Y'); $year >= (date('Y') - 2); $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
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
        <div class="card h-50 w-100"><canvas id="sponsor_stats" class="w-100 h-100"></canvas></div>
        <div class="card h-50 w-100"><canvas id="view_year_stats" class="w-100 h-100"></canvas></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* General */
    let month = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set' ,'Ott', 'Nov', 'Dic'];

    /* Get Elements */
    let viewStats = document.getElementById('view_stats');
    let messageStats = document.getElementById('message_stats');
    let sponsorStats = document.getElementById('sponsor_stats');
    let viewYearStats = document.getElementById('view_year_stats');
    let prova = document.getElementById('prova');

    /* VIEW Stats LINE */
    let viewData = @json($viewStats);
    let viewdates = viewData.map(entry => entry.month);
    let viewcounts = viewData.map(entry => entry.count);

    console.log('viewData:', viewData);
    console.log('viewdates:', viewdates);
    console.log('viewcounts:', viewcounts);
    let viewStatsElem = new Chart(viewStats, {
        type: 'line',
        data: {
            labels: month,
            datasets: [{
                label: 'Visualizzazioni',
                data: month,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                tension: 0.5
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

    /* MESSAGE Stats BAR */
    let messaegeStats = @json($messageStats);
    let messaegeDates = messaegeStats.map(entry => entry.month);
    let messaegecounts = messaegeStats.map(entry => entry.count);

    let messageStatsElem = new Chart(messageStats, {
        type: 'bar',
        data: {
        labels: month,
        datasets: [{
            label: '# of Votes',
            data: messaegecounts,
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

    /* SPONSOR Stats DOUGHNUT */
    new Chart(sponsorStats, {
        type: 'doughnut',
        data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100],
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
        }
    });
    new Chart(viewYearStats, {
        type: 'polarArea',
        data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100],
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
        }
    });


    function updateChart() {
        var selectedYear = document.getElementById('yearSelector').value;

        axios.get('http://127.0.0.1:8000/api/updateChart', {
            params: {
                selectedYear: selectedYear
            }
        }).then(function (response) {
            console.log(response.data);

            var updatedMessageStats = response.data.messageStats;
            console.log(updatedMessageStats);
            if (updatedMessageStats) {
                var labels = updatedMessageStats.map(entry => entry.month);
                var values = updatedMessageStats.map(entry => entry.count);

                messageStatsElem.data.labels = month;
                messageStatsElem.data.datasets[0].data = values;
                messageStatsElem.update();
                viewStatsElem.data.labels = month;
                viewStatsElem.data.datasets[0].data = values;
                viewStatsElem.update();

                // Add the logic to update viewStats (similar to messageStatsElem)
            } else {
                console.error('Il campo messageResponseStats non è presente nella risposta.');
            }

        }).catch(function (error) {
            console.error('Errore durante la richiesta Axios:', error);
        });
    }
</script>

@endsection
