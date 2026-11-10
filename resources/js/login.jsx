import React from 'react'
import { createRoot } from 'react-dom/client'
import { Auth1 } from '../../components/ui/auth-01'
import '../../components/watermelon/bionis-dashboard/dashboard.css'
import '../css/app.css'

const root = document.getElementById('auth-01-root')
const props = document.getElementById('auth-01-props')

if (root && props) {
  createRoot(root).render(<Auth1 {...JSON.parse(props.textContent || '{}')} />)
}
