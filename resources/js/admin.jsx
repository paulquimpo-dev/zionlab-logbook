import React from 'react'
import { createRoot } from 'react-dom/client'
import DashboardLayout from '../../components/watermelon/bionis-dashboard/dashboard-layout'
import { DashboardNavigationProvider } from '../../components/watermelon/bionis-dashboard/components/bionis/navigation'
import { LogbookDashboard } from '../../components/watermelon/bionis-dashboard/logbook-dashboard'

const root = document.getElementById('logbook-admin-root')
const dataElement = document.getElementById('logbook-dashboard-data')

if (root) {
  const pageTitle = root.dataset.pageTitle || 'Overview'
  const contentElement = document.getElementById('logbook-admin-content')
  const pageContent = contentElement?.innerHTML || ''
  if (dataElement) {
    const data = JSON.parse(dataElement.textContent || '{}')
    createRoot(root).render(
      <DashboardNavigationProvider>
        <DashboardLayout pageTitle="Overview"><LogbookDashboard data={data} /></DashboardLayout>
      </DashboardNavigationProvider>,
    )
  } else {
  createRoot(root).render(
    <DashboardNavigationProvider>
      <DashboardLayout pageTitle={pageTitle}><div className="admin-page-content" dangerouslySetInnerHTML={{ __html: pageContent }} /></DashboardLayout>
    </DashboardNavigationProvider>,
  )
  }
}
