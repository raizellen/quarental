document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('input[name="query"]');
    const suggestionsBox = document.getElementById('suggestionsBox');
    let cars = [];

    fetch('/poti assignment 2/assets/data/cars.json')
        .then(response => response.json())
        .then(data => {
            cars = data.cars;
            const carTypes = [...new Set(cars.map(car => car.carType))].sort();
            const brands = [...new Set(cars.map(car => car.brand))].sort();

            const filterOptions = document.querySelector('.filter-options');
            filterOptions.innerHTML = '';

            const carTypeTitle = document.createElement('div');
            carTypeTitle.innerHTML = '<strong>Car Types</strong>';
            filterOptions.appendChild(carTypeTitle);

            carTypes.forEach(type => {
                const label = document.createElement('label');
                label.innerHTML = `<input type="checkbox" name="filter[]" value="${type}"> ${type}`;
                filterOptions.appendChild(label);
        });

        const hr = document.createElement('hr');
        filterOptions.appendChild(hr);

        const brandTitle = document.createElement('div');
        brandTitle.innerHTML = '<strong>Brands</strong>';
        filterOptions.appendChild(brandTitle);

        brands.forEach(brand => {
            const label = document.createElement('label');
            label.innerHTML = `<input type="checkbox" name="filter[]" value="${brand}"> ${brand}`;
            filterOptions.appendChild(label);
        });
        })
        .catch(error => console.error('Error loading car data:', error));

    input.addEventListener('input', () => {
        const query = input.value.trim().toLowerCase();

        if (query === '') {
            suggestionsBox.style.display = 'none';
            return;
        }

        const checkedFilters =
            Array.from(document.querySelectorAll('.filter-options input[type="checkbox"]:checked')).map(cb => cb.value.toLowerCase());
        
        const matches = cars.filter(car => {
        const matchesQuery =
            car.brand.toLowerCase().includes(query) ||
            car.carModel.toLowerCase().includes(query) ||
            (car.description && car.description.toLowerCase().includes(query));

        let matchesFilters = true;
        if (checkedFilters.length > 0) {
            matchesFilters = checkedFilters.every(filter =>
                filter === car.carType.toLowerCase() || filter === car.brand.toLowerCase()
            );
        }

        return matchesQuery && matchesFilters;
    }).slice(0, 5);

        if (matches.length > 0) {
            suggestionsBox.innerHTML = matches
                .map(car => `<div class="suggestion-item">${car.brand} ${car.carModel}</div>`)
                .join('');
            suggestionsBox.style.display = 'block';
        } else {
            suggestionsBox.style.display = 'none';
        }
    });

    suggestionsBox.addEventListener('click', (e) => {
        if (e.target.classList.contains('suggestion-item')) {
            input.value = e.target.textContent;
            suggestionsBox.style.display = 'none';
            performSearch();
        }
    });

    function performSearch() {
        const query = encodeURIComponent(input.value.trim());
        const checkedFilters = Array.from(document.querySelectorAll('.filter-options input[type="checkbox"]:checked'))
            .map(cb => encodeURIComponent(cb.value));

        let url = `/poti assignment 2/pages/searchResults.php?query=${query}`;
        if (checkedFilters.length > 0) {
            url += '&filter[]=' + checkedFilters.join('&filter[]=');
        }
        window.location.href = url;
    }

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
});

const filterBtn = document.getElementById('filterBtn');
const filterOptions = document.querySelector('.filter-options');

filterBtn.addEventListener('click', (event) => {
    event.preventDefault();
    event.stopPropagation();
    const currentDisplay = window.getComputedStyle(filterOptions).display;
    console.log('Filter button clicked, current display:', currentDisplay);
    if (currentDisplay === 'none') {
        filterOptions.style.display = 'block';
    } else {
        filterOptions.style.display = 'none';
    }
});

const dropdownWrapper = document.querySelector('.filter-dropdown');

document.addEventListener('click', (event) => {
    if (!dropdownWrapper.contains(event.target)) {
        filterOptions.style.display = 'none';
    }
});