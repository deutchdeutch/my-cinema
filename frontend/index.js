let moviePage = 1;
const moviesPage = 5;
let roomPage = 1;
const roomsPage = 5;
const API_URL = 'https://mon-api-cinema.up.railway.app';

// --- FONCTION DE LISTAGE ---
function fetchData(action, page = 1) {
    console.log("appel de l'action:", action, "page:", moviePage);
    if (action === 'list_movie') {
        moviePage = page;
    } else if (action === 'list_room') {
        roomPage = page;
    }
    fetch(`${API_URL}?action=${action}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('api-result');
            let displayData = data;
            // découpage action = movie
            if (action === 'list_movie') {
                const start = (moviePage - 1) * moviesPage;
                const end = start + moviesPage;
                displayData = data.slice(start, end);
            }
            // découpage action = room
            else if (action === 'list_room') {
                const start = (roomPage - 1) * roomsPage;
                const end = start + roomsPage;
                displayData = data.slice(start, end);
            }

            //  Définition des colonnes selon l'action
            let headers = "";
            if (action === 'list_movie') {
                headers = `<th>ID</th><th>Titre</th><th>Genre</th><th>Durée</th><th>Réalisateur</th><th>Année</th>`;
            } else if (action === 'list_room') {
                headers = `<th>ID</th><th>Nom</th><th>Capacité</th><th>Type</th><th>Statut</th>`;
            } else if (action === 'list_screening') {
                headers = `<th>ID</th><th>Film</th><th>Genre</th><th>Salle</th><th>Début</th>`;
            }

            let html = `<table class="w-full text-xs text-left uppercase font-mono border-collapse">
                <thead class="text-red-500 border-b border-gray-700">
                    <tr>${headers}<th class="p-2">Action</th></tr>
                </thead>
                <tbody>`;

            displayData.forEach(item => {
                let rows = "";

                //  Remplissage des cellules selon les données reçues
                // --- données movies ---
                if (action === 'list_movie') {
                    rows = `
                        <td class="p-2 text-blue-300 font-bold">${item.id}</td>
                        <td class="p-2 text-blue-300 font-bold">${item.title}</td>
                        <td class="p-2">${item.genre || '-'}</td>
                        <td class="p-2">${item.duration} min</td>
                        <td class="p-2">${item.director}</td>
                        <td class="p-2">${item.release_year}</td>`;
                    // --- données room ---
                } else if (action === 'list_room') {
                    rows = `
                        <td class="p-2 text-green-300 font-bold">${item.id}</td>
                        <td class="p-2 text-green-300 font-bold">${item.name}</td>
                        <td class="p-2">${item.capacity} places</td>
                        <td class="p-2">${item.type || 'Standard'}</td>
                        <td class="p-2">${item.active == 1 ? '✅' : '❌'}</td>`;
                    // --- données screening ---
                } else if (action === 'list_screening') {
                    rows = `
                        <td class="p-2 text-yellow-300 font-bold">${item.id}</td>
                        <td class="p-2 text-yellow-300 font-bold">${item.movie_title}</td>
                        <td class="p-2">${item.movie_genre}</td>
                        <td class="p-2">${item.room_name}</td>
                        <td class="p-2">${item.start_time}</td>`;
                }

                const deleteAction = action.replace('list_', 'delete_');

                // On transforme l'objet en texte JSON sécurisé pour le passer dans la fonction
                const itemData = JSON.stringify(item).replace(/"/g, '&quot;');

                html += `
                <tr class="border-b border-gray-800 hover:bg-gray-900 transition-colors">
                    ${rows}
                    <td class="p-2 flex gap-2">
                        <button onclick="prepareUpdate('${action}', ${itemData})" 
                            class="text-green-500 hover:text-white hover:bg-blue-600 px-2 py-1 rounded text-xs transition">
                            Modifier
                        </button>
                        <button onclick="deleteItem('${deleteAction}', ${item.id})" 
                            class="text-red-500 hover:text-white hover:bg-red-600 px-2 py-1 rounded text-xs transition">
                            Supprimer
                        </button>
                    </td>
                </tr>`;

            });

            html += `</tbody></table>`;
            // --- pagination ---
            if (action === 'list_movie') {
                const totalPages = Math.ceil(data.length / moviesPage);
                html += `
                <div class="flex justify-center items-center gap-4 mt-6">
                    <button onclick="fetchData('list_movie', ${moviePage - 1})" 
                        ${moviePage <= 1 ? 'disabled' : ''} 
                        class="bg-gray-700 px-4 py-2 rounded disabled:opacity-30 hover:bg-red-600 transition">
                        Précédent
                    </button>
                    
                    <span class="text-red-500 font-bold">Page ${moviePage} sur ${totalPages}</span>
                    
                    <button onclick="fetchData('list_movie', ${moviePage + 1})" 
                        ${moviePage >= totalPages ? 'disabled' : ''} 
                        class="bg-gray-700 px-4 py-2 rounded disabled:opacity-30 hover:bg-red-600 transition">
                        Suivant
                    </button>
                </div>`;
            }
            if (action === 'list_room') {
                const totalPages = Math.ceil(data.length / roomsPage);
                html += `
                <div class="flex justify-center items-center gap-4 mt-6">
                    <button onclick="fetchData('list_room', ${roomPage - 1})" 
                        ${roomPage <= 1 ? 'disabled' : ''} 
                        class="bg-gray-700 px-4 py-2 rounded disabled:opacity-30 hover:bg-red-600 transition">
                        Précédent
                    </button>
                    
                    <span class="text-red-500 font-bold">Page ${roomPage} sur ${totalPages}</span>
                    
                    <button onclick="fetchData('list_room', ${roomPage + 1})" 
                        ${roomPage >= totalPages ? 'disabled' : ''} 
                        class="bg-gray-700 px-4 py-2 rounded disabled:opacity-30 hover:bg-red-600 transition">
                        Suivant
                    </button>
                </div>`;
            }

            container.innerHTML = html;
        });
}

// --- FONCTION D'AJOUT GÉNÉRIQUE ---
function handleFormSubmit(formId, actionDefault, listAction) {
    const form = document.getElementById(formId);
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Transforme les champs du formulaire en objet JSON
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const isUpdate = form.dataset.mode === 'update';
        const updateAction = form.dataset.updateAction;
        const id = form.dataset.id;
        let url = `${API_URL}?action=${actionDefault}`;
        if (isUpdate && updateAction && id) {
            url = `${API_URL}?action=${updateAction}&id=${id}`;
        }
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(res => res.json())
            .then(res => {
                if(res.error){
                    alert("Erreur: " + (typeof res.error === 'object' ? res.error.error : res.error));
                }else{
                alert(res.message || res.error);}
                form.reset();
                if (isUpdate) {
                    delete form.dataset.mode;
                    delete form.dataset.id;
                    delete form.dataset.updateAction;

                    const btn = form.querySelector('button[type="submit"]');
                    btn.innerText = "Ajouter au catalogue";
                    btn.classList.remove('bg-green-600');
                    btn.classList.add('bg-red-600');
                    // On vide le champ caché id si présent
                    const hiddenId = form.querySelector('input[type="hidden"]');
                    if (hiddenId) hiddenId.value = "";
                }
                fetchData(listAction); // Rafraîchit la liste
            });
    });
}

// --- FONCTION UPDATE ---

function prepareUpdate(action, item) {
    let formId = "";
    if (action === 'list_movie') formId = 'add-movie-form';
    else if (action === 'list_room') formId = 'add-room-form';
    else if (action === 'list_screening') formId = 'add-screening-form';

    const form = document.getElementById(formId);

    Object.keys(item).forEach(key => {
        const input = form.querySelector(`[name="${key}"]`);
        if (input) input.value = item[key];
    });
    form.dataset.mode = 'update';
    form.dataset.id = item.id;
    form.dataset.updateAction = action.replace('list_', 'update_');

    //  On change le bouton pour montrer qu'on est en mode Edition
    const btn = form.querySelector('button[type="submit"]');
    btn.innerText = "Mettre à jour";
    btn.classList.replace('bg-red-600', 'bg-green-600');

    // On stocke l'ID et l'action de mise à jour dans le formulaire
    form.dataset.mode = 'update';
    form.dataset.id = item.id;
    form.dataset.updateAction = action.replace('list_', 'update_');

    //  on remonte vers le formulaire
    form.scrollIntoView({ behavior: 'smooth' });
}
// --- 


// --- FONCTION DE SUPPRESSION ---
function deleteItem(action, id) {
    if (confirm('Supprimer cet élément ?')) {
        fetch(`${API_URL}?action=${action}&id=${id}`)
            .then(res => res.json())
            .then(res => {
                alert(res.message || "Supprimé");
                // On déduit la liste à rafraîchir
                const list = action.includes('movie') ? 'list_movie' :
                    action.includes('room') ? 'list_room' : 'list_screening';
                fetchData(list);
            });
    }
}

// Appel des fonctions
handleFormSubmit('add-movie-form', 'add_movie', 'list_movie');
handleFormSubmit('add-room-form', 'add_room', 'list_room');
handleFormSubmit('add-screening-form', 'add_screening', 'list_screening');