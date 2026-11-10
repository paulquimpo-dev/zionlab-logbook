"use client";

import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { MdEmail, MdLock, MdVisibility, MdVisibilityOff } from "react-icons/md";

export interface Auth1Props {
  csrfToken: string;
  emailValue?: string;
  error?: string;
}

export function Auth1({ csrfToken, emailValue = "", error }: Auth1Props) {
  const [showPassword, setShowPassword] = useState(false);

  return (
    <main className="bionis-dashboard auth-01-page relative flex min-h-screen w-full items-center justify-center overflow-hidden px-4 py-10 sm:px-6">
      <div aria-hidden="true" className="auth-01-orb auth-01-orb-one" />
      <div aria-hidden="true" className="auth-01-orb auth-01-orb-two" />
      <div className="relative z-10 w-full max-w-[430px]">
        <header className="mb-7 flex flex-col items-center text-center">
          <img src="/images/LZDS-LOGO.png" alt="Lord of Zion Divine School" className="mb-5 h-[76px] w-[190px] object-contain" />
          <span className="auth-01-badge">COMPUTER LABORATORY</span>
          <h1 className="mt-4 text-3xl font-semibold tracking-tight">Administrator sign in</h1>
          <p className="mt-2 max-w-sm text-sm leading-6 text-muted-foreground">Manage computer units and review student lab sessions.</p>
        </header>

        <Card className="auth-01-card gap-0 rounded-3xl border bg-card/95 p-2 shadow-2xl shadow-slate-900/10 backdrop-blur-sm">
          <CardHeader className="space-y-1 px-5 pb-4 pt-5 sm:px-7">
            <CardTitle className="text-lg font-semibold">Welcome back</CardTitle>
            <CardDescription>Sign in with your school administrator account.</CardDescription>
          </CardHeader>
          <CardContent className="px-5 pb-6 sm:px-7">
            {error && <p className="auth-01-error mb-4" role="alert">{error}</p>}
            <form action="/admin/login" method="post" className="space-y-4">
              <input type="hidden" name="_token" value={csrfToken} />
              <div className="space-y-2">
                <label className="text-sm font-medium" htmlFor="admin-email">Email address</label>
                <div className="relative">
                  <MdEmail aria-hidden="true" className="absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                  <Input id="admin-email" name="email" type="email" autoComplete="username" autoFocus required defaultValue={emailValue} placeholder="you@school.edu" className="h-11 rounded-xl pl-10" />
                </div>
              </div>
              <div className="space-y-2">
                <label className="text-sm font-medium" htmlFor="admin-password">Password</label>
                <div className="relative">
                  <MdLock aria-hidden="true" className="absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                  <Input id="admin-password" name="password" type={showPassword ? "text" : "password"} autoComplete="current-password" required placeholder="Enter your password" className="h-11 rounded-xl pl-10 pr-11" />
                  <button type="button" onClick={() => setShowPassword((visible) => !visible)} className="absolute right-3 top-1/2 -translate-y-1/2 rounded p-1 text-muted-foreground hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" aria-label={showPassword ? "Hide password" : "Show password"}>
                    {showPassword ? <MdVisibilityOff className="size-4" /> : <MdVisibility className="size-4" />}
                  </button>
                </div>
              </div>
              <Button type="submit" className="h-11 w-full rounded-xl text-sm font-semibold">Sign in to dashboard</Button>
            </form>
            <div className="mt-5 border-t pt-4 text-center">
              <a className="text-sm font-medium text-primary hover:underline" href="/">Return to student logbook</a>
            </div>
          </CardContent>
        </Card>
        <p className="mt-5 text-center text-xs text-muted-foreground">Authorized school staff only</p>
      </div>
    </main>
  );
}
