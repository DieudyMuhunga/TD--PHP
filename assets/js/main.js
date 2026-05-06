/**
 * Scripts principaux du SGA
 */

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialiser les popovers Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Masquer les alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

/**
 * Confirme une action avant de la soumettre
 */
function confirmer(message = 'Êtes-vous sûr?') {
    return confirm(message);
}

/**
 * Valide un formulaire
 */
function validerFormulaire(formulaire) {
    if (!formulaire.reportValidity()) {
        alert('Veuillez remplir tous les champs obligatoires');
        return false;
    }
    return true;
}

/**
 * Formate un nombre en devise
 */
function formatMontant(nombre) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(nombre);
}

/**
 * Formate une date
 */
function formatDate(dateStr) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateStr).toLocaleDateString('fr-FR', options);
}

/**
 * Affiche une notification toast
 */
function afficherToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    const toastContainer = document.querySelector('.toast-container') || document.body;
    const toastElement = document.createElement('div');
    toastElement.innerHTML = toastHtml;
    toastContainer.appendChild(toastElement);
    const toast = new bootstrap.Toast(toastElement.querySelector('.toast'));
    toast.show();
}

/**
 * Exporte un tableau en CSV
 */
function exporterCSV(tableId, nomFichier) {
    const table = document.getElementById(tableId);
    if (!table) {
        alert('Tableau non trouvé');
        return;
    }

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        let csvRow = [];
        const cells = row.querySelectorAll('td, th');
        cells.forEach(cell => {
            csvRow.push('"' + cell.innerText.trim() + '"');
        });
        csv.push(csvRow.join(','));
    });

    const csvContent = 'data:text/csv;charset=utf-8,' + csv.join('\n');
    const link = document.createElement('a');
    link.setAttribute('href', encodeURI(csvContent));
    link.setAttribute('download', nomFichier + '.csv');
    link.click();
}

/**
 * Imprime un élément
 */
function imprimer(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        alert('Élément non trouvé');
        return;
    }

    const printWindow = window.open('', '', 'height=400,width=800');
    printWindow.document.write('<html><head><title>Impression</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
    printWindow.document.write('</head><body>');
    printWindow.document.write(element.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}