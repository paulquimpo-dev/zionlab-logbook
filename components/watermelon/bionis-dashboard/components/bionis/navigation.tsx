import { createContext, useContext, useEffect, useState, type AnchorHTMLAttributes, type ReactNode } from 'react'

const DashboardNavigationContext = createContext({ pathname: '/' })

export function DashboardNavigationProvider({ children }: { children: ReactNode }) {
  const [pathname, setPathname] = useState(() => window.location.pathname)
  useEffect(() => {
    const update = () => setPathname(window.location.pathname)
    window.addEventListener('popstate', update)
    return () => window.removeEventListener('popstate', update)
  }, [])
  return <DashboardNavigationContext.Provider value={{ pathname }}>{children}</DashboardNavigationContext.Provider>
}

export function useDashboardNavigation() {
  return useContext(DashboardNavigationContext)
}

export function DashboardLink({ href, children, ...props }: AnchorHTMLAttributes<HTMLAnchorElement> & { href: string }) {
  return <a href={href} {...props}>{children}</a>
}
