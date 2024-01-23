let results = [];
let mappedResults = [];
let timer ='';
let isLoaded = false;

const autocompleteInput = document.getElementById('address');
const autocompleteResults = document.getElementById('autocompleteResults');

function checkTimer(){
    this.mappedResults = [];
    // this.isLoaded = false;

    if(this.timer != ''){
      clearTimeout(this.timer);
    }

    this.timer = setTimeout(()=>{
      if(this.inputSearch.length > 3){
        this.checkInputValue(this.inputSearch);
      }
    },500)

};
function checkInputValue(inputSearch) {

    this.getAddress(inputSearch);
    setTimeout(() => {

      this.getFFAddress();
    }, 500)

};
autocompleteInput.addEventListener('keyup', async (event) => {

        const query = encodeURIComponent(event.target.value);

        try {
            const initUrl = 'https://api.tomtom.com/search/2/geocode/';
            const finalUrl = '.json?key=nq7V1UsXc4xKYSFcXm3BDbYjtFObpZl8&typeahead=true&countrySet=IT'
            const queryUrl = initUrl + query + finalUrl;
            const response = await fetch(queryUrl);


            const data = await response.json();
            const results = data.results;



            // console.log(results);


            // Clear previous results
            autocompleteResults.innerHTML = '';


            // Display new results
            results.forEach(result => {
                // console.log(result.type);
                const options = document.createElement('option');
                options.innerText = result.address.freeformAddress;
                autocompleteResults.appendChild(options);
                // console.log(autocompleteResults)
            });
        } catch (error) {
            console.error('Error fetching autocomplete results:', error);
        }
    });


