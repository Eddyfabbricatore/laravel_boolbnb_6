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
    },1000)

};
function checkInputValue(inputSearch) {

    this.getAddress(inputSearch);
    setTimeout(() => {

      this.getFFAddress();
    }, 4000)

};
autocompleteInput.addEventListener('input', async (event) => {

        const query = encodeURIComponent(event.target.value);

        try {
            const initUrl = 'https://api.tomtom.com/search/2/geocode/';
            const finalUrl = '.json?key=nq7V1UsXc4xKYSFcXm3BDbYjtFObpZl8'
            const queryUrl = initUrl + query + finalUrl;
            const response =  fetch(queryUrl);


            const data =  response.json();
            const results = data.results;

            console.log(results);


            // Clear previous results
            autocompleteResults.innerHTML = '';

            // Display new results
            results.forEach(result => {
                const li = document.createElement('li');
                li.textContent = result.address.freeformAddress;
                autocompleteResults.appendChild(li);
            });
        } catch (error) {
            console.error('Error fetching autocomplete results:', error);
        }
    });


