@extends('layouts.app')

@section('content')

<div class="h-100 w-100 d-flex">
    <div class="w-75 h-100 d-flex flex-column gap-2 p-2">

        <div class=" h-75 w-100 card d-flex flex-row">
            <div class="content w-25 h-100 p-2">
                <h4>Views dell appartamento XXX</h4>


            </div>
            <div class="canv w-75 h-100">
                <canvas id="view_stats" class="w-100 h-100"></canvas>
            </div>
        </div>

        <div class="card h-75 w-100 d-flex flex-row">
            <div class="canv w-75 h-100">
                <canvas id="message_stats" class="w-100 h-100">
            </div>
            <div class="content w-25 h-100 p-2">
                <label for="yearSelector">Group By:</label>
                <select id="yearSelector" onchange="updateChart()">
                    @for ($year = date('Y'); $year >= (date('Y') - 5); $year--)
                        <option value="{{ $year }}" @if($selectedYear == $year) selected @endif>{{ $year }}</option>
                    @endfor
                </select>
                <h4>Messaggi ricevuti dell appartamento XXX</h4>
            </div>
        </div>

        <div class=" h-75 w-100 card d-flex flex-row">
            <div class="content w-25 h-100 p-2">
                <h4>Views dell appartamento XXX</h4>
            </div>
            <div class="canv w-75 h-100">
                <canvas id="prova" class="w-100 h-100"></canvas>
            </div>
        </div>
    </div>
    <div class="w-25 h-100 p-2">
        <div class="card w-100"><canvas id="sponsor_stats" class="w-100 h-100"></canvas></div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
        <div class="card w-100">sponsorizzazioni A TORTA</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* General */
    let month = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set' ,'Ott', 'Nov', 'Dic'];


    /* Get Element */
    let viewStats = document.getElementById('view_stats');
    let messageStats = document.getElementById('message_stats');
    let sponsorStats = document.getElementById('sponsor_stats');
    let prova = document.getElementById('prova');

    /* VIEW Stats LINE */
    // ViewStats
    let viewData = @json($viewStats);
    let viewdates = viewData.map(entry => entry.date);
    let viewcounts = viewData.map(entry => entry.count);

    // Chart.js
    new Chart(viewStats, {
        type: 'line',
        data: {
            labels: viewdates,
            datasets: [{
                label: 'Visualizzazioni',
                data: viewcounts,
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

    /* MESSAGE Stats BAR*/


    // MessageStatsData
    let messaegeStats = @json($messageStats);
    let messaegeDates = messaegeStats.map(entry => entry.month);
    let messaegecounts = messaegeStats.map(entry => entry.count);
    console.log(messaegeStats);


    // Chart.js
    new Chart(messageStats, {
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

    /* SPONSOR Stats DOUGHNUT*/

    // Chart.js
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

    /* PROVA */
    new Chart(prova, {
   type: 'bar',
   data: {
       datasets: [{
           label: 'Per mese',
           data: messaegeDates,
           // this dataset is drawn below
           order: 2
       }, {
           label: 'Per anno',
           data: messaegeDates,
           type: 'line',
           // this dataset is drawn on top
           order: 1
       }],
       labels: messaegeDates
   },
   options: {

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


function updateChart() {
    var selectedYear = document.getElementById('yearSelector').value;

    axios.get('http://127.0.0.1:8000/api/updateChart', {
        params: {
            selectedYear: selectedYear
        }
    }).then(function (response) {
        // Aggiorna i dati del grafico con i nuovi dati ottenuti dalla risposta
        var updatedMessageStats = response.data.messageStats;
        console.log(updatedMessageStats);

        // Esempio: Aggiorna il grafico usando Chart.js
        messageStats.data.labels = updatedMessageStats.labels;
        messageStats.data.datasets[0].data = updatedMessageStats.values;
        messageStats.update();

        // Aggiorna il grafico usando il tuo metodo specifico
    }).catch(function (error) {
        console.error('Errore durante la richiesta Axios:', error);
    });
}


</script>

@endsection
