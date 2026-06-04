// Simulating PHP fetch with JavaScript for demonstration
document.addEventListener("DOMContentLoaded", function () {
  // Function to show loading animation
  function showLoading() {
    document.querySelector(".loading").style.display = "flex";
  }

  // Function to hide loading animation
  function hideLoading() {
    document.querySelector(".loading").style.display = "none";
  }

  // Function to fetch data from server (simulated)
  function fetchDashboardData() {
    showLoading();

    // Simulate AJAX request delay
    setTimeout(() => {
      // This would be replaced with actual AJAX call to PHP script
      // Example:
      // fetch('get_dashboard_data.php')
      //    .then(response => response.json())
      //    .then(data => updateDashboard(data))
      //    .catch(error => console.error('Error fetching data:', error))
      //    .finally(() => hideLoading());

      // Simulate random data for demonstration
      const data = {
        totalSales: "$" + (Math.floor(Math.random() * 2000) + 800),
        weeklyRevenue: "$" + (Math.floor(Math.random() * 5000) + 5000),
        monthlyOrders: Math.floor(Math.random() * 100) + 300,
        newUsers: Math.floor(Math.random() * 20) + 15,
        abandonedCarts: Math.floor(Math.random() * 10) + 10,
        lowInventory: Math.floor(Math.random() * 5) + 5,
      };

      updateDashboard(data);
      hideLoading();
    }, 1000);
  }

  // Function to update dashboard with fetched data
  function updateDashboard(data) {
    document.getElementById("total-sales").textContent = data.totalSales;
    document.getElementById("weekly-revenue").textContent = data.weeklyRevenue;
    document.getElementById("monthly-orders").textContent = data.monthlyOrders;
    document.getElementById("new-users").textContent = data.newUsers;
    document.getElementById("abandoned-carts").textContent =
      data.abandonedCarts;
    document.getElementById("low-inventory").textContent = data.lowInventory;
  }

  // Initial data fetch
  fetchDashboardData();

  // Refresh button event listener
  document
    .getElementById("refreshStats")
    .addEventListener("click", fetchDashboardData);

  // Add click listeners to quick access panels
  const panels = document.querySelectorAll(".panel");
  panels.forEach((panel) => {
    panel.addEventListener("click", function () {
      const panelId = this.id;
      // In a real application, this would navigate to different pages
      alert(`Navigating to ${panelId.replace("-", " ")}`);
    });
  });

  // Auto refresh every 5 minutes (300000 ms)
  // setInterval(fetchDashboardData, 300000);
});
