import React from 'react'
import { createRoot } from 'react-dom/client'
import DashboardLayout from '../../components/watermelon/bionis-dashboard/dashboard-layout'
import { DashboardNavigationProvider } from '../../components/watermelon/bionis-dashboard/components/bionis/navigation'
import { LogbookDashboard } from '../../components/watermelon/bionis-dashboard/logbook-dashboard'

const root = document.getElementById('logbook-admin-root')
const dataElement = document.getElementById('logbook-dashboard-data')

if (root && dataElement) {
  const data = JSON.parse(dataElement.textContent || '{}')
  createRoot(root).render(
    <DashboardNavigationProvider>
      <DashboardLayout><LogbookDashboard data={data} /></DashboardLayout>
    </DashboardNavigationProvider>,
  )
}
