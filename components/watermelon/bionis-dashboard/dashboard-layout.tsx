import type { CSSProperties, ReactNode } from 'react'
import { DashboardSidebar } from './components/bionis/sidebar'
import { DashboardTopbar } from './components/bionis/topbar'
import { SidebarProvider } from '@/components/ui/sidebar'
import { TooltipProvider } from '@/components/ui/tooltip'
import './dashboard.css'

type DashboardLayoutProps = {
  children: ReactNode
  pageTitle?: string
}

export default function DashboardLayout({ children, pageTitle = 'Overview' }: DashboardLayoutProps) {
  return (
    <TooltipProvider>
      <SidebarProvider
        defaultOpen
        className="bionis-dashboard h-svh overflow-hidden no-scrollbar"
        style={{ '--sidebar-width': '17rem', '--sidebar-width-icon': '4.25rem' } as CSSProperties}
      >
        <DashboardSidebar />
        <main className="flex min-w-0 flex-1 flex-col overflow-hidden">
          <DashboardTopbar pageTitle={pageTitle} />
          <div className="flex-1 overflow-y-auto px-4 py-6 md:px-8 md:py-8"><div className="mx-auto w-full max-w-[1600px]">{pageTitle !== 'Overview' && <div className="mb-6"><p className="mb-1 text-xs font-semibold uppercase tracking-[0.14em] text-primary">Administration</p><h1 className="text-2xl font-semibold tracking-tight">{pageTitle}</h1></div>}{children}</div></div>
        </main>
      </SidebarProvider>
    </TooltipProvider>
  )
}
