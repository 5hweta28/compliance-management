// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})

document.addEventListener('DOMContentLoaded', function() {
	const menuItems = document.querySelectorAll('.side-menu li');
	const contentSections = document.querySelectorAll('.content-section');

	menuItems.forEach(item => {
		item.addEventListener('click', function() {
			// Remove 'active' class from all menu items
			menuItems.forEach(item => item.classList.remove('active'));
			// Add 'active' class to the clicked menu item
			this.classList.add('active');

			// Get the content id to be displayed
			const contentId = this.getAttribute('data-content');

			// Hide all content sections
			contentSections.forEach(section => section.style.display = 'none');
			// Show the selected content section
			document.getElementById(contentId).style.display = 'block';
		});
	});

	// By default, show the dashboard content
	document.getElementById('dashboard').style.display = 'block';
});

document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('edit-button');
    const viewButton = document.getElementById('view-button');
    const saveEditButton = document.getElementById('save-edit-button');
    const cancelEditButton = document.getElementById('cancel-edit-button');
    const backViewButton = document.getElementById('back-view-button');

    // Event listener for Edit button
    editButton.addEventListener('click', function() {
        showEditProfile();
    });

    // Event listener for View button
    viewButton.addEventListener('click', function() {
        showViewProfile();
    });

    // Event listener for Save Edit button
    saveEditButton.addEventListener('click', function() {
        saveEditedProfile();
    });

    // Event listener for Cancel Edit button
    cancelEditButton.addEventListener('click', function() {
        hideEditProfile();
    });

    // Event listener for Back View button
    backViewButton.addEventListener('click', function() {
        hideViewProfile();
    });

    // Fetch user profile data on page load
    fetchUserProfile();

    // Fetch task counts on page load
    fetchTaskCounts();

    // Fetch tasks on page load
    fetchTasks();
});

function fetchUserProfile() {
    // Fetch user profile data using AJAX or fetch API
    fetch('fetch_profile.php')
        .then(response => response.json())
        .then(data => {
            // Populate profile info in profile-info section
            document.getElementById('profile-name').textContent = data.name;
            document.getElementById('profile-email').textContent = 'Email: ' + data.email;
        })
        .catch(error => console.error('Error fetching profile:', error));
}

function showEditProfile() {
    document.getElementById('profile-info').style.display = 'none';
    document.getElementById('edit-profile').style.display = 'block';

    // Prefill edit form with existing profile data
    fetch('fetch_profile.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-name').value = data.name;
            document.getElementById('edit-phone').value = data.phone;
            document.getElementById('edit-location').value = data.location;
        })
        .catch(error => console.error('Error fetching profile for edit:', error));
}

function saveEditedProfile() {
    const newName = document.getElementById('edit-name').value;
    const newPhone = document.getElementById('edit-phone').value;
    const newLocation = document.getElementById('edit-location').value;

    const formData = new FormData();
    formData.append('name', newName);
    formData.append('phone', newPhone);
    formData.append('location', newLocation);

    fetch('update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Update UI with saved data
        document.getElementById('profile-name').textContent = data.name;
        hideEditProfile();
    })
    .catch(error => console.error('Error saving edited profile:', error));
}

function hideEditProfile() {
    document.getElementById('edit-profile').style.display = 'none';
    document.getElementById('profile-info').style.display = 'block';
}

function showViewProfile() {
    document.getElementById('profile-info').style.display = 'none';
    document.getElementById('view-profile').style.display = 'block';

    // Fetch and display all profile details
    fetch('fetch_profile.php')
        .then(response => response.json())
        .then(data => {
            const profileDetailsDiv = document.getElementById('profile-details');
            profileDetailsDiv.innerHTML = ''; // Clear previous content

            for (const key in data) {
                if (data.hasOwnProperty(key) && data[key] !== null && key !== 'password' && key !== 'userID' && key !== 'email') {
                    const detailElement = document.createElement('p');
                    detailElement.textContent = `${key}: ${data[key]}`;
                    profileDetailsDiv.appendChild(detailElement);
                }
            }
        })
        .catch(error => console.error('Error fetching profile for view:', error));
}

