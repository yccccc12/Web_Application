// Function to filter data based on selected year and month
function renderDashboardCharts(config) {
    const genderLabels = config.genderLabels;
    const genderData = config.genderData;
    const availableYears = config.availableYears;
    const allMonthlyData = config.allMonthlyData;
    const allOrdersData = config.allOrdersData;

    // Gender Pie Chart
    const genderCtx = document.getElementById('genderPieChart').getContext('2d');
    const genderPieChart = new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: genderLabels,
            datasets: [{
                data: genderData,
                backgroundColor: ['#36A2EB', '#FF6384'],
                hoverOffset: 10
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Loads year selector dynamically
    const yearSelector = document.getElementById('yearSelector');
    availableYears.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelector.appendChild(option);
    });

    // Function to filter data by year
    function filterDataByYear(year) {
        const monthOrder = ['January', 'February', 'March', 'April', 'May', 'June', 
                            'July', 'August', 'September', 'October', 'November', 'December'];
    
        const filteredLabels = [];
        const filteredValues = [];
        const filteredOrders = [];
    
        for (const month of monthOrder) {
            filteredLabels.push(month);
            filteredValues.push(allMonthlyData[year]?.[month] || 0);
            filteredOrders.push(allOrdersData[year]?.[month] || 0);
        }
    
        return { labels: filteredLabels, values: filteredValues, orders: filteredOrders };
    }

    // Initial load using first year
    let { labels: initialLabels, values: initialValues, orders: initialOrders } = filterDataByYear(availableYears[0]);

    // Line chart for total amount spent
    const monthlyChartCtx = document.getElementById('timeSeriesChart').getContext('2d');
    const monthlyChart = new Chart(monthlyChartCtx, {
        type: 'line',
        data: {
            labels: initialLabels,
            datasets: [{
                label: 'Total Amount Spent (RM)',
                data: initialValues,
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                x: { title: { display: true, text: 'Month' }},
                y: { title: { display: true, text: 'Total Amount (RM)' }, beginAtZero: true }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Bar chart for total orders
    const ordersChartCtx = document.getElementById('ordersPerMonthChart').getContext('2d');
    const ordersChart = new Chart(ordersChartCtx, {
        type: 'bar',
        data: {
            labels: initialLabels,
            datasets: [{
                label: 'Total Orders per Month',
                data: initialOrders,
                backgroundColor: '#FF6384',
                borderColor: '#FF6384',
                borderWidth: 1,
                barThickness: 50
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { 
                    title: { display: true, text: 'Month' },
                    ticks: { 
                        maxRotation: 60, 
                        minRotation: 60 
                    }
                },
                y: { 
                    title: { display: true, text: 'Total Orders' }, 
                    beginAtZero: true 
                }
            }
        }
    });

    // Update charts and stats when year is changed
    yearSelector.addEventListener('change', () => {
        const selectedYear = yearSelector.value;
        const { labels, values, orders } = filterDataByYear(selectedYear);
    
        // Update charts
        monthlyChart.data.labels = labels;
        monthlyChart.data.datasets[0].data = values;
        monthlyChart.update();
    
        ordersChart.data.labels = labels;
        ordersChart.data.datasets[0].data = orders;
        ordersChart.update();
    
        // Update stats
        const stats = yearlyStats[selectedYear];
    
        // Total amount
        document.getElementById('totalAmount').textContent = `RM ${stats.totalAmount.toFixed(2)}`;
    
        // Average spending
        const avg = stats.orderCount ? (stats.totalAmount / stats.orderCount).toFixed(2) : "0.00";
        document.getElementById('averageSpending').textContent = `RM ${avg}`;
    
        // Top color
        let topColor = 'N/A';
        let topCount = 0;
        for (const color in stats.colors) {
            if (stats.colors[color] > topCount) {
                topColor = color;
                topCount = stats.colors[color];
            }
        }
        document.getElementById('topColor').textContent = topColor;
        document.getElementById('topColorCount').textContent = `Count: ${topCount}`;
    
        // Gender chart update
        const genderStats = stats.gender;
        genderPieChart.data.labels = Object.keys(genderStats);
        genderPieChart.data.datasets[0].data = Object.values(genderStats);
        genderPieChart.update();
    });
}

// Initialize everything once the page is loaded
document.addEventListener('DOMContentLoaded', function () {
    const defaultYear = availableYears[0];
    const genderStats = yearlyStats[defaultYear].gender;

    const genderLabels = Object.keys(genderStats);
    const genderData = Object.values(genderStats);

    renderDashboardCharts({
        genderLabels: genderLabels,
        genderData: genderData,
        availableYears: availableYears,
        allMonthlyData: allMonthlyData,
        allOrdersData: allOrdersData
    });
});