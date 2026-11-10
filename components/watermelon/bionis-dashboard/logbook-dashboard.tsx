import { Activity, ArrowDownRight, ArrowUpRight, Computer, LogIn, Users, Wifi } from 'lucide-react'
import { Bar, BarChart, CartesianGrid, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts'

type LogbookDashboardData = {
  stats: { computers: number; occupied: number; available: number; completedToday: number; sessionsToday: number }
  weeklyUsage: { label: string; date: string; total: number }[]
  computers: { pcNumber: string; active: boolean; state: string; currentStudent: string | null; lastSeen: string | null }[]
  recentSessions: { student: string; pc: string; subject: string; timeIn: string; status: string }[]
}

function StatCard({ label, value, detail, icon: Icon, accent }: { label: string; value: number; detail: string; icon: typeof Activity; accent: string }) {
  return (
    <article className="flex min-h-36 flex-col justify-between rounded-2xl border bg-card p-5 shadow-sm">
      <div className="flex items-center justify-between gap-3">
        <p className="text-sm text-muted-foreground">{label}</p>
        <span className={`flex size-9 items-center justify-center rounded-xl ${accent}`}><Icon className="size-4" /></span>
      </div>
      <div><p className="text-3xl font-semibold tracking-tight">{value}</p><p className="mt-1 text-xs text-muted-foreground">{detail}</p></div>
    </article>
  )
}

export function LogbookDashboard({ data }: { data: LogbookDashboardData }) {
  const occupancy = data.stats.computers ? Math.round((data.stats.occupied / data.stats.computers) * 100) : 0
  return (
    <div className="mx-auto flex w-full max-w-[1500px] flex-col gap-7">
      <div className="flex flex-wrap items-end justify-between gap-4">
        <div><p className="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-primary">Laboratory overview</p><h1 className="text-2xl font-semibold tracking-tight md:text-3xl">Computer lab activity</h1><p className="mt-2 text-sm text-muted-foreground">A live overview of today&apos;s computer usage.</p></div>
        <a href="/admin/sessions" className="inline-flex h-10 items-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition hover:opacity-90"><Activity className="size-4" />View session log</a>
      </div>

      <section className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard label="Registered computers" value={data.stats.computers} detail="Lab units registered" icon={Computer} accent="bg-blue-500/10 text-blue-600" />
        <StatCard label="Currently occupied" value={data.stats.occupied} detail={`${occupancy}% of registered computers`} icon={Users} accent="bg-amber-500/10 text-amber-600" />
        <StatCard label="Available computers" value={data.stats.available} detail="Active and ready for use" icon={Wifi} accent="bg-emerald-500/10 text-emerald-600" />
        <StatCard label="Sessions today" value={data.stats.sessionsToday} detail={`${data.stats.completedToday} checked out today`} icon={LogIn} accent="bg-violet-500/10 text-violet-600" />
      </section>

      <section className="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1.5fr)_minmax(320px,1fr)]">
        <article className="rounded-2xl border bg-card p-5 shadow-sm md:p-6">
          <div className="mb-6 flex items-start justify-between gap-4"><div><h2 className="font-semibold">Usage this week</h2><p className="mt-1 text-sm text-muted-foreground">Check-ins by day · last 7 days</p></div><span className="rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">7 days</span></div>
          <div className="h-[260px] w-full">
            <ResponsiveContainer width="100%" height="100%"><BarChart data={data.weeklyUsage} margin={{ top: 8, right: 8, left: -20, bottom: 0 }}>
              <CartesianGrid vertical={false} stroke="var(--border)" strokeDasharray="4 4" />
              <XAxis dataKey="label" axisLine={false} tickLine={false} tick={{ fill: 'var(--muted-foreground)', fontSize: 12 }} dy={10} />
              <YAxis allowDecimals={false} axisLine={false} tickLine={false} tick={{ fill: 'var(--muted-foreground)', fontSize: 12 }} />
              <Tooltip cursor={{ fill: 'var(--muted)', opacity: .5 }} contentStyle={{ borderRadius: 12, borderColor: 'var(--border)', background: 'var(--card)', color: 'var(--foreground)' }} labelFormatter={(_, payload) => payload?.[0]?.payload?.date ?? ''} formatter={(value) => [`${value} check-ins`, 'Usage']} />
              <Bar dataKey="total" fill="var(--primary)" radius={[6, 6, 0, 0]} maxBarSize={44} />
            </BarChart></ResponsiveContainer>
          </div>
        </article>

        <article className="rounded-2xl border bg-card p-5 shadow-sm md:p-6">
          <div className="mb-5 flex items-start justify-between gap-4"><div><h2 className="font-semibold">Computer availability</h2><p className="mt-1 text-sm text-muted-foreground">Current station status</p></div><a href="/admin/computers" className="text-xs font-medium text-primary hover:underline">Manage</a></div>
          <div className="flex flex-col gap-2">
            {data.computers.length ? data.computers.slice(0, 7).map((pc) => (
              <div key={pc.pcNumber} className="flex items-center gap-3 rounded-xl border border-transparent bg-muted/60 px-3 py-2.5">
                <span className={`size-2.5 shrink-0 rounded-full ${pc.state === 'Occupied' ? 'bg-amber-500' : pc.state === 'Available' ? 'bg-emerald-500' : 'bg-slate-400'}`} />
                <div className="min-w-0 flex-1"><p className="text-sm font-medium">{pc.pcNumber}</p><p className="truncate text-xs text-muted-foreground">{pc.currentStudent ?? (pc.lastSeen ? `Last seen ${pc.lastSeen}` : 'No activity recorded')}</p></div>
                <span className="text-xs text-muted-foreground">{pc.state}</span>
              </div>
            )) : <div className="flex min-h-40 flex-col items-center justify-center gap-2 rounded-xl border border-dashed text-center"><Computer className="size-6 text-muted-foreground" /><p className="text-sm text-muted-foreground">No computers registered yet.</p><a href="/admin/computers" className="text-sm font-medium text-primary hover:underline">Register a computer</a></div>}
          </div>
        </article>
      </section>

      <article className="rounded-2xl border bg-card p-5 shadow-sm md:p-6">
        <div className="mb-5 flex flex-wrap items-end justify-between gap-3"><div><h2 className="font-semibold">Recent sessions</h2><p className="mt-1 text-sm text-muted-foreground">Latest student activity in the lab</p></div><a href="/admin/sessions" className="inline-flex items-center gap-1 text-sm font-medium text-primary hover:underline">All sessions <ArrowUpRight className="size-4" /></a></div>
        <div className="overflow-x-auto"><table className="w-full min-w-[620px] text-left text-sm">
          <thead><tr className="border-b text-xs text-muted-foreground"><th className="pb-3 font-medium">Student</th><th className="pb-3 font-medium">Computer</th><th className="pb-3 font-medium">Subject</th><th className="pb-3 font-medium">Time in</th><th className="pb-3 font-medium">Status</th></tr></thead>
          <tbody>{data.recentSessions.length ? data.recentSessions.map((session, i) => <tr key={`${session.pc}-${session.timeIn}-${i}`} className="border-b last:border-0"><td className="py-3 font-medium">{session.student}</td><td className="py-3">{session.pc}</td><td className="py-3 text-muted-foreground">{session.subject}</td><td className="py-3 text-muted-foreground">{session.timeIn}</td><td className="py-3"><span className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs ${session.status === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-700' : session.status === 'INTERRUPTED' ? 'bg-amber-500/10 text-amber-700' : 'bg-muted text-muted-foreground'}`}><span className="size-1.5 rounded-full bg-current" />{session.status}</span></td></tr>) : <tr><td colSpan={5} className="py-10 text-center text-muted-foreground">No sessions recorded yet.</td></tr>}</tbody>
        </table></div>
        <div className="mt-3 flex items-center justify-end text-xs text-muted-foreground"><ArrowDownRight className="mr-1 size-3.5" />Times shown in Asia/Manila</div>
      </article>
    </div>
  )
}
