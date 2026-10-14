document.addEventListener('DOMContentLoaded', () => {
    const carDetailsDiv = document.getElementById('carDetails');
    const formContainer = document.getElementById('reservationFormContainer');
    const noCarMsg = document.getElementById('noCarMessage');

    let vin = new URLSearchParams(window.location.search).get('vin');
    let car;

    if (!vin) {
        const lastCar = JSON.parse(localStorage.getItem('lastCar'));
        if (lastCar) {
            car = lastCar;
            vin = lastCar.vin;
        } else {
            noCarMsg.style.display = 'block';
            return;
        }
    }

    fetch('/poti assignment 2/assets/data/cars.json')
        .then(res => res.json())
        .then(data => {
        const cars = data.cars;
        const car = cars.find(c => c.vin === vin);

        if (!car) {
            noCarMsg.style.display = 'block';
            return;
        }

        localStorage.setItem('lastCar', JSON.stringify(car));

        carDetailsDiv.innerHTML = `
            <div class="car-details"
                style="margin-left: 7rem;
                margin-top: 3rem;
                padding: 20px;
                background-color: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                ">
                <h2>${car.brand} ${car.carModel}</h2>
                <img src="${car.image}" alt="${car.brand} ${car.carModel}?>" style="width: 500px; text-align: centre;"/>
                <p><strong>Type:</strong> ${car.carType}</p>
                <p><strong>Year:</strong> ${car.yearOfManufacture}</p>
                <p><strong>Description:</strong> ${car.description}</p>
                <p><strong>Price/Day:</strong> $${car.pricePerDay}</p>
            </div>
        `;

        if (car.available) {
            buildForm(car);
        } else {
            formContainer.innerHTML = `<p>This car is currently unavailable. Please choose another car.</p>`;
            formContainer.style.display = 'block';
        }
    });
});

function buildForm(car) {
    const formContainer = document.getElementById('reservationFormContainer');
    const savedInputs = JSON.parse(localStorage.getItem('reservationForm')) || {};

    formContainer.innerHTML = `
        <form id="reservationForm" action="/poti assignment 2/pages/submitted.php" method="POST" novalidate>
            <input type="hidden" name="vin" value="${car.vin}">
            <label>
                Name:
                <input type="text" name="name" value="${savedInputs.name || ''}" required>
                <span class="error-message" id="nameError"></span>
            </label><br>
            <label>
                Phone:
                <input type="tel" name="phone" value="${savedInputs.phone || ''}" required>
                <span class="error-message" id="phoneError"></span>
            </label><br>
            <label>
                Email:
                <input type="email" name="email" value="${savedInputs.email || ''}" required>
                <span class="error-message" id="emailError"></span>
            </label><br>
            <label>
                Driver's License:
                <input type="text" name="license" value="${savedInputs.license || ''}" required>
                <span class="error-message" id="licenseError"></span>
            </label><br>
            <label>
                Start Date:
                <input type="date" name="startDate" value="${savedInputs.startDate || ''}" required>
                <span class="error-message" id="dateError"></span>
            </label><br>
            <label>
                Rental Days:
                <input type="number" name="days" min="1" value="${savedInputs.days || ''}" required>
                <span class="error-message" id="daysError"></span>
            </label><br>
            <p id="totalPrice" style="padding-bottom: 5px;"></p>
            <button type="submit" id="submitBtn" disabled>Reserve</button>
            <button type="button" id="cancelBtn">Cancel</button>
        </form>
    `;

    formContainer.style.display = 'block';

    const form = document.getElementById('reservationForm');
    const submitBtn = document.getElementById('submitBtn');
    const totalPriceEl = document.getElementById('totalPrice');

    const runValidation = () => {
        const formData = new FormData(form);
        const formValues = Object.fromEntries(formData.entries());

        const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formValues.email);
        const phoneValid = formValues.phone?.trim().length == 10;
        const licenseValid = formValues.license?.trim().length > 0;
        const nameValid = formValues.name?.trim().length > 0;
        const daysValid = /^\d+$/.test(formValues.days) && parseInt(formValues.days) > 0;

        const startDate = form.querySelector('input[name="startDate"]');
        const startDateInput = new Date(formValues.startDate);
        const today = new Date();
        startDateInput.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        const dateValid = startDateInput >= today;

        const emailError = document.getElementById('emailError');
        const phoneError = document.getElementById('phoneError');
        const nameError = document.getElementById('nameError');
        const licenseError = document.getElementById('licenseError');
        const dateError = document.getElementById('dateError');
        const daysError = document.getElementById('daysError');

        const email = form.querySelector('input[name="email"]');
        const phone = form.querySelector('input[name="phone"]');
        const name = form.querySelector('input[name="name"]');
        const license = form.querySelector('input[name="license"]');
        const days = form.querySelector('input[name="days"]');

        if (!emailValid) {
            emailError.textContent = 'Please enter a valid email address.';
            email.style.borderColor = 'red';
        } else {
            emailError.textContent = '';
            email.style.borderColor = 'green';
        }

        if (!phoneValid) {
            phoneError.textContent = 'Please enter a valid mobile number.';
            phone.style.borderColor = 'red';
        } else {
            phoneError.textContent = '';
            phone.style.borderColor = 'green';
        }
        
        if (!nameValid) {
            nameError.textContent = 'Please enter your name.';
            name.style.borderColor = 'red';
        } else {
            nameError.textContent = '';
            name.style.borderColor = 'green';
        }

        if (!licenseValid) {
            licenseError.textContent = 'Please enter your license number.';
            license.style.borderColor = 'red';
        } else {
            licenseError.textContent = '';
            license.style.borderColor = 'green';
        }

        if (!dateValid) {
            dateError.textContent = 'Please enter a valid date.';
            startDate.style.borderColor = 'red';
        } else {
            dateError.textContent = '';
            startDate.style.borderColor = 'green';
        }

        if (!daysValid) {
            daysError.textContent = 'Rental days must be greater than 0.';
            days.style.borderColor = 'red';
        } else {
            daysError.textContent = '';
            days.style.borderColor = 'green';
        }

        const isValid = emailValid && phoneValid && licenseValid && nameValid && dateValid && daysValid;

        if (isValid) {
            const total = parseInt(formValues.days, 10) * car.pricePerDay;
            totalPriceEl.textContent = `Total Hire Cost: $${total}`;
            submitBtn.disabled = false;
        } else {
            totalPriceEl.textContent = '';
            submitBtn.disabled = true;
        }

        localStorage.setItem('reservationForm', JSON.stringify(formValues));
    };

    form.addEventListener('input', runValidation);

    if (Object.keys(savedInputs).length > 0) {
        runValidation();
    }

    document.getElementById('cancelBtn').addEventListener('click', () => {
        localStorage.clear();
        window.location.href = '/poti assignment 2/index.php';
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        document.getElementById('confirmationModal').classList.remove('hidden');
        car.available = false;
        localStorage.setItem('lastCar', JSON.stringify(car));
    });

    document.getElementById('confirmYes').addEventListener('click', () => {
        document.getElementById('confirmationModal').classList.add('hidden');
        localStorage.removeItem('reservationForm'); 
        car.available = true;
        localStorage.setItem('lastCar', JSON.stringify(car));
        form.submit();
    });

    document.getElementById('confirmNo').addEventListener('click', () => {
        document.getElementById('confirmationModal').classList.add('hidden');
        car.available = true;
        localStorage.setItem('lastCar', JSON.stringify(car));
    });
}