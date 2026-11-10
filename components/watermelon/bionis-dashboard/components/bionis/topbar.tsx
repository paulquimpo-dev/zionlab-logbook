import { SidebarTrigger } from '@/components/ui/sidebar'
import { Separator } from '@/components/ui/separator'

export function DashboardTopbar({ pageTitle = 'Overview' }: { pageTitle?: string }) {
  return (
    <header className="flex h-16 shrink-0 items-center gap-3 border-b px-5 md:px-8">
      <SidebarTrigger className="-ml-1" />
      <Separator orientation="vertical" className="h-5" />
      <div className="min-w-0">
        <p className="truncate text-sm font-medium">LZDS Computer Laboratory</p>
        <p className="truncate text-xs text-muted-foreground">{pageTitle}</p>
      </div>
      <div className="ml-auto flex items-center gap-2 text-xs text-muted-foreground">
        <span className="size-2 rounded-full bg-emerald-500" /> School logbook
      </div>
    </header>
  )
}
