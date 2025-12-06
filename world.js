document.addEventListener('DOMContentLoaded', function() {
    const lookupButton = document.getElementById('lookup');
    const countryInput = document.getElementById('country');
    const resultDiv = document.getElementById('result');

    lookupButton.addEventListener('click', function(event) {
        event.preventDefault();
        
        const countryName = countryInput.value.trim();

        const url = 'world.php?country=' + encodeURIComponent(countryName);

        fetch(url)
            .then(response => {
                if (!response.ok)
                    throw new Error(`HTTP error! status: ${response.status}`);

                return response.text(); 
            })
            .then(data => {
                resultDiv.innerHTML = '<h2>Country Lookup Results</h2>' + data;
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                resultDiv.innerHTML = '<h2>Error</h2><p>Could not fetch country data.</p>';
            });
    });
});