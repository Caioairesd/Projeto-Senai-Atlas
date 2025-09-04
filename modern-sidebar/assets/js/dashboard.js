/**
 * Dashboard JavaScript Functions
 * Hardware Inventory Management System
 */

// Initialize dashboard when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  initializeDashboard()
})

/**
 * Initialize dashboard functionality
 */
function initializeDashboard() {
  // Initialize sidebar toggle
  initSidebarToggle()

  // Initialize tooltips
  initTooltips()

  // Initialize auto-refresh for metrics
  initAutoRefresh()

  // Initialize search functionality
  initSearch()
}

/**
 * Sidebar toggle functionality
 */
function initSidebarToggle() {
  const sidebarToggle = document.getElementById("sidebarToggle")
  const sidebar = document.getElementById("sidebar")
  const sidebarOverlay = document.getElementById("sidebarOverlay")

  if (sidebarToggle && sidebar && sidebarOverlay) {
    sidebarToggle.addEventListener("click", toggleSidebar)
    sidebarOverlay.addEventListener("click", closeSidebar)

    // Close sidebar on escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        closeSidebar()
      }
    })
  }
}

/**
 * Toggle sidebar visibility
 */
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar")
  const sidebarOverlay = document.getElementById("sidebarOverlay")

  sidebar.classList.toggle("-translate-x-full")
  sidebarOverlay.classList.toggle("hidden")
}

/**
 * Close sidebar
 */
function closeSidebar() {
  const sidebar = document.getElementById("sidebar")
  const sidebarOverlay = document.getElementById("sidebarOverlay")

  sidebar.classList.add("-translate-x-full")
  sidebarOverlay.classList.add("hidden")
}

/**
 * Initialize tooltips for better UX
 */
function initTooltips() {
  const tooltipElements = document.querySelectorAll("[data-tooltip]")

  tooltipElements.forEach((element) => {
    element.addEventListener("mouseenter", showTooltip)
    element.addEventListener("mouseleave", hideTooltip)
  })
}

/**
 * Show tooltip
 */
function showTooltip(event) {
  const element = event.target
  const tooltipText = element.getAttribute("data-tooltip")

  if (tooltipText) {
    const tooltip = document.createElement("div")
    tooltip.className = "absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg"
    tooltip.textContent = tooltipText
    tooltip.id = "tooltip"

    document.body.appendChild(tooltip)

    const rect = element.getBoundingClientRect()
    tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + "px"
    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + "px"
  }
}

/**
 * Hide tooltip
 */
function hideTooltip() {
  const tooltip = document.getElementById("tooltip")
  if (tooltip) {
    tooltip.remove()
  }
}

/**
 * Initialize auto-refresh for dashboard metrics
 */
function initAutoRefresh() {
  // Refresh metrics every 5 minutes
  setInterval(refreshMetrics, 300000)
}

/**
 * Refresh dashboard metrics via AJAX
 */
function refreshMetrics() {
  fetch("api/dashboard-metrics.php")
    .then((response) => response.json())
    .then((data) => {
      updateMetricCards(data)
    })
    .catch((error) => {
      console.error("Error refreshing metrics:", error)
    })
}

/**
 * Update metric cards with new data
 */
function updateMetricCards(data) {
  // Update total items
  const totalItemsElement = document.querySelector('[data-metric="total-items"]')
  if (totalItemsElement && data.totalItems) {
    totalItemsElement.textContent = data.totalItems.toLocaleString()
  }

  // Update low stock count
  const lowStockElement = document.querySelector('[data-metric="low-stock"]')
  if (lowStockElement && data.lowStock) {
    lowStockElement.textContent = data.lowStock
  }

  // Update inventory value
  const inventoryValueElement = document.querySelector('[data-metric="inventory-value"]')
  if (inventoryValueElement && data.inventoryValue) {
    inventoryValueElement.textContent = formatCurrency(data.inventoryValue)
  }
}

/**
 * Initialize search functionality
 */
function initSearch() {
  const searchInput = document.getElementById("searchInput")
  if (searchInput) {
    searchInput.addEventListener("input", debounce(performSearch, 300))
  }
}

/**
 * Perform search with debouncing
 */
function performSearch(event) {
  const query = event.target.value.trim()

  if (query.length >= 2) {
    fetch(`api/search.php?q=${encodeURIComponent(query)}`)
      .then((response) => response.json())
      .then((data) => {
        displaySearchResults(data)
      })
      .catch((error) => {
        console.error("Search error:", error)
      })
  } else {
    hideSearchResults()
  }
}

/**
 * Display search results
 */
function displaySearchResults(results) {
  const searchResults = document.getElementById("searchResults")
  if (searchResults) {
    searchResults.innerHTML = ""

    if (results.length > 0) {
      results.forEach((result) => {
        const resultElement = createSearchResultElement(result)
        searchResults.appendChild(resultElement)
      })
      searchResults.classList.remove("hidden")
    } else {
      searchResults.innerHTML = '<div class="p-4 text-gray-500">Nenhum resultado encontrado</div>'
      searchResults.classList.remove("hidden")
    }
  }
}

/**
 * Create search result element
 */
function createSearchResultElement(result) {
  const div = document.createElement("div")
  div.className = "p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100"
  div.innerHTML = `
        <div class="font-medium text-gray-900">${result.name}</div>
        <div class="text-sm text-gray-600">${result.category} - ${result.sku}</div>
    `

  div.addEventListener("click", () => {
    window.location.href = `inventory.php?item=${result.id}`
  })

  return div
}

/**
 * Hide search results
 */
function hideSearchResults() {
  const searchResults = document.getElementById("searchResults")
  if (searchResults) {
    searchResults.classList.add("hidden")
  }
}

/**
 * Utility function for debouncing
 */
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

/**
 * Format currency for display
 */
function formatCurrency(amount) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(amount)
}

/**
 * Show loading state
 */
function showLoading(element) {
  element.classList.add("loading")
}

/**
 * Hide loading state
 */
function hideLoading(element) {
  element.classList.remove("loading")
}

/**
 * Show notification
 */
function showNotification(message, type = "info") {
  const notification = document.createElement("div")
  notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${getNotificationClasses(type)}`
  notification.textContent = message

  document.body.appendChild(notification)

  // Auto-remove after 5 seconds
  setTimeout(() => {
    notification.remove()
  }, 5000)
}

/**
 * Get notification CSS classes based on type
 */
function getNotificationClasses(type) {
  switch (type) {
    case "success":
      return "bg-green-500 text-white"
    case "error":
      return "bg-red-500 text-white"
    case "warning":
      return "bg-yellow-500 text-white"
    default:
      return "bg-blue-500 text-white"
  }
}
