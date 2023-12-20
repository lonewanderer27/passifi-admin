import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";

import "./bootstrap.js";

import {createInertiaApp} from '@inertiajs/react'
import {createRoot} from 'react-dom/client'

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./pages/${name}.jsx`, import.meta.glob('./pages/**/*.jsx')),
    setup({el, App, props}) {
        createRoot(el).render(<App {...props} />)
    },
})
