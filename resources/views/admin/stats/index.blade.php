@extends('layouts.app')

@section('content')

<div class="h-100 w-100 d-flex flex-column flex-md-row">
    <div class="h-100 d-flex flex-column gap-2 p-2 flex-grow-1 mb-3">

        <div class="card d-flex flex-grow-1">

            <div class="content p-2 d-flex justify-content-evenly">
                <div class="me-4">
                    <h4>Views dell appartamento:</h4>
                    <p>{{$apartment->title}}</p>
                </div>
                <div>
                    <select id="yearViewSelector"  onchange="updateChart(viewStatsElem, apiViewStats, 0, 'yearViewSelector')">
                        @for ($year = date('Y'); $year >= (date('Y') - 2); $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="canv flex-grow-1">
                <canvas id="view_stats" class="p-2 w-100 h-100"></canvas>
            </div>

        </div>

        <div class="card d-flex flex-grow-1">

            <div class="content p-2 d-flex justify-content-evenly">
                <div class="me-4">
                    <h4>Messaggi ricevuti dell appartamento</h4>
                    <p>{{$apartment->title}}</p>
                </div>
                <div>
                    <select id="yearMessageSelector"  onchange="updateChart(messageStatsElem, apiMessageStats, 0, 'yearMessageSelector')">
                        @for ($year = date('Y'); $year >= (date('Y') - 2); $year--)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="canv flex-grow-1">
                <canvas id="message_stats" class="p-2 w-100 h-100">
            </div>
        </div>
    </div>
    <div class="d-flex flex-md-column gap-2 p-2 flex-wrap flex-md-nowrap">
{{--         <div class="card h-50 w-100">
            <h4>Sponsorizzazione preferita:</h4>
            <canvas id="sponsor_stats" class="p-5 w-100 h-100"></canvas>
        </div> --}}
        <div class="card h-50 w-100">
            <h4>Sponsorizzazione preferita:</h4>
            <canvas id="view_year_stats" class="px-5 w-100 h-100"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* Api call */
    let apiViewStats = 'http://127.0.0.1:8000/api/updateViewChart';
    let apiMessageStats = 'http://127.0.0.1:8000/api/updateMessageChart';

    /* General */
    let month = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set' ,'Ott', 'Nov', 'Dic'];
    let sponsors = ['bronze', 'silver', 'gold'];

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
    //console.log('viewdates:', viewdates);
    //console.log('viewcounts:', viewcounts);

    let viewStatsElem = new Chart(viewStats, {
        type: 'line',
        data: {
            labels: month,
            datasets: [{
                label: 'Visualizzazioni',
                data: viewcounts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                tension: 0.3,
                pointHoverRadius: 9,
                pointRadius: 7,
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

    console.log('messaegeStats:', messaegeStats);

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
    var sponsorAllYearsData = @json($sponsorAllYears);

    // Extract data for Chart.js
    var sponsorAllYearsId = sponsorAllYearsData.map(item => item.sponsor_id);
    var sponsorAllYearsCount= sponsorAllYearsData.map(item => item.count);

    console.log('sponsorAllYearsData:',sponsorAllYearsData);
    console.log('sponsorAllYearsData:',sponsorAllYearsData);
    console.log('sponsorAllYearsData:',sponsorAllYearsData);

/*     let sponsorStatsElem = new Chart(sponsorStats, {
        type: 'doughnut',
        data: {
        labels: sponsors,
        datasets: [{
            label: 'My First Dataset',
            data: sponsorAllYearsCount,
            backgroundColor: [
            'rgb(219, 112, 42)',
            'rgb(200, 200, 200)',
            'rgb(255, 220, 0)'
            ],
            hoverOffset: 4
        }]
        }
    }); */
    let viewYearStatsElem = new Chart(viewYearStats, {
        type: 'polarArea',
        data: {
        labels: sponsors,
        datasets: [{
            label: 'Numero totale di sponsorizzazioni',
            data: sponsorAllYearsCount,
            backgroundColor: [
            'rgba(219, 112, 42, 0.7)',
            'rgba(200, 200, 200, 0.7)',
            'rgba(255, 220, 0, 0.7)'
            ],
            hoverOffset: 2,
            /* options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 1,
            } */


        }]
        }
    });


    function updateChart(chartElem, apiUrl, datasetIndex, yearSelected) {
    var selectedYear = document.getElementById(yearSelected).value;

    axios.get(apiUrl, {
        params: {
            selectedYear: selectedYear
        }
    }).then(function (response) {
        var updatedStats = response.data.response;

        if (updatedStats) {
            var labels = updatedStats.map(entry => entry.month);
            var values = updatedStats.map(entry => entry.count);

            chartElem.data.labels = labels;
            chartElem.data.datasets[datasetIndex].data = values;
            chartElem.update();
        } else {
            console.error('Il campo stats non è presente nella risposta.');
        }
    }).catch(function (error) {
        console.error('Errore durante la richiesta Axios:', error);
    });
}


</script>

@endsection
