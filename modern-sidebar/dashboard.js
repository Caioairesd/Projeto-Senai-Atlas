import { Chart } from "@/components/ui/chart"
// Dashboard JavaScript
document.addEventListener("DOMContentLoaded", () => {
  // Sidebar toggle functionality
  const sidebarToggle = document.querySelector(".sidebar-toggle")
  const sidebar = document.querySelector(".sidebar")

  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
      sidebar.classList.toggle("open")
    })
  }

  // Initialize Charts
  initializeCharts()
})

function initializeCharts() {
  // Sales Chart
  const salesCtx = document.getElementById("salesChart")
  if (salesCtx) {
    new Chart(salesCtx, {
      type: "line",
      data: {
        labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun"],
        datasets: [
          {
            label: "Vendas (R$)",
            data: [12000, 19000, 15000, 25000, 22000, 30000],
            borderColor: "#3B82F6",
            backgroundColor: "rgba(59, 130, 246, 0.1)",
            tension: 0.4,
            fill: true,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: (value) => "R$ " + value.toLocaleString(),
            },
          },
        },
      },
    })
  }

  // Category Chart
  const categoryCtx = document.getElementById("categoryChart")
  if (categoryCtx) {
    new Chart(categoryCtx, {
      type: "doughnut",
      data: {
        labels: ["Hardware", "Software", "Periféricos", "Acessórios"],
        datasets: [
          {
            data: [35, 25, 25, 15],
            backgroundColor: ["#3B82F6", "#10B981", "#FBBF24", "#EF4444"],
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
          },
        },
      },
    })
  }

  // Orders Chart
  const ordersCtx = document.getElementById("ordersChart")
  if (ordersCtx) {
    new Chart(ordersCtx, {
      type: "bar",
      data: {
        labels: ["Pendente", "Processando", "Enviado", "Entregue", "Cancelado"],
        datasets: [
          {
            label: "Pedidos",
            data: [15, 25, 30, 45, 5],
            backgroundColor: ["#FBBF24", "#3B82F6", "#10B981", "#059669", "#EF4444"],
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    })
  }

  // Stock Chart
  const stockCtx = document.getElementById("stockChart")
  if (stockCtx) {
    new Chart(stockCtx, {
      type: "line",
      data: {
        labels: ["Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"],
        datasets: [
          {
            label: "Entradas",
            data: [65, 59, 80, 81, 56, 55, 40],
            borderColor: "#10B981",
            backgroundColor: "rgba(16, 185, 129, 0.1)",
            tension: 0.4,
          },
          {
            label: "Saídas",
            data: [28, 48, 40, 19, 86, 27, 90],
            borderColor: "#EF4444",
            backgroundColor: "rgba(239, 68, 68, 0.1)",
            tension: 0.4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
          },
        },
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    })
  }
}

// Navigation functionality
document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", function (e) {
    e.preventDefault()

    // Remove active class from all nav items
    document.querySelectorAll(".nav-item").forEach((item) => {
      item.classList.remove("active")
    })

    // Add active class to clicked item
    this.parentElement.classList.add("active")
  })
})
