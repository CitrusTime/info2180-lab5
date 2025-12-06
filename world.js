document.addEventListener('DOMContentLoaded', function() {
    const lookupButton = document.getElementById('lookup');
    const lookupCitiesButton = document.getElementById('lookup-cities');
    const countryInput = document.getElementById('country');
    const resultDiv = document.getElementById('result');
    
    async function performLookup(lookupType = '') {
        const countryName = countryInput.value.trim();
        
        if (!countryName) {
            resultDiv.innerHTML = '<div class="error">Please enter a country name</div>';
            return;
        }
        
        resultDiv.innerHTML = '<div class="loading">Searching</div>';
        
        try {
            const params = new URLSearchParams();
            params.append('country', countryName);
            
            if (lookupType === 'cities') {
                params.append('lookup', 'cities');
            }
            
            const response = await fetch(`world.php?${params.toString()}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            
            const data = await response.text();
            resultDiv.innerHTML = data;
            
        } catch (error) {
            console.error('Error:', error);
            resultDiv.innerHTML = `<div class="error">Error retrieving data: ${error.message}</div>`;
        }
    }
    
    if (lookupButton) {
        lookupButton.addEventListener('click', () => performLookup());
    }
    
    if (lookupCitiesButton) {
        lookupCitiesButton.addEventListener('click', () => performLookup('cities'));
    }
    
    if (countryInput) {
        countryInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                performLookup();
            }
        });
    }
    
    document.addEventListener('keypress', (event) => {
        if (event.key === 'Enter' && document.activeElement === countryInput) {
            performLookup();
        }
    });
});