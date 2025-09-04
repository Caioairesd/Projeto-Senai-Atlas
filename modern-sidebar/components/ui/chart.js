// Chart component wrapper for Chart.js
export const Chart = window.Chart || null

export const ChartContainer = ({ children, ...props }) => {
  return <div {...props}>{children}</div>
}

export const ChartTooltip = ({ children, ...props }) => {
  return <div {...props}>{children}</div>
}

export const ChartTooltipContent = ({ children, ...props }) => {
  return <div {...props}>{children}</div>
}

export const ChartLegend = ({ children, ...props }) => {
  return <div {...props}>{children}</div>
}

export const ChartLegendContent = ({ children, ...props }) => {
  return <div {...props}>{children}</div>
}

export const ChartStyle = ({ children, ...props }) => {
  return <div {...props}>{children}</div>
}