function hideViewProfile() {
    document.getElementById('view-profile').style.display = 'none';
    document.getElementById('profile-info').style.display = 'block';
}

function fetchTaskCounts() {
    fetch('fetch_task_counts.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('assigned-doc-count').textContent = data.assigned_doc_count;
            document.getElementById('pending-task-count').textContent = data.pending_task_count;
            document.getElementById('completed-task-count').textContent = data.completed_task_count;
        })
        .catch(error => console.error('Error fetching task counts:', error));
}

function fetchTasks() {
    fetch('fetch_tasks.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#task tbody');
            tbody.innerHTML = ''; // Clear existing rows

            data.forEach((task, index) => {
                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${task.doc_name}</td>
                    <td>${task.type}</td>
                    <td>${task.date}</td>
                    <td><span class="status ${task.status}">${task.status}</span></td>
                    <td><img src="img/img.png" alt="CA"></td>
                    <td>${task.completion_status}</td>
                `;

                tbody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching tasks:', error));
}



const API_KEY = "82a38375b441ab5d64bc389baa87f04a"; // Replace with your Mindee API key
const API_ENDPOINT = "https://api.mindee.net/v1/products/mindee/financial_document/v1/predict";
const MAX_RETRIES = 3;
const RETRY_DELAY = 1000; // 1 second

const mindeeSubmit = (event) => {
    event.preventDefault();
    let myFileInput = document.getElementById('my-file-input');
    let myFile = myFileInput.files[0];
    if (!myFile) {
        alert("Please select a file.");
        return;
    }

    let data = new FormData();
    data.append("document", myFile, myFile.name);

    sendRequest(data, MAX_RETRIES);
};

const sendRequest = (data, retriesLeft) => {
    let xhr = new XMLHttpRequest();

    xhr.addEventListener("readystatechange", function () {
        if (this.readyState === 4) {
            if (this.status === 200 || this.status === 201) {
                const response = JSON.parse(this.responseText);
                const prediction = response.document.inference.prediction;

                const reader = new FileReader();
                reader.onload = () => {
                    const documentData = {
                        document_type: prediction.document_type ? prediction.document_type.value : "N/A",
                        file_name: data.get('document').name,
                        purchase_date: prediction.date ? prediction.date.value : "N/A",
                        due_date: prediction.due_date ? prediction.due_date.value : "N/A",
                        total_amount: prediction.total_amount ? prediction.total_amount.value : "N/A",
                        supplier_name: prediction.supplier_name ? prediction.supplier_name.value : "N/A",
                        document_number: prediction.document_number ? prediction.document_number.value : "N/A",
                        doc_image: reader.result // Base64 encoded image
                    };

                    // Populate the form fields with the extracted data
                    document.getElementById('document-type').value = documentData.document_type;
                    document.getElementById('file-name').value = documentData.file_name;
                    document.getElementById('purchase-date').value = documentData.purchase_date;
                    document.getElementById('due-date').value = documentData.due_date;
                    document.getElementById('total-amount').value = documentData.total_amount;
                    document.getElementById('supplier-name').value = documentData.supplier_name;
                    document.getElementById('document-number').value = documentData.document_number;

                    // Send the extracted data to the backend
                    saveDocumentData(documentData);
                };
                reader.readAsDataURL(data.get('document')); // Convert the image to Base64
            } else if (this.status === 429 && retriesLeft > 0) {
                setTimeout(() => {
                    sendRequest(data, retriesLeft - 1);
                }, RETRY_DELAY);
            } else {
                alert(`Error processing document. Status ${this.status}: ${this.statusText}`);
            }
        }
    });

    xhr.open("POST", API_ENDPOINT);
    xhr.setRequestHeader("Authorization", `Token ${API_KEY}`);
    xhr.send(data);
};

const saveDocumentData = (documentData) => {
    fetch('save_document_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(documentData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Document data saved successfully.');
            // Fetch tasks to update the task table
            fetchTasks();
        } else {
            alert('Error saving document data: ' + data.error);
        }
    })
    .catch(error => console.error('Error saving document data:', error));
};
