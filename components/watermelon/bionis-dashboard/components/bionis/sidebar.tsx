import {
  Activity,
  BookOpenCheck,
  LogOut,
  Computer,
  LayoutDashboard,
  Settings2,
} from 'lucide-react'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarSeparator,
} from '@/components/ui/sidebar'
import { useDashboardNavigation } from './navigation'

const groups = [
  {
    label: 'LAB OPERATIONS',
    items: [
      { label: 'Overview', href: '/admin', icon: LayoutDashboard },
      { label: 'Session history', href: '/admin/sessions', icon: BookOpenCheck },
      { label: 'Lab computers', href: '/admin/computers', icon: Computer },
    ],
  },
  {
    label: 'MANAGEMENT',
    items: [{ label: 'Dropdown options', href: '/admin/settings', icon: Settings2 }],
  },
]

export function DashboardSidebar() {
  const { pathname } = useDashboardNavigation()
  const csrf = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? ''

  return (
    <Sidebar collapsible="icon" className="h-full border-none">
      <SidebarHeader className="h-20 flex-row items-center gap-3 border-b border-sidebar-border px-4">
        <img src="/images/LZDS-LOGO.png" alt="LZDS" className="h-12 w-24 shrink-0 object-contain" />
        <div className="min-w-0 group-data-[collapsible=icon]:hidden">
          <p className="truncate text-sm font-semibold tracking-tight">LZDS</p>
          <p className="truncate text-xs text-muted-foreground">Computer Laboratory</p>
        </div>
      </SidebarHeader>
      <SidebarContent className="gap-5 overflow-x-hidden px-3 py-5">
        {groups.map((group) => (
          <SidebarGroup key={group.label} className="gap-2 p-0">
            <SidebarGroupLabel className="h-auto px-3 py-1 text-[10px] font-semibold tracking-[0.12em] text-muted-foreground">
              {group.label}
            </SidebarGroupLabel>
            <SidebarGroupContent>
              <SidebarMenu className="gap-1.5">
                {group.items.map(({ label, href, icon: Icon }) => {
                  const active = href === '/admin' ? pathname === href : pathname.startsWith(href)
                  return (
                    <SidebarMenuItem key={href}>
                      <SidebarMenuButton asChild isActive={active} tooltip={label} className="h-11 rounded-xl px-3 text-sm">
                        <a href={href} aria-current={active ? 'page' : undefined}>
                          <Icon className="size-4" /><span>{label}</span>
                        </a>
                      </SidebarMenuButton>
                    </SidebarMenuItem>
                  )
                })}
              </SidebarMenu>
            </SidebarGroupContent>
          </SidebarGroup>
        ))}
        <SidebarSeparator />
        <SidebarGroup className="gap-2 p-0">
          <SidebarGroupLabel className="h-auto px-3 py-1 text-[10px] font-semibold tracking-[0.12em] text-muted-foreground">QUICK LINK</SidebarGroupLabel>
          <SidebarGroupContent><SidebarMenu><SidebarMenuItem>
            <SidebarMenuButton asChild tooltip="Student logbook" className="h-11 rounded-xl px-3 text-sm">
              <a href="/"><Activity className="size-4" /><span>Student logbook</span></a>
            </SidebarMenuButton>
          </SidebarMenuItem></SidebarMenu></SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>
      <SidebarFooter className="border-t border-sidebar-border p-3">
        <form method="post" action="/admin/logout">
          <input type="hidden" name="_token" value={csrf} />
          <button type="submit" className="flex h-11 w-full items-center gap-3 rounded-xl px-3 text-sm text-muted-foreground transition-colors hover:bg-sidebar-accent hover:text-foreground">
            <LogOut className="size-4" /><span className="group-data-[collapsible=icon]:hidden">Sign out</span>
          </button>
        </form>
      </SidebarFooter>
    </Sidebar>
  )
}
