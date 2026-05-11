document.addEventListener('DOMContentLoaded', () => {
  const weightInput = document.getElementById('weight-value');
  const dateInput = document.getElementById('weight-date');
  const saveButton = document.querySelector('[data-action="updateWeightHistory"]');
  const displayWeight = document.getElementById('display-weight');
  const displayDate = document.getElementById('display-weight-date');
  const chartCanvas = document.getElementById('weightChart');
  let weightChart = null;

  const formatDate = (value) => {
    if (!value) return 'Jamais';
    const parts = value.slice(0, 10).split('-');
    return parts.length === 3 ? `${parts[2]}/${parts[1]}/${parts[0]}` : value;
  };

  const setButtonState = (isLoading) => {
    if (!saveButton) return;
    saveButton.disabled = isLoading;
    saveButton.classList.toggle('is-loading', isLoading);
    saveButton.innerHTML = isLoading
      ? '<i class="ti ti-loader-2" aria-hidden="true"></i> Enregistrement...'
      : '<i class="ti ti-device-floppy" aria-hidden="true"></i> Enregistrer';
  };

  const renderWeightChart = (chartData) => {
    if (!chartCanvas || typeof Chart === 'undefined') return;

    if (weightChart) {
      weightChart.destroy();
    }

    weightChart = new Chart(chartCanvas, {
      type: 'line',
      data: chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false },
        },
        scales: {
          x: { grid: { display: false }, ticks: { color: '#5a4a60' } },
          y: { beginAtZero: false, ticks: { color: '#5a4a60' } },
        },
        elements: {
          line: { tension: 0.35, borderWidth: 3 },
          point: { radius: 4, hoverRadius: 6 },
        },
      },
    });
  };

  const loadChart = async () => {
    if (!chartCanvas) return;

    try {
      const response = await fetch('/api/weight-chart', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });
      const result = await response.json();
      const chartData = result.data?.chart_data;

      if (result.success && chartData) {
        chartData.datasets = (chartData.datasets || []).map((dataset) => ({
          ...dataset,
          borderColor: '#663266',
          backgroundColor: 'rgba(102, 50, 102, 0.12)',
          fill: true,
        }));
        renderWeightChart(chartData);
      }
    } catch (error) {
      console.error('Impossible de charger le graphique du poids :', error);
    }
  };

  const saveWeight = async () => {
    const poids = Number.parseFloat(weightInput?.value || '0');
    const date = dateInput?.value || new Date().toISOString().slice(0, 10);

    if (!poids || poids <= 20) {
      alert('Veuillez saisir un poids valide.');
      weightInput?.focus();
      return;
    }

    const formData = new FormData();
    formData.append('poids', poids.toString());
    formData.append('date_mesure', date);

    setButtonState(true);

    try {
      const response = await fetch('/api/weight', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });
      const result = await response.json();

      if (!result.success) {
        const errors = result.errors ? Object.values(result.errors).join('\n') : result.error;
        alert(errors || "Impossible d'enregistrer le poids.");
        return;
      }

      if (displayWeight) displayWeight.textContent = poids.toFixed(1);
      if (displayDate) displayDate.textContent = `Dernière mise à jour : ${formatDate(date)}`;
      await loadChart();
    } catch (error) {
      console.error('Erreur pendant l_enregistrement du poids :', error);
      alert('Erreur de communication avec le serveur.');
    } finally {
      setButtonState(false);
    }
  };

  if (saveButton) {
    saveButton.addEventListener('click', (event) => {
      event.preventDefault();
      saveWeight();
    });
  }

  loadChart();
});
